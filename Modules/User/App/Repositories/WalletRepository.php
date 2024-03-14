<?php

namespace Modules\User\App\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Finance\App\DTO\TransactionData;
use Modules\User\App\DTO\WalletData;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;

class WalletRepository
{
    public function store(WalletData $data): WalletData
    {
        return DB::transaction(static function () use ($data): WalletData{
            return Wallet::query()->create($data->toArray())->getData();
        });
    }

    public function updateWallets(TransactionData $data): void
    {
        $this->updateBalance($data->payee, $data->value);
        $this->updateBalance($data->payer, $data->value * -1.0);
    }

    public function updateBalance(User $user, float $value): void
    {
        DB::transaction(static function () use ($user, $value){
            $user->wallet->balance += $value;
            $user->push();
        });
    }
}
