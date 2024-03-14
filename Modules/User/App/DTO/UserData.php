<?php

namespace Modules\User\App\DTO;

use App\Enums\UserType;
use Illuminate\Support\Optional;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public Optional | string $id,
        public string $name,
        public string $document,
        public string $email,
        public string $password,
        public UserType $type
    ) {}
}
