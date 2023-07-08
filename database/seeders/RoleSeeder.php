<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        Role::create([
            'role' => 'admin',
        ]);
        // Create an customer user
        Role::create([
            'role' => 'seller',
        ]);
        // Create an customer user
        Role::create([
            'role' => 'customer',
        ]);
    }
}
