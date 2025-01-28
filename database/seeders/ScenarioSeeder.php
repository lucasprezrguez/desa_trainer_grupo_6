<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scenario;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Scenario::create([
                'scenario_name' => 'Escenario ' . $i,
                'image_url' => 'images/scenario_' . $i . '.png'
            ]);
        }

        // Add more scenarios if needed
        // Scenario::create([...]);
    }
}
