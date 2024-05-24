<?php

namespace App\SEO;

use App\Model\Helpers\HasUrlPresenter;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Str;

class SEOConfigure
{
    public static $robots = [
        'index, follow',
        'noindex, nofollow',
        'index, nofollow',
        'noindex, follow',
    ];

    public static function config(WithSEOMeta $model): void
    {
        if ($model->isSEOConfigured()) {
            return;
        }

        $image = '';
        if (method_exists($model, 'getImageUrl')) {
            $image = $model->getImageUrl();
        } elseif (!empty($model->image)) {
            $image = $model->image;
        }

        $attributes = array_merge([
            'seo_title'       => '',
            'seo_description' => '',
            'meta_keywords'   => '',
            'robots'          => static::$robots[0],
            'canonical'       => '',
            'seo_image'       => Str::startsWith($image, ['http', '/']) ? $image : '',
        ], $model->getSeoAttributes());

        if (empty($attributes['seo_title'])) {
            $attributes['seo_title'] = $model->getSeoTitle();
        }

        if (request()->is('/')) {
            $attributes['seo_title'] = '';
        }

        if ($attributes['seo_title']) {
            SEOTools::setTitle($attributes['seo_title']);
        }

        if ($attributes['seo_description']) {
            SEOTools::setDescription($attributes['seo_description']);
        }

        if ($model instanceof HasUrlPresenter && empty($attributes['canonical'])) {
            $attributes['canonical'] = $model->url->link();
        }

        if ($attributes['canonical']) {
            SeoTools::setCanonical($attributes['canonical']);
        }

        if ($attributes['seo_image']) {
            SeoTools::addImages($attributes['seo_image']);
        }

        $metatags = SEOTools::metatags();
        $metatags->setRobots($attributes['robots']);

        if ($attributes['meta_keywords']) {
            $metatags->addKeyword($attributes['meta_keywords']);
        }

        $model->isSEOConfigured = true;
    }
}
