<?php

use Modules\Finance\App\Repositories\FinanceRepository;
use Modules\User\App\Repositories\WalletRepository;
use Modules\Finance\App\Services\TransactionService;
use Modules\Finance\App\Services\FinanceService;
use Modules\Finance\App\Models\Transaction;
use Modules\User\App\Models\User;
use App\Enums\UserType;
use Modules\Finance\App\DTO\TransactionData;
use Illuminate\Support\Facades\Queue;
use Modules\Finance\App\Jobs\ProcessTransaction;
use Modules\User\App\Models\Wallet;
use App\Jobs\SendMailPaymentReceivedJob;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;

describe('test initiate function', function (){
    it('should test initiate transaction suuccesfully', function(){
        $financeRepository = Mockery::mock(FinanceRepository::class);
        $walletRepository = Mockery::mock(WalletRepository::class);
        $transactionService = Mockery::mock(TransactionService::class);

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

        $financeService = new FinanceService($financeRepository, $walletRepository,$transactionService);

        $financeRepository->shouldReceive('createTransaction')
            ->with($transactionData)
            ->andReturn($transaction);

        Queue::fake();

        $financeService->initiateTransaction($transactionData);

        Queue::assertPushed(ProcessTransaction::class);
    });

    it('should test initiate transaction fail', function(){
        $financeRepository = Mockery::mock(FinanceRepository::class);
        $walletRepository = Mockery::mock(WalletRepository::class);
        $transactionService = Mockery::mock(TransactionService::class);

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

        $financeService = new FinanceService($financeRepository, $walletRepository,$transactionService);

        $financeRepository->shouldReceive('createTransaction')
            ->with($transactionData)
            ->andReturn(null);

        Queue::fake();

        $this->expectException(HttpException::class);

        $financeService->initiateTransaction($transactionData);

        $this->expectExceptionMessage("Nao foi possivel inicializar transacao");
        $this->expectExceptionCode(400);

        Queue::assertNotPushed(ProcessTransaction::class);
    });
});

describe('test process transaction function', function(){
     it('should handle function succesfully', function(){
         $financeRepository = Mockery::mock(FinanceRepository::class);
         $walletRepository = Mockery::mock(WalletRepository::class);
         $transactionService = Mockery::mock(TransactionService::class);

         $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
         $payee = User::factory()->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

         Wallet::factory()->for($payer)->create(['balance' => 150]);
         Wallet::factory()->for($payee)->create(['balance' => 100]);

         $transaction = Transaction::factory()->create([
             'payer_id' => $payer->id,
             'payee_id' => $payee->id,
             'value' => 5
         ]);

         $financeService = new FinanceService($financeRepository, $walletRepository,$transactionService);

         $transactionService
             ->shouldReceive('verifyTransactionCanBeMade')
             ->andReturn(true);

         $financeRepository
             ->shouldReceive('updateStatus')
             ->andReturn(NULL);

         $walletRepository
             ->shouldReceive('updateWallets')
             ->andReturn(NULL);

         Queue::fake();

         $financeService->processTransaction($transaction);

         Queue::assertPushed(SendMailPaymentReceivedJob::class);
     });

    it('should handle function failed', function(){
        $financeRepository = Mockery::mock(FinanceRepository::class);
        $walletRepository = Mockery::mock(WalletRepository::class);
        $transactionService = Mockery::mock(TransactionService::class);

        $payer = User::factory()->create(['type' => \App\Enums\UserType::COMMON]);
        $payee = User::factory()->create(['type' => \App\Enums\UserType::SHOPKEEPER]);

        Wallet::factory()->for($payer)->create(['balance' => 150]);
        Wallet::factory()->for($payee)->create(['balance' => 100]);

        $transaction = Transaction::factory()->create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => 5
        ]);

        $financeService = new FinanceService($financeRepository, $walletRepository,$transactionService);

        $transactionService
            ->shouldReceive('verifyTransactionCanBeMade')
            ->andReturn(FALSE);

        $financeRepository
            ->shouldReceive('updateStatus')
            ->andReturn(NULL);

        Queue::fake();

        $this->expectException(ValidationException::class);

        $financeService->processTransaction($transaction);

        $this->expectExceptionMessage("Transação não autorizada pelo serviço externo.");
        $this->expectExceptionCode(400);

        Queue::assertNotPushed(SendMailPaymentReceivedJob::class);
    });
});
