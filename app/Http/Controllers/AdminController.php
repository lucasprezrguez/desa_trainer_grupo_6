<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $scenarios = Scenario::all(); // Obtener todos los escenarios
        return view('admin.dashboard', compact('scenarios'));
    }

    public function updateBpm(Request $request)
    {
        $validated = $request->validate([
            'bpm' => 'required|in:100,110,120'
        ]);

        session(['metronome_bpm' => $request->bpm]);
        
        return back()->with('success', 'Metrónomo actualizado correctamente');
    }

    public function toggleScenarios(Request $request)
    {
        $validated = $request->validate([
            'scenario_id' => 'required|exists:scenarios,scenario_id',
            'is_enabled' => 'required|boolean',
        ]);

        $scenario = Scenario::findOrFail($validated['scenario_id']);
        $scenario->update(['is_enabled' => $validated['is_enabled']]);

        return response()->json(['success' => true, 'scenario_id' => $scenario->scenario_id, 'is_enabled' => $scenario->is_enabled]);
    }
}