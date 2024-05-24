<?php

namespace App\Http\Controllers;

use App\Model\Page;
use App\SEO\SEOConfigure;
use App\Utils\ContentParser;

class PageController extends Controller
{
    public function home()
    {
        $home = Page::firstOrCreate([
            'type'  => Page::TYPE_HOME,
            'title' => 'Index',
        ]);

        $sliders = collect($home->options->get('sliders'))->reject(function ($items) {
            return empty($items['title']) && empty($items['description']);
        });

        SEOConfigure::config($home);

        return view('home', [
            'page'    => $home,
            'sliders' => $sliders,
        ]);
    }

    public function show(Page $page)
    {
        $anchors = ContentParser::parseAnchors($page->content);

        return view('page', [
            'page'    => $page,
            'anchors' => $anchors,
        ]);
    }
}
