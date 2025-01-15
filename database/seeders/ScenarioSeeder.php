<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('scenarios')->insert([
            'name' => 'Sample Scenario',
            'description' => 'This is a sample scenario.',
            'dicharge_numbers' => 1,
            'min_interval' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
