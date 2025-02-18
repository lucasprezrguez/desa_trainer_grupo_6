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
            'require_action' => 'required|boolean',
            'waiting_time' => 'required|integer',
        ]);

        Instruction::create($validated);

        return redirect()->route('instructions.index')->with('success', 'Instrucción creada exitosamente.');
    }

    public function edit($instruction_id)
    {
        $instruction = Instruction::findOrFail($instruction_id);
        return view('admin.instructions.edit', compact('instruction'));
    }

    public function update(Request $request, $instruction_id)
    {
        $validated = $request->validate([
            'instruction_name' => 'required|string|max:255',
            'require_action' => 'required|boolean',
            'waiting_time' => 'required|integer',
        ]);

        $instruction = Instruction::findOrFail($instruction_id);
        $instruction->update($validated);

        return redirect()->route('instructions.index')->with('success', 'Instrucción actualizada exitosamente.');
    }

    public function destroy($instruction_id)
    {
        $instruction = Instruction::findOrFail($instruction_id);
        $instruction->delete();
        return redirect()->route('instructions.index')->with('success', 'Instrucción eliminada con éxito.');
    }
}
