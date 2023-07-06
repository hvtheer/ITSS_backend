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
            'role_id' => '1'
        ]);

        // RoleUser::factory()->count(10)->create();
    }
}