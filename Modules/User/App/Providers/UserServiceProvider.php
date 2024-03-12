<?php

namespace Modules\User\App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'User';

    protected string $moduleNameLower = 'user';

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
