<?php

namespace Modules\Finance\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Finance\App\Models\Transaction;
use Modules\Finance\App\Repositories\FinanceRepository;
use Modules\Finance\App\Services\FinanceService;

class ProcessTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Transaction $transaction) {}

    /**
     * Execute the job.
     */
    public function handle(FinanceService $service): void
    {
        $service->processTransaction($this->transaction);
    }
}
