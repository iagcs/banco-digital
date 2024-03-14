<?php

namespace Modules\User\App\Services;

use GuzzleHttp\Client;
use Modules\Finance\App\Models\Transaction;

class MailService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => config('services.mail.base_uri')]);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendNotificationMail(Transaction $transaction): void
    {
        $this->client->get(config('services.mail.endpoints.send'));
    }
}
