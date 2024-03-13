<?php

namespace Modules\Finance\App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Modules\Finance\App\DTO\TransactionData;
use Modules\Finance\App\Models\Transaction;
use Modules\User\App\Services\WalletService;
use Symfony\Component\HttpFoundation\Response;

class FinanceService
{
    public function __construct(private readonly WalletService $walletService) {}

    public function transaction(TransactionData $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $transaction = $this->createTransaction($data);

            $this->updateWallets($data);

            $this->verifyTransaction();

            $this->sendEmail($data->payee);

            return $transaction;
        });
    }

    private function sendEmail(User $payee): void
    {

    }

    private function createTransaction(TransactionData $data): Transaction
    {
        $transaction = new Transaction(['value' => $data->value]);

        $transaction->payee()->associate($data->payee);
        $transaction->payer()->associate($data->payer);

        $transaction->save();

        return $transaction;
    }

    private function updateWallets(TransactionData $data): void
    {
        $this->walletService->updateBalance($data->payee, $data->value);
        $this->walletService->updateBalance($data->payer, $data->value * -1.0);
    }

    private function verifyTransaction(): void
    {
        $response = Http::get(config('services.transaction.url'));

        if ($response->failed()) {
            abort(Response::HTTP_BAD_REQUEST, 'Transação não autorizada pelo serviço externo.');
        }
    }
}
