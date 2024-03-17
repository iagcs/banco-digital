<?php

use Modules\Finance\App\Services\TransactionService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

describe('test transaction service', function(){
    it('test veryfy transaction can be made', function(){
        Http::fake([
            config('services.transaction.base_uri').config('services.transaction.endpoints.verify') => Http::response(['message' => 'Autorizado']),
        ]);

        $transactionService = new TransactionService;

        $bool = $transactionService->verifyTransactionCanBeMade();

        $this->assertTrue($bool);
    });

    it("test veryfy transaction can't be made", function(){
        Http::fake([
            config('services.transaction.base_uri').config('services.transaction.endpoints.verify') => Http::response(['message' => 'Nao autorizado'], Response::HTTP_UNAUTHORIZED),
        ]);

        $transactionService = new TransactionService;

        $bool = $transactionService->verifyTransactionCanBeMade();

        $this->assertFalse($bool);
    });
});
