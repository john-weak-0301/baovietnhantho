<?php

namespace App\Providers;

use App\Shortcodes\AboutAsideMenu;
use App\Shortcodes\BCTN;
use App\Shortcodes\Block;
use App\Shortcodes\GT;
use App\Shortcodes\LKHTC;
use App\Formatter\ShortcodeManager;
use App\Shortcodes\CategoryProduct;
use App\Shortcodes\ExperiencePost;
use App\Shortcodes\FeaturedProducts;
use App\Shortcodes\FormContact;
use App\Shortcodes\LKHTC2;
use App\Shortcodes\MENUDSSP;
use App\Shortcodes\RelatedProducts;
use App\Shortcodes\SPBT;
use Illuminate\Support\ServiceProvider;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $shortcode = ShortcodeManager::getInstance();

        $shortcode->register('SPPB', new FeaturedProducts());
        $shortcode->register('SPLQ', new RelatedProducts());
        $shortcode->register('SPTDM', new CategoryProduct());
        $shortcode->register('GPPH', new CategoryProduct());
        $shortcode->register('SPBT', new SPBT());

        $shortcode->register('CSKN', new ExperiencePost());
        $shortcode->register('FORMLIENHE', new FormContact());

        $shortcode->register('LKHTC', new LKHTC());
        $shortcode->register('LKHTC2', new LKHTC2());
        $shortcode->register('GTVCT', new AboutAsideMenu());
        $shortcode->register('block', new Block());

        $shortcode->register('BCTN', new BCTN());
        $shortcode->register('GT', new GT());

        $shortcode->register('MENUDSSP', new MENUDSSP());
    }
}
