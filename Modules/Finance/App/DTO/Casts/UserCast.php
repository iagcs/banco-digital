<?php

namespace Modules\Finance\App\DTO\Casts;

use Modules\User\App\Models\User;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class UserCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return User::query()->find($value);
    }
}
