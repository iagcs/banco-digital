<?php

namespace Modules\User\App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Finance\App\Models\Transaction;
//use Modules\User\Database\factories\UserFactory;

class User extends Model
{
    use HasUuids/*, HasFactory*/;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'document',
        'password',
    ];

    protected $casts = [
        'type' => UserType::class
    ];

    protected $hidden = [
        'password'
    ];

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function outflow_transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function inflow_transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    /*protected static function newFactory(): UserFactory
    {
        //return UserFactory::new();
    }*/
}
