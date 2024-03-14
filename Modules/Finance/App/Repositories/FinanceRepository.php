<?php

namespace Modules\Finance\App\Repositories;

use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\DB;
use Modules\Finance\App\DTO\TransactionData;
use Modules\Finance\App\Models\Transaction;

class FinanceRepository
{
    public function createTransaction(TransactionData $data): Transaction | null
    {
        return DB::transaction(static function () use ($data): Transaction | null {
            $transaction = new Transaction(['value' => $data->value]);

            $transaction->payee()->associate($data->payee);
            $transaction->payer()->associate($data->payer);

            return $transaction->save() ? $transaction : null;
        });
    }

    public function updateStatus(Transaction $transaction, TransactionStatus $status): void
    {
        $transaction->status = $status;
        $transaction->save();
    }
}
