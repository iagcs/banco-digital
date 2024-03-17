<?php

namespace Modules\User\Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\User\App\Models\User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid,
            'name' => fake()->name,
            'document' => fake()->numerify,
            'email' => fake()->email,
            'password' => fake()->password,
            'type' => Arr::random(UserType::cases())
        ];
    }
}

