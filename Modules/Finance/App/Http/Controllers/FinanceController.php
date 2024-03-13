<?php

namespace Modules\Finance\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Finance\App\Http\Requests\TransactionRequest;
use Modules\Finance\App\Services\FinanceService;

class FinanceController extends Controller
{
    public function __construct(private readonly FinanceService $service) {}

    /**
     * @throws \Spatie\LaravelData\Exceptions\InvalidDataClass
     */
    public function transaction(TransactionRequest $request)
    {
        return $this->service->transaction($request->getData());
    }
}
