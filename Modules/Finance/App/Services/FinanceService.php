<?php

namespace Modules\Finance\App\Services;

use App\Enums\TransactionStatus;
use App\Jobs\SendMailPaymentReceivedJob;
use Illuminate\Validation\ValidationException;
use Modules\Finance\App\DTO\TransactionData;
use Modules\Finance\App\Jobs\ProcessTransaction;
use Modules\Finance\App\Models\Transaction;
use Modules\Finance\App\Repositories\FinanceRepository;
use Modules\User\App\Repositories\WalletRepository;

class FinanceService
{
    public function __construct(
        private readonly FinanceRepository $financeRepository,
        private readonly WalletRepository $walletRepository,
        private readonly TransactionService $transactionService
    ) {}

    public function initiateTransaction(TransactionData $data): void
    {
        $transaction = $this->financeRepository->createTransaction($data);

        if(!$transaction){
            abort(400, "Nao foi possivel inicializar transacao");
        }

        ProcessTransaction::dispatch($transaction);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function processTransaction(Transaction $transaction): void
    {
        if ($this->transactionService->verifyTransactionCanBeMade()) {
            $this->handleSuccessfulTransaction($transaction);

           SendMailPaymentReceivedJob::dispatch($transaction->toArray());

            return;
        }

        $this->handleFailedTransaction($transaction);
    }

    private function handleSuccessfulTransaction(Transaction $transaction): void
    {
        $this->financeRepository->updateStatus($transaction, TransactionStatus::SUCCESS);

        $this->walletRepository->updateWallets($transaction->payer, $transaction->payee, $transaction->value);
    }

    private function handleFailedTransaction(Transaction $transaction): void
    {
        $this->financeRepository->updateStatus($transaction, TransactionStatus::FAILED);

        throw ValidationException::withMessages(['message' => 'Transação não autorizada pelo serviço externo.']);
    }
}
