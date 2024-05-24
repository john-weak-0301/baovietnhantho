<?php

namespace App\Providers;

use App\Http\Controllers\Privates\MigrateController;
use App\Model;
use App\Model\Experience;
use App\Model\Page;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\Scopes\PublishedScope;
use App\Model\Services\ProductService;
use Illuminate\Routing\Route as SingleRoute;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Orchid\Platform\Dashboard;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The models binding.
     *
     * @see https://laravel.com/docs/5.8/routing#route-model-binding
     *
     * @var array
     */
    protected $models = [
        'pageId' => Page::class,
        'newsId' => Model\News::class,
        'userId' => Model\User::class,
        'serviceId' => Model\Service::class,
        'categoryId' => Model\Category::class,
        'counselorId' => Model\Counselor::class,
        'personalityId' => Model\Personality::class,
        'productCategoryId' => ProductCategory::class,
        // 'productId'         => Model\Product::class,
        'branchServiceId' => Model\BranchService::class,
        'branchId' => Model\Branch::class,
        'consultantId' => Model\Consultant::class,
        'contactId' => Model\Contact::class,
        'serviceCategoryId' => Model\ServiceCategory::class,
        // 'expId'             => Model\Experience::class,
        'expCategoryId' => Model\ExperienceCategory::class,
        'blockId' => Model\Block::class,
        'popupId' => Model\Popup::class,
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->bindings();

        parent::boot();
    }

    /**
     * Register models binding.
     *
     * @return void
     */
    protected function bindings(): void
    {
        foreach ($this->models as $model => $class) {
            Route::model($model, $class);
        }

        Route::bind('productId', function ($id) {
            return Product::withoutGlobalScopes(['productType', PublishedScope::class])
                ->whereKey($id)
                ->firstOrFail();
        });

        Route::bind('expId', function ($id) {
            return Experience::withoutGlobalScope(PublishedScope::class)
                ->whereKey($id)
                ->firstOrFail();
        });

        Route::bind('pageSlug', function ($slug) {
            $query = Page::whereSlug($slug);

            if (!Gate::allows(Page::PERMISSION_TOUCH)) {
                $query->where('status', Page::STATUS_PUBLISH);
            }

            return $query->firstOrFail();
        });

        Route::bind('productName', function ($slug, SingleRoute $route) {
            $categorySlug = $route->parameter('productCategorySlug');

            return app(ProductService::class)->getBySlug($slug, $categorySlug);
        });

        Route::bind('productCategoryName', function ($name) {
            return ProductCategory::findBySlugOrFail($name);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();

        $this->app->booted(function () {
            $this->mapWebRoutes();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        $privateMiddleware = config('platform.middleware.private');
        $privateMiddleware[] = \App\Http\Middleware\RestrictedArea::class;

        Route::prefix('__private/__area/__doNotEnter/__22/__10/__18')
            ->middleware($privateMiddleware)
            ->group(function () {
                Route::get('__command/__let_run_migration__', MigrateController::class);
            });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
