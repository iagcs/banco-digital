<?php

namespace Modules\User\App\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\User\App\Models\User;

class WalletRepository
{
    public function updateWallets(User $payer, User $payee, float $value): void
    {
        $this->updateBalance($payee, $value);
        $this->updateBalance($payer, $value * -1.0);
    }

    public function updateBalance(User $user, float $value): void
    {
        DB::transaction(static function () use ($user, $value){
            $user->wallet->balance += $value;
            $user->push();
        });
    }
}
