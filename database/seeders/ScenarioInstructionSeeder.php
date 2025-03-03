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
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 2,
                'order' => 3,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 4,
                'order' => 4,
                'reps' => 0,
                'params' => 'Se dio una descarga. En pausa. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 1,
                'order' => 5,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 1,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario2Instructions = [
            [
                'scenario_id' => 2,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 4,
                'order' => 2,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 1,
                'order' => 3,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 1,
                'order' => 4,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 2,
                'order' => 5,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 4,
                'order' => 4,
                'reps' => 0,
                'params' => 'Se dio una descarga. En pausa. Inicie reanimación cardiopulmonar'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 1,
                'order' => 6,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'scenario_id' => 2,
                'instruction_id' => 4,
                'order' => 7,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario3Instructions = [
            [
                'scenario_id' => 3,
                'instruction_id' => 5,
                'order' => 1,
                'reps' => 0,
                'params' => 'Aplique los electrodos en pecho desnudo del paciente. Pulse el botón de descarga para continuar.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. No toque al paciente. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 2,
                'order' => 3,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 4,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 1,
                'order' => 5,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Manténgase alejado del paciente. Analizando el ritmo cardíaco.'
            ],
            [
                'scenario_id' => 3,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario4Instructions = [
            [
                'scenario_id' => 4,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 2,
                'order' => 2,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 3,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 1,
                'order' => 4,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 5,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 1,
                'order' => 6,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 2,
                'order' => 7,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 8,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 1,
                'order' => 9,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 4,
                'instruction_id' => 4,
                'order' => 10,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario5Instructions = [
            [
                'scenario_id' => 5,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 5,
                'instruction_id' => 4,
                'order' => 2,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario6Instructions = [
            [
                'scenario_id' => 6,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 2,
                'order' => 2,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 4,
                'order' => 3,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 1,
                'order' => 4,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 2,
                'order' => 5,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 1,
                'order' => 7,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 6,
                'instruction_id' => 4,
                'order' => 8,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario7Instructions = [
            [
                'scenario_id' => 7,
                'instruction_id' => 4,
                'order' => 1,
                'reps' => 0,
                'params' => 'Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 2,
                'order' => 3,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 4,
                'order' => 4,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 1,
                'order' => 5,
                'reps' => 0,
                'params' => 'Detenga la reanimación cardiopulmonar. Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 7,
                'instruction_id' => 4,
                'order' => 6,
                'reps' => 0,
                'params' => 'No se recomienda dar una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];

        $scenario8Instructions = [
            [
                'scenario_id' => 8,
                'instruction_id' => 1,
                'order' => 1,
                'reps' => 0,
                'params' => 'Analizando el ritmo cardíaco. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 8,
                'instruction_id' => 1,
                'order' => 2,
                'reps' => 0,
                'params' => 'Se recomienda dar una descarga. Manténgase alejado del paciente.'
            ],
            [
                'scenario_id' => 8,
                'instruction_id' => 2,
                'order' => 2,
                'reps' => 0,
                'params' => 'Pulse el botón de descarga.'
            ],
            [
                'scenario_id' => 8,
                'instruction_id' => 4,
                'order' => 3,
                'reps' => 0,
                'params' => 'Se dio una descarga. Inicie reanimación cardiopulmonar.'
            ]
        ];
        
        foreach ($scenario1Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario2Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario3Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario4Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario5Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario6Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario7Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }
        foreach ($scenario8Instructions as $scenarioInstruction) {
            ScenarioInstruction::create($scenarioInstruction);
        }

    }
}