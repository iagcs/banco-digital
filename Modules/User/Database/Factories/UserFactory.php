<?php

namespace Modules\User\Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'document' => $this->faker->numerify,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'type' => Arr::random(UserType::cases())
        ];
    }
}

