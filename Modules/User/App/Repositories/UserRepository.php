<?php

namespace Modules\User\App\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\User\App\DTO\UserData;
use Modules\User\App\Models\User;

class UserRepository
{
    public function store(UserData $data): UserData
    {
        return DB::transaction(static function () use ($data): UserData{
            return User::query()->create($data->toArray())->getData();
        });
    }
}
