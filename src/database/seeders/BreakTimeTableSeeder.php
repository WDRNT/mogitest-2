<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BreakTime;

class BreakTimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BreakTime::create([
            'attendance_id' => 1,
            'break_in' => '2026-04-08 10:00:00',
            'break_out' => '2026-04-08 11:00:00',
        ]);

        BreakTime::create([
            'attendance_id' => 1,
            'break_in' => '2026-04-08 12:00:00',
            'break_out' => '2026-04-08 12:05:00',
        ]);

    }
}
