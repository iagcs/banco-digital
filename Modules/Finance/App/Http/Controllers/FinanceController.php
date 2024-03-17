<?php

namespace Modules\Finance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Finance\App\Http\Requests\TransactionRequest;
use Modules\Finance\App\Services\FinanceService;
use Symfony\Component\HttpFoundation\Response;

class FinanceController extends Controller
{
    public function __construct(private readonly FinanceService $service) {}

    /**
     * @throws \Spatie\LaravelData\Exceptions\InvalidDataClass
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $this->service->initiateTransaction($request->getData());

        return new JsonResponse(['data' => 'Transacao iniciada'], Response::HTTP_OK);
    }
}
