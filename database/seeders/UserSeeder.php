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
     * 
     * Creates default users for each role:
     * - Admin: admin@baiturrohim.com / password
     * - Bendahara: bendahara@baiturrohim.com / password
     * - Staff: staff@baiturrohim.com / password
     */
    public function run(): void
    {
        // Create Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@baiturrohim.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Bendahara user
        User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@baiturrohim.com',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
        ]);

        // Create Staff user
        User::create([
            'name' => 'Staff',
            'email' => 'staff@baiturrohim.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
