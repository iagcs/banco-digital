<?php

use Modules\Finance\App\Repositories\FinanceRepository;
use Modules\Finance\App\Models\Transaction;
use App\Enums\TransactionStatus;
use Modules\User\App\Models\User;
use App\Enums\UserType;
use Modules\Finance\App\DTO\TransactionData;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

describe('test finance repository class', function(){

    it('should create a transaction', function () {
        $transaction = Transaction::factory()->make([
            'payer_id' => User::factory()->create(['type' => UserType::COMMON])->id,
            'payee_id' => User::factory()->create(['type' => UserType::SHOPKEEPER])->id
        ]);

        $transactionData = [
            'value' => $transaction->value,
            'payer' => $transaction->payee_id,
            'payee' => $transaction->payee_id
        ];

        $transactionData = TransactionData::from($transactionData);

        $financeRepository = new FinanceRepository;

        $result = $financeRepository->createTransaction($transactionData);

        expect($result)->toBeInstanceOf(Transaction::class)
            ->and($result?->value)->toEqual($transactionData->value)
            ->and($result?->payer_id)->toEqual($transactionData->payer->id)
            ->and($result?->payee_id)->toEqual($transactionData->payee->id);
    });


    it('should update transaction status', function (){
        $transactionRepository = new FinanceRepository;

        $transaction = Transaction::factory()->forPayee()->forPayer()->create(['status' => TransactionStatus::WAITING]);

        $transactionRepository->updateStatus($transaction, TransactionStatus::SUCCESS);

        assertDatabaseCount('transactions', 1);
        assertDatabaseHas('transactions', [
           'id' => $transaction->id,
           'status' => TransactionStatus::SUCCESS->value
        ]);
    });
});
