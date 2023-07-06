<?php

namespace Database\Factories;

use App\Models\RoleUser;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleUserFactory extends Factory
{
    protected $model = RoleUser::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'role_id' => Role::inRandomOrder()->first()->id,
        ];
    }
}
