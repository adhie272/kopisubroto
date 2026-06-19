<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kopi.test'],
            [
                'name' => 'Admin Kopi',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@kopi.test'],
            [
                'name' => 'Kasir Kopi',
                'password' => bcrypt('password'),
                'role' => 'kasir',
            ]
        );
    }
}
