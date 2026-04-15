<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attendance::create([
            'user_id' => 1,
            'work_date' => '2026-04-08',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        Attendance::create([
            'user_id' => 2,
            'work_date' => '2026-04-08',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        Attendance::create([
            'user_id' => 1,
            'work_date' => '2026-04-09',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);
    }
}
