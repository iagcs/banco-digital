<?php

namespace Modules\Finance\App\Rules;

use App\Enums\UserType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\App\Models\User;

class TransactionPermission implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::find($value);
        if($user->type === UserType::SHOPKEEPER){
            $fail('Usuarios do tipo lojistas nao tem permissao para realizar transferencias.');
        }
    }
}
