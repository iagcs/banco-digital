<?php

namespace Modules\User\App\DTO;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class WalletData extends Data
{
    public function __construct(
      public Optional | string $id,
      public float $balance
    ) {}
}
