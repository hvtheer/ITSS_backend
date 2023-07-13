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
            'deleted' => false
        ]);

        RoleUser::create([
            'user_id' => '2',
            'role_id' => '3',
            'deleted' => false
        ]);

        RoleUser::create([
            'user_id' => '3',
            'role_id' => '3',
            'deleted' => false
        ]);

        RoleUser::factory()->count(4)->create();
    }
}