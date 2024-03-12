<?php

namespace Modules\Finance\App\Providers;

use Illuminate\Support\ServiceProvider;

class FinanceServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Finance';

    protected string $moduleNameLower = 'finance';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
