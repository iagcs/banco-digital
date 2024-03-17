<?php

namespace Modules\Finance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Finance\App\DTO\TransactionData;
use Modules\Finance\App\Rules\SufficientBalance;
use Modules\Finance\App\Rules\TransactionPermission;
use Modules\User\App\Models\User;
use Spatie\LaravelData\WithData;

class TransactionRequest extends FormRequest
{
    use WithData;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payee' => [
                'bail',
                'required',
                'uuid',
                'exists:users,id'
            ],
            'payer' => [
                'bail',
                'required',
                'uuid',
                'exists:users,id',
                $this->input('payer') ? new TransactionPermission : ''
            ],
            'value' => [
                'bail',
                'required',
                'numeric',
                $this->input('payer') ? new SufficientBalance($this->input('payer')) : ''
            ]
        ];
    }

    public function dataClass(): string
    {
        return TransactionData::class;
    }
}
