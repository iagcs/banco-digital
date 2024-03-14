<?php

namespace Modules\Finance\App\Rules;

use App\Enums\UserType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\App\Models\User;

class TransactionPermission implements ValidationRule
{
    private $user;

    public function __construct(private string $payer)
    {
        $this->user = User::query()->find($this->payer);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->user->type === UserType::SHOPKEEPER){
            $fail('Usuarios do tipo lojistas nao tem permissao para realizar transferencias.');
        }
    }
}
