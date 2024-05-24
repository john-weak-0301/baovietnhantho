<?php

namespace App\Dashboard\Layouts;

use App\SEO\SEOConfigure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class SEOLayout extends Rows
{
    /**
     * {@inheritdoc}
     */
    public function fields(): array
    {
        return [
            Input::make('seotools.seo_title')
                ->maxlength(100)
                ->title(__('SEO Title')),

            TextArea::make('seotools.seo_description')
                ->rows(2)
                ->title(__('SEO Description')),

            Input::make('seotools.meta_keywords')
                ->maxlength(100)
                ->title(__('Meta keywords')),

            Select::make('seotools.robots')
                ->title(__('Robots'))
                ->options(array_combine(SEOConfigure::$robots, SEOConfigure::$robots)),

            Input::make('seotools.canonical')
                ->maxlength(100)
                ->title(__('Canonical URL')),

            Picture::make('seotools.seo_image')
                ->title(__('Ảnh SEO'))
                ->targetUrl(),
        ];
    }

    public static function values(Request $request)
    {
        $atts = Arr::only((array) ($request->input('seotools') ?: []), [
            'seo_title',
            'seo_description',
            'meta_keywords',
            'robots',
            'canonical',
            'seo_image'
        ]);

        return map_deep($atts, 'clean');
    }
}
