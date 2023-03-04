<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Mark Ponce',
                'email' => 'markponce07@gmail.com',
                'password' => Hash::make('@water123'),
            ],
            [
                'name' => 'Apple Dela Cruz',
                'email' => 'appledc@gmail.com',
                'password' => Hash::make('@water123'),
            ],
        ];

        foreach ($users as $key => $users) {
            User::create($users);
        }
    }
}
