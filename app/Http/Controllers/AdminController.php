<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Scenario;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get top 5 users by completed scenarios
        $topUsers = User::with(['progress.scenario'])
            ->withCount('progress')
            ->orderByDesc('progress_count')
            ->take(5)
            ->get()
            ->map(function ($user) {
                $scenarioCounts = $user->progress->groupBy('scenario_id')
                    ->map(function ($progresses) {
                        return [
                            'name' => $progresses->first()->scenario->scenario_name,
                            'count' => $progresses->count()
                        ];
                    })->values();

                return [
                    'name' => $user->name,
                    'completed' => $user->progress_count,
                    'scenarios' => $scenarioCounts
                ];
            });

        // Get scenario completion statistics
        $scenarioStats = Scenario::withCount('progress')
            ->orderByDesc('progress_count')
            ->get()
            ->map(function ($scenario) {
                return [
                    'name' => $scenario->scenario_name,
                    'completed' => $scenario->progress_count
                ];
            });

        $scenarios = Scenario::all();
        $instructions = \App\Models\Instruction::all();

        return view('admin.dashboard', compact('topUsers', 'scenarioStats', 'scenarios', 'instructions'));
    }

    public function updateBpm(Request $request)
    {
        $request->validate([
            'bpm' => 'required|integer|in:100,110,120'
        ]);

        session(['metronome_bpm' => $request->bpm]);
        return response()->json(['success' => true]);
    }

    public function updateWaitingTime(Request $request)
    {
        $request->validate([
            'instruction_id' => 'required|integer',
            'waiting_time' => 'required|integer|min:1'
        ]);

        // Update the waiting time in the instructions table
        \App\Models\Instruction::where('instruction_id', $request->instruction_id)
            ->update(['waiting_time' => $request->waiting_time]);

        return response()->json(['success' => true]);
    }

    public function toggleScenarios(Request $request)
    {
        $request->validate([
            'scenario_id' => 'required|integer',
            'is_enabled' => 'required|boolean'
        ]);

        $scenario = Scenario::findOrFail($request->scenario_id);
        $scenario->is_enabled = $request->is_enabled;
        $scenario->save();

        return response()->json(['success' => true]);
    }
}