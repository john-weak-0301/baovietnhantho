<?php

namespace Core\Dashboard;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Core\Dashboard\Permissions\MediaPermission;

class DashboardProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @param  Dashboard  $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        View::prependNamespace('platform', dirname(__DIR__, 2).'/resources/views/orchid');

        View::composer('platform::dashboard', Composers\MainMenuComposer::class);
        View::composer('platform::systems', Composers\SystemMenuComposer::class);

        $dashboard
            ->registerPermissions($this->registerMediaPermissions());
    }

    /**
     * Register the permission for the Media.
     *
     * @return ItemPermission
     */
    protected function registerMediaPermissions(): ItemPermission
    {
        return tap(ItemPermission::group(__('Media')), function (ItemPermission $permission) {
            foreach (MediaPermission::getLabels() as $key => $label) {
                $permission->addPermission($key, $label);
            }
        });
    }
}
