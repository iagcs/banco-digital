<?php

namespace Modules\Finance\App\DTO;

use App\Enums\TransactionStatus;
use Modules\Finance\App\DTO\Casts\UserCast;
use Modules\User\App\Models\User;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TransactionData extends Data
{
    public function __construct(
        public Optional | string $id,
        #[WithCast(UserCast::class)]
        public string | User $payer,
        #[WithCast(UserCast::class)]
        public string | User $payee,
        public float $value,
        public Optional | TransactionStatus $status,
    ) {}
}
