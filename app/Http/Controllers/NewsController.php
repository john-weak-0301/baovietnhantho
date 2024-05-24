<?php

namespace App\Http\Controllers;

use App\Model\News;
use App\Model\Category;
use App\SEO\SEOConfigure;
use App\Utils\ContentParser;

class NewsController extends Controller
{
    public function home()
    {
        $news = News::query()
            ->where('status', 'publish')
            ->latest('published_date')
            ->orderByDesc('order')
            ->paginate(5);

        return view('news-category', [
            'news'  => $news,
            'title' => __('Tin tức'),
        ]);
    }

    public function show($slug)
    {
        $news = News::whereSlug($slug)->firstOrFail();

        $related = News::whereHas('tags', function ($q) use ($news) {
            return $q->whereIn('name', $news->tags->pluck('name'));
        })->where('id', '!=', $news->id)->take(5)->get();

        $inCategory = News::whereHas('categories', function ($q) use ($news) {
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
        $category = Category::whereSlug($slug)->firstOrFail();

        $news = News::byCategory($slug);
        SEOConfigure::config($category);

        return view('news-category', [
            'category' => $category,
            'news'     => $news,
        ]);
    }
}
