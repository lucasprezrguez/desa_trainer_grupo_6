<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class DESAController extends Controller
{
    public function recordCompletion(Request $request)
    {
        $request->validate([
            'scenario_id' => 'required|exists:scenarios,scenario_id'
        ]);

        UserProgress::create([
            'user_id' => auth()->id(),
            'scenario_id' => $request->scenario_id,
            'completed_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
} 