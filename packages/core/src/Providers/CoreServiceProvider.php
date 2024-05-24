<?php

namespace Core\Providers;

use Core\User\Command\EditUser;
use Core\User\Command\EditUserHandler;
use Core\User\Command\UploadAvatar;
use Core\User\Command\UploadAvatarHandler;
use Core\User\User;
use Core\User\Event\Saving;
use Core\User\SelfDemotionGuard;
use Core\User\GateHandler;
use Orchid\Platform\Dashboard;
use Core\Dashboard\DashboardProvider;
use Core\Macros\FirstDayOfQuarter;
use Core\Macros\FirstDayOfPreviousQuarter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Auth\Access\Gate;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPermissionsOnGate($this->app->make(Gate::class));

        $this->registerEvents($this->app->make(Dispatcher::class));

        $this->registerPublishing();

        $this->registerCarbonMacros();

        $this->loadRoutes();

        require_once dirname(__DIR__, 2).'/functions/functions.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(DashboardProvider::class);
    }

    /**
     * Binding the events on the application.
     *
     * @param  Dispatcher  $events
     */
    protected function registerEvents(Dispatcher $events)
    {
        $events->listen(Saving::class, SelfDemotionGuard::class);

        $events->listen(EditUser::class, EditUserHandler::class);
        $events->listen(UploadAvatar::class, UploadAvatarHandler::class);
    }

    /**
     * Register the permission check method on the gate.
     *
     * @param  Gate  $gate
     * @return void
     */
    protected function registerPermissionsOnGate(Gate $gate): void
    {
        $gate->before(function ($user, string $ability, array $arguments) {
            if ($user instanceof User) {
                return GateHandler::handle($user, $ability, $arguments[0] ?? null);
            }
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->loadViewsFrom(dirname(__DIR__, 2).'/resources/views/core', 'core');

        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');
    }

    /**
     * Load the routes if routes are not already cached.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/'))
            ->middleware(config('platform.middleware.private'))
            ->group(dirname(__DIR__, 2).'/routes/dashboard.php');
    }

    /**
     * Register the Carbon macros.
     *
     * @return void
     */
    protected function registerCarbonMacros(): void
    {
        Carbon::macro('firstDayOfQuarter', new FirstDayOfQuarter);
        Carbon::macro('firstDayOfPreviousQuarter', new FirstDayOfPreviousQuarter);
    }
}
