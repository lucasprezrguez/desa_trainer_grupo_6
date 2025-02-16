<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\ScenarioInstruction;
use Illuminate\Http\Request;

class ScenarioController extends Controller
{
    public function index(){
        $scenarios = Scenario::withCount('instructions')->get(); 
        return view('admin.scenarios.index', compact('scenarios'));
    }

    public function create(){
        return view('admin.scenarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scenario_name' => 'required|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'instructions' => 'required|array',
            'instructions.*' => 'exists:instructions,instruction_id'
        ]);

        $scenario = Scenario::create([
            'scenario_name' => $validated['scenario_name'],
            'image_url' => $validated['image_url'] ?? null
        ]);

        $scenarioInstructionData = [
            [
                'instruction_id' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'instruction_id' => 1,
                'params' => 'Se enciende el botón de descarga.'
            ],
            [
                'instruction_id' => 2,
                'params' => 'Esperar hasta que el usuario pulse el botón de descarga.'
            ],
            [
                'instruction_id' => 4,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardiopulmonar'
            ],
            [
                'instruction_id' => 3,
                'params' => 'Se reproduce el audio: Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'instruction_id' => 3,
                'params' => 'No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar.'
            ],
            [
                'instruction_id' => 5, // ID de "Contacto deficiente de los electrodos"
                'params' => 'Contacto deficiente de los electrodos. Revise y ajuste los electrodos para asegurar buen contacto.'
            ]
        ];

        $selectedInstructions = array_filter($scenarioInstructionData, function ($instruction) use ($validated) {
            return in_array($instruction['instruction_id'], $validated['instructions']);
        });

        $order = 1;

        // Crear cada entrada en ScenarioInstruction
        foreach ($selectedInstructions as $instruction) {
            ScenarioInstruction::create([
                'scenario_id' => $scenario->scenario_id,
                'instruction_id' => $instruction['instruction_id'],
                'order' => $order++,
                'reps' => 1,
                'params' => $instruction['params']
            ]);
        }

        return redirect()->route('scenarios.index')->with('success', 'Escenario creado exitosamente.');
    }

    public function show($scenario_id){
        return view('admin.scenarios.show', ['id' => $scenario_id]);
    }

    public function edit($scenario_id)
    {
        $scenario = Scenario::with(['instructions' => function($query) {
            $query->orderBy('scenario_instruction.order');
        }])->findOrFail($scenario_id);
        
        return view('admin.scenarios.edit', compact('scenario'));
    }

    public function update(Request $request, $scenario_id)
    {
        $scenario = Scenario::find($scenario_id);
        $validated = $request->validate([
            'scenario_name' => 'required|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        $scenario->update($validated);

        return redirect()->route('scenarios.index')->with('success', 'Escenario actualizado exitosamente.');
    }

    public function destroy($scenario_id)
    {
        $scenario = Scenario::findOrFail($scenario_id);
        $scenario->delete();

        return redirect()->route('scenarios.index')->with('success', 'Escenario eliminado con éxito.');
    }
}
