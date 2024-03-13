<?php

namespace Modules\User\App\Services;

use Modules\User\App\Models\User;

class WalletService
{
    public function updateBalance(User $user, float $value): void
    {
        $user->wallet->balance += $value;
        $user->push();
    }
}
