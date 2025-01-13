
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceSeeder extends Seeder
{
    public function run()
    {
        DB::table('devices')->insert([
            [
                'name' => 'Device 1',
                'on_led' => true,
                'pause_state' => false,
                'display_message' => 'Hello World',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Device 2',
                'on_led' => false,
                'pause_state' => true,
                'display_message' => 'Device Paused',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
