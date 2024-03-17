<?php

use Modules\Finance\App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Modules\Finance\App\Jobs\ProcessTransaction;
use Illuminate\Validation\ValidationException;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendMailPaymentReceivedJob;

describe('test job actions', function () {
    it('should test job that process transaction authorized', function () {

        $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
        $payee = User::factory()->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

        Wallet::factory()->for($payer)->create(['balance' => 150]);
        Wallet::factory()->for($payee)->create(['balance' => 100]);

        $transaction = Transaction::factory()->create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => 5
        ]);

        Http::fake([
            config('services.transaction.base_uri').config('services.transaction.endpoints.verify') => Http::response(['message' => 'Autorizado']),
        ]);

        \Pest\Laravel\assertDatabaseCount('transactions', 1);
        \Pest\Laravel\assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => \App\Enums\TransactionStatus::WAITING->value,
        ]);

        ProcessTransaction::dispatchSync($transaction);

        \Pest\Laravel\assertDatabaseCount('transactions', 1);
        \Pest\Laravel\assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => \App\Enums\TransactionStatus::SUCCESS->value,
        ]);

        \Pest\Laravel\assertDatabaseCount('wallets', 2);
        \Pest\Laravel\assertDatabaseHas('wallets', [
            'user_id' => $transaction->payee_id,
            'balance' => 105
        ]);
        \Pest\Laravel\assertDatabaseHas('wallets', [
            'user_id' => $transaction->payer_id,
            'balance' => 145
        ]);
    });

    it('should test job that process transaction not authorized', function () {
        $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
        $payee = User::factory()->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

        Wallet::factory()->for($payer)->create(['balance' => 150]);
        Wallet::factory()->for($payee)->create(['balance' => 100]);

        $transaction = Transaction::factory()->create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => 5
        ]);

        Http::fake([
            config('services.transaction.base_uri').config('services.transaction.endpoints.verify') => Http::response(['message' => 'Nao autorizado'], Response::HTTP_UNAUTHORIZED),
        ]);

        \Pest\Laravel\assertDatabaseCount('transactions', 1);
        \Pest\Laravel\assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => \App\Enums\TransactionStatus::WAITING->value,
        ]);

        $this->expectException(ValidationException::class);

        Queue::fake([SendMailPaymentReceivedJob::class]);

        ProcessTransaction::dispatchSync($transaction);

        $this->expectExceptionMessage('Transação não autorizada pelo serviço externo.');

        Queue::assertPushedOn('mail-queue', SendMailPaymentReceivedJob::class);

        \Pest\Laravel\assertDatabaseCount('transactions', 1);
        \Pest\Laravel\assertDatabaseHas('transactions', [
            'id'     => $transaction->id,
            'status' => \App\Enums\TransactionStatus::FAILED->value,
        ]);

        \Pest\Laravel\assertDatabaseCount('wallets', 2);
        \Pest\Laravel\assertDatabaseHas('wallets', [
            'user_id' => $transaction->payee_id,
            'balance' => 100
        ]);
        \Pest\Laravel\assertDatabaseHas('wallets', [
            'user_id' => $transaction->payer_id,
            'balance' => 150
        ]);
    });
});
