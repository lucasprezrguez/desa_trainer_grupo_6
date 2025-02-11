<?php

namespace App\Http\Controllers;

use App\Models\Instruction;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    public function index()
    {
        $instructions = Instruction::all();
        return view('admin.instructions.index', compact('instructions'));
    }

    public function create()
    {
        return view('admin.instructions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'instruction_name' => 'required|string|max:255',
            'tts_description' => 'required|string',
            'require_action' => 'required|boolean',
            'type' => 'required|string',
            'waiting_time' => 'required|integer',
        ]);

        Instruction::create($validated);

        return redirect()->route('instructions.index')->with('success', 'Instrucción creada exitosamente.');
    }

    public function edit(Instruction $instruction)
    {
        return view('admin.instructions.edit', compact('instruction'));
    }

    public function update(Request $request, Instruction $instruction)
    {
        $validated = $request->validate([
            'instruction_name' => 'required|string|max:255',
            'tts_description' => 'required|string',
            'require_action' => 'required|boolean',
            'type' => 'required|string',
            'waiting_time' => 'required|integer',
        ]);

        
        $instruction->update($validated);

        return redirect()->route('instructions.index')->with('success', 'Instrucción actualizada exitosamente.');
    }

    public function destroy(Instruction $instruction)
    {
        $instruction->delete();
        return redirect()->route('instructions.index')->with('success', 'Instrucción eliminada con éxito.');
    }
}
