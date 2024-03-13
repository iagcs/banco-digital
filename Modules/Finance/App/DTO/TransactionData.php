<?php

namespace Modules\Finance\App\DTO;

use App\Enums\TransactionStatus;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class TransactionData extends Data
{
    public function __construct(
        public string $payer,
        public string $payee,
        public float $value,
        public Optional | TransactionStatus $status,
    ) {}
}
