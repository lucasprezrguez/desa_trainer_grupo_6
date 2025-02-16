<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scenario;
use App\Models\Instruction;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            $scenario = Scenario::create([
                'scenario_name' => 'aedt3_sce_' . $i,
                'image_url' => 'images/aedt3_sce_' . $i . '.png'
            ]);
        }
    }
}
