<?php

namespace App\Http\Controllers;

use App\Model\Experience;
use App\Model\ExperienceCategory;
use App\SEO\SEOConfigure;
use App\Utils\ContentParser;

class ExperienceController extends Controller
{
    public function home()
    {
        $news = Experience::query()
            ->latest('published_date')
            ->orderByDesc('order')
            ->paginate(5);

        return view('news-category', [
            'news'  => $news,
            'title' => __('Góc chuyên gia'),
        ]);
    }

    public function show($slug)
    {
        /* @var $news \App\Model\News */
        $news = Experience::whereSlug($slug)->firstOrFail();

        $related = Experience::whereHas('tags', function ($q) use ($news) {
            return $q->whereIn('name', $news->tags->pluck('name'));
        })->where('id', '!=', $news->id)->take(5)->get();

        $inCategory = Experience::whereHas('categories', function ($q) use ($news) {
            return $q->whereIn('id', $news->categories->pluck('id'));
        })->where('id', '!=', $news->id)->take(4)->get();

        try {
            views($news)->record();
        } catch (\Exception $e) {
            report($e);
        }

        if ($news->options->get('show_toc')) {
            try {
                $tocs = ContentParser::parseTOC(
                    $news->content
                );
            } catch (\Exception $e) {
                report($e);
                $tocs = '';
            }
        }

        SEOConfigure::config($news);

        return view('news', [
            'news'       => $news,
            'tocs'       => $tocs ?? null,
            'related'    => $related,
            'inCategory' => $inCategory,
        ]);
    }

    public function category($slug)
    {
        $category = ExperienceCategory::whereSlug($slug)->firstOrFail();

        $news = Experience::byCategory($slug);
        SEOConfigure::config($category);

        return view('news-category', [
            'category' => $category,
            'news'     => $news,
        ]);
    }
}
