<?php

declare(strict_types=1);

namespace App\Orchid;

use Core\Media\Media;
use Orchid\Attachment\Models\Attachment;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Authorizable;
use App\Orchid\Composers\MainMenuComposer;
use App\Orchid\Composers\SystemMenuComposer;

class PlatformProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @param  Dashboard  $dashboard
     *
     * @throws \Throwable
     */
    public function boot(Dashboard $dashboard): void
    {
        Dashboard::useModel(Attachment::class, Media::class);

        View::composer('platform::dashboard', MainMenuComposer::class);
        View::composer('platform::systems', SystemMenuComposer::class);

        $this->registerPermissionsOnGate(
            $this->app->make(Gate::class)
        );

        $dashboard
            ->registerPermissions($this->registerPermissionsForPage())
            ->registerPermissions($this->registerPermissionsForNews())
            ->registerPermissions($this->registerPermissionsForServices())
            ->registerPermissions($this->registerPermissionsForCategories())
            ->registerPermissions($this->registerPermissionsForContacts())
            ->registerPermissions($this->registerPermissionsForCounselors())
            ->registerPermissions($this->registerPermissionsForPersonalities())
            ->registerPermissions($this->registerPermissionsForProductCategories())
            ->registerPermissions($this->registerPermissionsForProducts())
            ->registerPermissions($this->registerPermissionsForBranchs())
            ->registerPermissions($this->registerPermissionsForBranchService())
            ->registerPermissions($this->registerPermissionsForConsultants())
            ->registerPermissions($this->registerPermissionsForServiceCategory())
            ->registerPermissions($this->registerPermissionsForExp())
            ->registerPermissions($this->registerPermissionsForExpCategory())
            ->registerPermissions($this->registerPermissionsSystems())
            ->registerPermissions($this->registerPermissionsForPopup())
            ->registerPermissions($this->registerPermissionsForFund());

        $dashboard->registerGlobalSearch([
            //...Models
        ]);
    }

    /**
     * Register the permission check method on the gate.
     *
     * @param  Gate  $gate
     * @return void
     */
    public function registerPermissionsOnGate(Gate $gate): void
    {
        $gate->before(function (Authorizable $user, string $ability): bool {
            if (method_exists($user, 'hasAccess')) {
                return $user->hasAccess($ability);
            }

            return true;
        });
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsSystems(): ItemPermission
    {
        return ItemPermission::group(__('Systems'))
            ->addPermission('platform.systems.menu', __('Menu'))
            ->addPermission('platform.systems.roles', __('Roles'))
            ->addPermission('platform.systems.users', __('Users'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForPage(): ItemPermission
    {
        return ItemPermission::group(__('Pages'))
            ->addPermission('platform.pages.view', __('View pages'))
            ->addPermission('platform.pages.touch', __('Create or update pages'))
            ->addPermission('platform.pages.delete', __('Trash or delete pages'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForNews(): ItemPermission
    {
        return ItemPermission::group(__('News'))
            ->addPermission('platform.news.view', __('View News'))
            ->addPermission('platform.news.touch', __('Create or update news'))
            ->addPermission('platform.news.delete', __('Trash or delete news'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForServices(): ItemPermission
    {
        return ItemPermission::group(__('Services'))
            ->addPermission('platform.services.view', __('View Services'))
            ->addPermission('platform.services.touch', __('Create or update services'))
            ->addPermission('platform.services.delete', __('Trash or delete services'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForCategories(): ItemPermission
    {
        return ItemPermission::group(__('Categories'))
            ->addPermission('platform.categories.view', __('View Categories'))
            ->addPermission('platform.categories.touch', __('Create or update categories'))
            ->addPermission('platform.categories.delete', __('Trash or delete categories'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForContacts(): ItemPermission
    {
        return ItemPermission::group(__('Contacts'))
            ->addPermission('platform.contacts.view', __('View Contacts'))
            ->addPermission('platform.contacts.delete', __('Trash or delete contacts'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForCounselors(): ItemPermission
    {
        return ItemPermission::group(__('Counselors'))
            ->addPermission('platform.counselors.view', __('View Counselors'))
            ->addPermission('platform.counselors.touch', __('Create or update counselors'))
            ->addPermission('platform.counselors.delete', __('Trash or delete counselors'));
    }

    /**
     * @return ItemPermission
     */
    protected function registerPermissionsForPersonalities(): ItemPermission
    {
        return ItemPermission::group(__('Personalities'))
            ->addPermission('platform.personalities.view', __('View Personalities'))
            ->addPermission('platform.personalities.touch', __('Create or update personalities'))
            ->addPermission('platform.personalities.delete', __('Trash or delete personalities'));
    }

    protected function registerPermissionsForProductCategories(): ItemPermission
    {
        return ItemPermission::group(__('Product Categories'))
            ->addPermission('platform.products_categories.view', __('View Product Categories'))
            ->addPermission('platform.products_categories.touch', __('Create or update product categories'))
            ->addPermission('platform.products_categories.delete', __('Trash or delete product categories'));
    }

    protected function registerPermissionsForProducts(): ItemPermission
    {
        return ItemPermission::group(__('Products'))
            ->addPermission('platform.products.view', __('View Products'))
            ->addPermission('platform.products.touch', __('Create or update products'))
            ->addPermission('platform.products.delete', __('Trash or delete products'));
    }

    protected function registerPermissionsForBranchs(): ItemPermission
    {
        return ItemPermission::group(__('Branchs'))
            ->addPermission('platform.branchs.view', __('View Branchs'))
            ->addPermission('platform.branchs.touch', __('Create or update branchs'))
            ->addPermission('platform.branchs.delete', __('Trash or delete branchs'));
    }

    protected function registerPermissionsForBranchService(): ItemPermission
    {
        return ItemPermission::group(__('Branch Service'))
            ->addPermission('platform.branchs_services.view', __('View Branch Services'))
            ->addPermission('platform.branchs_services.touch', __('Create or update branch services'))
            ->addPermission('platform.branchs_services.delete', __('Trash or delete branch services'));
    }

    protected function registerPermissionsForConsultants(): ItemPermission
    {
        return ItemPermission::group(__('Consultants'))
            ->addPermission('platform.consultants.view', __('View Consultants'))
            ->addPermission('platform.consultants.touch', __('Create or update consultants'))
            ->addPermission('platform.consultants.delete', __('Trash or delete consultants'));
    }

    protected function registerPermissionsForServiceCategory(): ItemPermission
    {
        return ItemPermission::group(__('Service Category'))
            ->addPermission('platform.services_categories.view', __('View Service Categories'))
            ->addPermission('platform.services_categories.touch', __('Create or update service categories'))
            ->addPermission('platform.services_categories.delete', __('Trash or delete service categories'));
    }

    protected function registerPermissionsForExp(): ItemPermission
    {
        return ItemPermission::group(__('Exps'))
            ->addPermission('platform.exps.view', __('View exps'))
            ->addPermission('platform.exps.touch', __('Create or update exps'))
            ->addPermission('platform.exps.delete', __('Trash or delete exps'));
    }

    protected function registerPermissionsForExpCategory(): ItemPermission
    {
        return ItemPermission::group(__('Exps'))
            ->addPermission('platform.exps_categories.view', __('View exps categories'))
            ->addPermission('platform.exps_categories.touch', __('Create or update exps categories'))
            ->addPermission('platform.exps_categories.delete', __('Trash or delete exps categories'));
    }

    protected function registerPermissionsForPopup(): ItemPermission
    {
        return ItemPermission::group(__('Popup'))
            ->addPermission('platform.exps_categories.view', __('View popup'))
            ->addPermission('platform.exps_categories.touch', __('Create or update popup'))
            ->addPermission('platform.exps_categories.delete', __('Trash or delete popup'));
    }

    protected function registerPermissionsForFund(): ItemPermission
    {
        return ItemPermission::group(__('Fund'))
            ->addPermission('platform.funds.view', __('View fund'))
            ->addPermission('platform.funds.touch', __('Create or update fund'))
            ->addPermission('platform.funds.delete', __('Trash or delete fund'));
    }
}
