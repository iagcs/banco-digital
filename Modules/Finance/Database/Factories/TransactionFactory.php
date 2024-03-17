<?php

namespace Modules\Finance\Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Finance\App\Models\Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'value' => fake()->randomFloat(2, 0, 10)
        ];
    }
}

