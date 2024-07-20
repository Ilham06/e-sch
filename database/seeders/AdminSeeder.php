<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => 'Administrator 1',
            'email' => 'admin1@mail.com',
            'password' => Hash::make('password'),
        ];

        $user = User::create($data);
        // $user->assignRole('administrator');
    }
}
