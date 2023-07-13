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
            'deleted' => false
        ]);
        // Create an customer user
        Role::create([
            'role' => 'seller',
            'deleted' => false
        ]);
        // Create an customer user
        Role::create([
            'role' => 'customer',
            'deleted' => false
        ]);
    }
}
