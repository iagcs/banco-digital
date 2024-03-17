<?php

use function Pest\Laravel\post;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;
use Illuminate\Support\Facades\Queue;
use Modules\Finance\App\Jobs\ProcessTransaction;

describe('test transaction actions', function () {

    it('should show fail basic validations', function ($data, $error) {
        post('/transaction', $data)->assertInvalid($error);
    })->with([
        'payer:required' => [
            [], // Dados da requisição vazios
            ['payer' => 'required'] // Erro esperado
        ],
        'payee:required' => [
            [], // Dados da requisição vazios
            ['payee' => 'required'] // Erro esperado
        ],
        'value:required' => [
            [], // Dados da requisição vazios
            ['value' => 'required'] // Erro esperado
        ],
        'value:numeric'  => [
            ['value' => 'teste'], // Dados da requisição vazios
            ['value' => 'The value field must be a number.'] // Erro esperado
        ],
    ]);

    describe('test failed validations from transaction', function () {
        it('should fail payer cant make transaction if is shopkeeper', function () {
            $payer = User::factory()->has(Wallet::factory())->create(['type' => \App\Enums\UserType::SHOPKEEPER]);
            $payee = User::factory()->has(Wallet::factory())->create(['type' => \App\Enums\UserType::COMMON]);

            post('/transaction', [
                'payer' => $payer->id,
                'payee' => $payee->id,
                'value' => fake()->randomFloat(),
            ])->assertInvalid([
                "payer" => "Usuarios do tipo lojistas nao tem permissao para realizar transferencias.",
            ]);
        });

        it("should fail user don't  have wallet", function () {
            $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
            $payee = User::factory()->has(Wallet::factory())->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

            post('/transaction', [
                'payer' => $payer->id,
                'payee' => $payee->id,
                'value' => fake()->randomFloat(),
            ])->assertInvalid([
                "value" => "Esse usuario nao tem uma carteira cadastrada.",
            ]);
        });

        it("should fail user don't  have sufficient balance", function () {

            $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
            $payee = User::factory()->has(Wallet::factory())->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

            Wallet::factory()->for($payer)->create(['balance' => 10]);

            post('/transaction', [
                'payer' => $payer->id,
                'payee' => $payee->id,
                'value' => fake()->randomFloat(),
            ])->assertInvalid([
                "value" => "Esse usuario nao tem saldo suficiente para realizar essa transacao.",
            ]);
        });
    });

    describe('test success transaction', function () {
        it('should create transaction', function () {
            Queue::fake();

            $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
            $payee = User::factory()->has(Wallet::factory())->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

            Wallet::factory()->for($payer)->create(['balance' => 10]);

            post('/transaction', [
                'payer' => $payer->id,
                'payee' => $payee->id,
                'value' => 5,
            ])->assertSuccessful()
                ->assertValid([
                    'data' => 'Transacao iniciada',
                ]);

            \Pest\Laravel\assertDatabaseCount('transactions', 1);
            \Pest\Laravel\assertDatabaseHas('transactions', [
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'value'    => 5,
                'status'   => \App\Enums\TransactionStatus::WAITING->value
            ]);

            Queue::assertPushed(ProcessTransaction::class);
        });
    });
});
