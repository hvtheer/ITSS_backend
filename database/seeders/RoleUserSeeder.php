<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        RoleUser::create([
            'user_id' => '1',
            'role_id' => '1',
            'status' => 'approved'
        ]);

        RoleUser::create([
            'user_id' => '2',
            'role_id' => '3',
            'status' => 'pending'
        ]);

        RoleUser::create([
            'user_id' => '3',
            'role_id' => '3',
            'status' => 'pending'
        ]);

        // RoleUser::factory()->count(10)->create();
    }
}