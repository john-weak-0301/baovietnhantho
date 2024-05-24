<?php

namespace App\Providers;

use App\Model\Experience;
use App\Model\News;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\ServiceCategory;
use App\Http\Composers\HeaderMenuComposer;
use App\Http\Composers\FooterMenuComposer;
use App\Http\Composers\CategoryMenuComposer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as SingleView;
use CyrildeWit\EloquentViewable\Support\Period;
use Lavary\Menu\Builder;
use Lavary\Menu\Facade as Menu;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('layouts.main', HeaderMenuComposer::class);
        View::composer('partials.footer', FooterMenuComposer::class);

        View::composer('news.category', CategoryMenuComposer::class);
        View::composer('news.top', function ($view) {
            $query = Route::is(['exps', 'exps.*', 'exp'])
                ? Experience::query()
                : News::query();

            $topNews = cache()->remember(
                'top_five::'.get_class($query->getModel()),
                now()->addHours(12),
                function () use ($query) {
                    try {
                        return $query
                            ->orderByDesc('unique_views_count')
                            ->take(5)
                            ->get();
                    } catch (\Exception $e) {
                        return $query
                            ->orderByUniqueViews('desc', Period::pastYears(1))
                            ->take(5)
                            ->get();
                    }
                }
            );

            $view->with('topNews', $topNews);
        });
        View::composer('news.slider', function ($view) {
            $class = Route::is(['exps', 'exps.*', 'exp'])
                ? Experience::class
                : News::class;

            $view
                ->with('sliders', $class::inSlider())
                ->with('features', $class::feature());
        });

        View::composer(['header._product-items', 'partials.mobile-menu'], function ($view) {
            /* @var Collection $products */
            $products = cache()->rememberForever('products.with-category', function () {
                return Product::query()
                    ->has('categories')
                    ->orderByDesc('order')
                    ->latest()
                    ->limit(500)
                    ->get();
            });

            $products = $products
                ->mapToGroups(function (Product $item, $key) {
                    return [$item->category->name => $item];
                });

            /* @var SingleView $view */
            $view->with('productsWithCategory', $products);
        });

        View::composer(['header.service', 'partials.mobile-menu'], function ($view) {
            $categories = cache()->rememberForever('service.categories', function () {
                return ServiceCategory::query()
                    ->where('show_in_menu', true)
                    ->latest('order')
                    ->take(15)
                    ->get();
            });

            /* @var SingleView $view */
            $view->with('serviceCategories', $categories);
        });

        View::composer('pages.header-san-pham', function ($view) {
            Menu::make('productCategories', function (Builder $menu) {
                $productCategories = ProductCategory::orderByDesc('order')->latest()->get();

                $menu->add(__('Tất cả'), 'san-pham');
                foreach ($productCategories as $category) {
                    $menu->add($category->name, url(route('product.category', $category->slug)));
                }
            });
        });

        register_block_type('core/heading', [
            'render_callback' => function ($args, $text) {
                $id = Str::slug(clean($text));

                return preg_replace('/(<h[^><]*)>/i', '$1 id="toc-'.$id.'">', $text);
            },
        ]);

        register_block_type('cgb/block-common-anchor', [
            'render_callback' => function ($args) {
                if (empty($args['anchor'])) {
                    return '';
                }

                return sprintf(
                    '<div id="anchor-%2$s" title="%1$s"></div>',
                    $args['anchor'],
                    Str::slug($args['anchor'])
                );
            },
        ]);

        register_block_type('core/shortcode', [
            'attributes' => [
                'text' => [
                    'type' => 'string',
                    'source' => 'html',
                ],
            ],
            'render_callback' => function ($args, $content) {
                return wpautop(do_shortcode(wp_kses_post($content)));
            },
        ]);

        add_filter('the_content', 'do_blocks', 9);
        // add_filter('the_content', 'wpautop');
        add_filter('the_content', 'shortcode_unautop');
        add_filter('the_content', 'do_shortcode', 11);
    }
}
