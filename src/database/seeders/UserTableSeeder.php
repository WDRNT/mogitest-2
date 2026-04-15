<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'tony',
            'email' => 'aho@ahomail.com',
            'password' => Hash::make('00000000'),
        ]);

        User::create([
            'name' => 'an',
            'email' => 'test@ahomail.com',
            'password' => Hash::make('00000000'),
        ]);

        User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('00000000'),
            'role' => 'admin',
        ]);
    }
}
