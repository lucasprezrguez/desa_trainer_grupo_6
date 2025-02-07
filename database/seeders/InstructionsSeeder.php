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
                'type' => 'emergency'
            ],
            [
                'instruction_name' => 'Una descarga para conversión',
                'tts_description' => 'Administrar una descarga eléctrica para conversión de ritmo.',
                'type' => 'treatment'
            ],
            [
                'instruction_name' => 'Ritmo no desfibrilable',
                'tts_description' => 'Identificar ritmo no desfibrilable en el paciente.',
                'type' => 'emergency'
            ],
            [
                'instruction_name' => 'RCP antes ante ritmo desfibrilable',
                'tts_description' => 'Realizar RCP antes de analizar ritmo desfibrilable.',
                'type' => 'procedure'
            ]
        ];

        foreach ($instructions as $instruction) {
            Instruction::create($instruction);
        }
    }
}