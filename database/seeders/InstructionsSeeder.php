<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instruction;

class InstructionsSeeder extends Seeder
{
    public function run()
    {
        $instructions = [
            [
                'instruction_name' => 'Análisis de ritmo',
                'require_action' => true,
                'waiting_time' => 5
            ],
            [
                'instruction_name' => 'Administrar descarga',
                'require_action' => true, 
                'waiting_time' => 10
            ],
            [
                'instruction_name' => 'Espera de confirmación',
                'require_action' => true, 
                'waiting_time' => 15
            ],
            [
                'instruction_name' => 'Iniciar RCP',
                'require_action' => false,
                'waiting_time' => 120  
            ],
            [
                'instruction_name' => 'Contacto deficiente de los electrodos',
                'require_action' => true,
                'waiting_time' => 5
            ]
        ];

        foreach ($instructions as $instruction) {
            Instruction::updateOrCreate(
                ['instruction_name' => $instruction['instruction_name']],
                $instruction
            );
        }
    }
}