<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('1111'), // You may change this password according to your requirements
            'deleted' => false
        ]);

        // Create some user and seller users
        User::factory()->count(5)->create();

        // Create one more custom user with a specific role
        User::create([
            'username' => 'custom_user',
            'email' => 'custom_user@example.com',
            'password' => bcrypt('1111'),
            'deleted' => false
        ]);
    }
}
