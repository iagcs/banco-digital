<?php

namespace Modules\Finance\App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Modules\Finance\Database\factories\FinanceOperationFactory;
use Modules\User\App\Models\User;

class Transaction extends Model
{
    use HasUuids /*, HasFactory*/;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'value',
        'status'
    ];

    protected $casts = [
        'status' => TransactionStatus::class
    ];

    protected $attributes = [
        'status' => TransactionStatus::WAITING
    ];

    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /*protected static function newFactory(): FinanceOperationFactory
    {
        //return FinanceOperationFactory::new();
    }*/
}
