<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instruction;

class InstructionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $instructions = [
            [
                'instruction_name' => 'Ritmo desfibrilable',
                'tts_description' => 'Identificar ritmo desfibrilable en el paciente.',
                'require_action' => false,
                'type' => 'procedure',
                'waiting_time' => '5'
            ],
            [
                'instruction_name' => 'Una descarga para conversión',
                'tts_description' => 'Administrar una descarga eléctrica para conversión de ritmo.',
                'require_action' => false,
                'type' => 'treatment',
                'waiting_time' => '10'
            ],
            [
                'instruction_name' => 'Ritmo no desfibrilable',
                'tts_description' => 'Identificar ritmo no desfibrilable en el paciente.',
                'require_action' => false,
                'type' => 'emergency',
                'waiting_time' => '15'
            ],
            [
                'instruction_name' => 'RCP antes ante ritmo desfibrilable',
                'tts_description' => 'Realizar RCP antes de analizar ritmo desfibrilable.',
                'require_action' => false,
                'type' => 'procedure',
                'waiting_time' => '10'
            ]
        ];

        foreach ($instructions as $instruction) {
            Instruction::create($instruction);
        }
    }
}