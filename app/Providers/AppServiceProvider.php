<?php

namespace App\Providers;

use App\Model;
use App\Model\Observers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_COLLATE, 'vi_VN.utf8');

        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Model\News::observe(Observers\NewsObserver::class);
        Model\Product::observe(Observers\ProductObserver::class);

        if (!function_exists('do_shortcode')) {
            require app_path('Utils'.DIRECTORY_SEPARATOR.'functions.php');
        }

        $this->app->booted(function () {
            if (defined('APP_INSTALLING') || $this->app->runningInConsole()) {
                return;
            }

            $settings = setting([
                'suggested_y-te',
                'suggested_tai-chinh',
                'suggested_giao-duc',
                'suggested_dau-tu',
                'suggested_huu-tri',
            ]);

            foreach (config('press.objectives') as $id => $value) {
                $ids = $settings['suggested_'.$id] ?? null;

                if ($ids !== null && is_array($ids) && count($ids) > 0) {
                    $this->app['config']['press.objectives.'.$id.'.related'] = $ids;
                }
            }

            if (!config('captcha.secret') && !config('captcha.sitekey')) {
                config()->set('captcha.sitekey', setting('recaptcha_sitekey'));
                config()->set('captcha.secret', setting('recaptcha_secret'));
            }
        });
    }
}
