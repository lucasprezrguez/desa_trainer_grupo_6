<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard'); // Asegúrate que esta vista exista
    }

    public function updateBpm(Request $request)
    {
        $validated = $request->validate([
            'bpm' => 'required|in:100,110,120'
        ]);

        session(['metronome_bpm' => $request->bpm]);
        
        return back()->with('success', 'Metrónomo actualizado correctamente');
    }
}