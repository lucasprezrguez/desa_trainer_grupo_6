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
        $scenario1Instructions = [
            [
                'scenario_id' => 1,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 1,
                'params' => 'Se enciende el botón de descarga.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 2,
                'order' => 3,
                'reps' => 1,
                'params' => 'Esperar hasta que el usuario pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 4,
                'order' => 4,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 3,
                'order' => 6,
                'reps' => '0',
                'params' => 'Se reproduce el audio: Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 3,
                'order' => 7,
                'reps' => '0',
                'params' => 'No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar.'
            ]
        ];

        $scenario3Instructions = [
            [
                'scenario_id' => 3,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 1,
                'params' => 'Apriete los electrodos firmemente sobre la piel desnuda del pecho del paciente'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 3,
                'order' => 3,
                'reps' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 3,
                'order' => 4,
                'reps' => 1,
                'params' => 'Se enciende el botón de descarga'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 5,
                'reps' => 1,
                'params' => '"Pulse el botón de descarga"'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 7,
                'reps' => 0,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 8,
                'reps' => 0,
                'params' => 'Detenga la resucitación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 9,
                'reps' => 0,
                'params' => 'Volvemos al paso 7'
            ]
        ];

        //escenario 4

        $scenario4Instructions = [
            [
                'scenario_id' => 4,
                'instruction_id' => 2,
                'order' => 1,
                'reps' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 3,
                'order' => 2,
                'reps' => 1,
                'params' => 'Espere 10 segundos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 3,
                'order' => 3,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 3,
                'order' => 4,
                'reps' => 1,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 5,
                'reps' => 1,
                'params' => 'Detenga resuciación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 1,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 7,
                'reps' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 8,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 9,
                'reps' => 0,
                'params' => 'La aplicación se queda en espera durante 2 minutos'	
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 10,
                'reps' => 0,
                'params' => 'Detenga resuciación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar'	
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 11,
                'reps' => 0,
                'params' => 'Volvemos al paso 9'
            ]
        ];

        //escenario 5

        $scenario5Instructions = [
            [
                'scenario_id' => 5,
                'instruction_id' => 2,
                'order' => 1,
                'reps' => 1,
                'params' => 'Detenga la resucitacion cardiopulmonar. Analizando el ritmo cardiaco. Mantengase alejado del paciente. Analizando el ritmo cardiaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitacion cardiopulmonar'
            ],
            [
                'scenario_id' => 5,
                'instruction_id' => 3,
                'order' => 2,
                'reps' => 1,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 3,
                'order' => 3,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 3,
                'order' => 4,
                'reps' => 1,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 5,
                'reps' => 1,
                'params' => 'Detenga resuciación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 1,
                'params' => 'La aplicación se queda en espera durante 2 minutos'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 7,
                'reps' => 1,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Se recomienda dar una descarga. Se carga el aparato. Manténgase alejado del paciente. Dé una descarga ahora. Pulse el botón naranja'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 8,
                'reps' => 1,
                'params' => 'Se dio una descarga. En pausa. Inicie resucitación cardio pulmonar'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 9,
                'reps' => 0,
                'params' => 'La aplicación se queda en espera durante 2 minutos'	
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 10,
                'reps' => 0,
                'params' => 'Detenga resuciación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente. Analizando el ritmo cardíaco. No se recomienda dar una descarga. En pausa. Si es necesario, inicie resucitación cardiopulmonar'	
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 11,
                'reps' => 0,
                'params' => 'Volvemos al paso 9'
            ]
        ];



        
        foreach ($scenario1Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }

        foreach ($scenario3Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
    }
}