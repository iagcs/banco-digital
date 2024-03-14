<?php

namespace Modules\Finance\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Finance\App\DTO\TransactionData;
use Modules\Finance\App\Rules\SufficientBalance;
use Modules\Finance\App\Rules\TransactionPermission;
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
                new TransactionPermission($this->input('payer'))
            ],
            'value' => [
                'bail',
                'required',
                'numeric',
                new SufficientBalance($this->input('payer'))
            ]
        ];
    }

    public function dataClass(): string
    {
        return TransactionData::class;
    }
}
