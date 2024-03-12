<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Modules\User\Database\factories\WalletFactory;

class Wallet extends Model
{
    use HasUuids /*, HasFactory*/;

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

    /*
    protected static function newFactory(): WalletFactory
    {
        //return WalletFactory::new();
    }*/
}
