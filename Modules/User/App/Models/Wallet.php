<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\App\DTO\WalletData;
use Modules\User\Database\Factories\WalletFactory;
use Spatie\LaravelData\WithData;

class Wallet extends Model
{
    use HasUuids, WithData, HasFactory;

    protected $dataClass = WalletData::class;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'balance'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    protected static function newFactory(): WalletFactory
    {
        return WalletFactory::new();
    }
}
