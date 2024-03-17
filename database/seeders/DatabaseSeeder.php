<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
        $payee = User::factory()->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

        Wallet::factory()->for($payer)->create(['balance' => 150]);
        Wallet::factory()->for($payee)->create(['balance' => 100]);
    }
}
