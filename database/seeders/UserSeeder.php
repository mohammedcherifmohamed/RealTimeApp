<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name"     => "Mohamed",
            "email"    => "mohamed@gmail.com",
            "password" => Hash::make('aze'),
        ]);
        User::create([
            "name"     => "Anes",
            "email"    => "Anes@gmail.com",
            "password" => Hash::make('aze'),
        ]);
    }
}
