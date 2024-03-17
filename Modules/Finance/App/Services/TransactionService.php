<?php

namespace Modules\Finance\App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    private PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.transaction.base_uri'));
    }


    public function verifyTransactionCanBeMade(): bool
    {
        $status = $this->client->get(config('services.transaction.endpoints.verify'))->status();

        return $status === Response::HTTP_OK;
    }
}
