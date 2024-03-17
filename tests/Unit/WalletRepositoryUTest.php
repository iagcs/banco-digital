<?php

use Modules\User\App\Repositories\WalletRepository;
use Modules\User\App\Models\User;
use Modules\User\App\Models\Wallet;
use function Pest\Laravel\assertDatabaseHas;

describe('test wallet repository', function(){
   it('should update wallet', function(){
       $walletRepository = new WalletRepository;

       $user = User::factory()->create();

       Wallet::factory()->for($user)->create([
           'balance' => 5
       ]);

       $walletRepository->updateBalance($user, 5);

       assertDatabaseHas('wallets', [
           'user_id' => $user->id,
           'balance' => 10
       ]);
   });
});
