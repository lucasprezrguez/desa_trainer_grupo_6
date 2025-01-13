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
        $request->validate([
            'text_content' => 'required|string',
            'require_action' => 'required|boolean',
            'action_type' => 'required|in:discharge,electrodes,none',
            'waiting_time' => 'required|integer',
        ]);

        Instruction::create($request->all());

        return redirect()->route('instructions.index')->with('success', 'Instrucción creada con éxito.');
    }

    public function edit(Instruction $instruction)
    {
        return view('admin.instructions.edit', compact('instruction'));
    }

    public function update(Request $request, Instruction $instruction)
    {
        $request->validate([
            'text_content' => 'required|string',
            'require_action' => 'required|boolean',
            'action_type' => 'required|in:discharge,electrodes,none',
            'waiting_time' => 'required|integer',
        ]);

        $instruction->update($request->all());

        return redirect()->route('instructions.index')->with('success', 'Instrucción actualizada con éxito.');
    }

    public function destroy(Instruction $instruction)
    {
        $instruction->delete();
        return redirect()->route('instructions.index')->with('success', 'Instrucción eliminada con éxito.');
    }
}
