<?php

namespace Modules\Finance\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\User\App\Models\User;

class SufficientBalance implements ValidationRule
{
    private User $user;

    public function __construct(private string $payer)
    {
        $this->user = User::find($this->payer);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!$this->user->wallet){
            abort(400,'Esse usuario nao tem uma carteira cadastrada.');
        }

        if($this->user->wallet->balance < $value){
            abort(400,'Esse usuario nao tem saldo suficiente para realizar essa transacao.');
        }
    }
}
