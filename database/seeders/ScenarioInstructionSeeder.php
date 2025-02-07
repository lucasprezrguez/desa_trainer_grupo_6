<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScenarioInstruction;

class ScenarioInstructionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $scenarioInstructions = [
            [
                'scenario_id' => 1,
                'instruction_id' => 2,
                'order' => 1,
                'repeticiones' => 1,
                'parametros' => '"Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja"'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 3,
                'order' => 2,
                'repeticiones' => 1,
                'parametros' => 'Se enciende el botón de descarga.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 3,
                'order' => 3,
                'repeticiones' => 1,
                'parametros' => 'Esperar hasta que el usuario pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 3,
                'order' => 4,
                'repeticiones' => 1,
                'parametros' => '"Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar"'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 5,
                'order' => 5,
                'repeticiones' => 1,
                'parametros' => 'Esperar 2 minutos.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 4,
                'order' => 6,
                'repeticiones' => '0',
                'parametros' => 'Se reproduce el audio: "Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar."'
            ]
        ];

        foreach ($scenarioInstructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
    }
}
