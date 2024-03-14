<?php

namespace Modules\Finance\App\Services;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.transaction.base_uri')]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyTransactionCanBeMade(): bool
    {
        $status = $this->client->get(config('services.transaction.endpoints.verify'))->getStatusCode();

        return $status === Response::HTTP_OK;
    }
}
