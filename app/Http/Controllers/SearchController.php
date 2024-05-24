<?php

namespace App\Http\Controllers;

use App\Model\Experience;
use App\Model\News;
use App\Model\Product;
use App\Model\Service;
use App\Search\TranslateModelSearchAspect as SearchAspect;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = clean(rawurldecode($request->input('q') ?? ''));

        $title = 'Tìm kiếm';

        if ($query) {
            $title = sprintf('Kết quả tìm kiếm cho "%s"', clean($query));
        }

        return view('search', [
            'title' => $title,
            'results' => $query ? $this->search($query, $request->type) : null,
        ]);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function search($query, $type = '')
    {
        $search = new Search();

        if (!$type || $type === 'products') {
            $search->registerAspect(new SearchAspect(Product::class, ['title', 'slug']));
        }

        if (!$type || $type === 'services') {
            $search->registerAspect(new SearchAspect(Service::class, ['title', 'slug']));
        }

        if (!$type || $type === 'news') {
            $search->registerAspect(new SearchAspect(News::class, ['title', 'slug']));
        }

        if (!$type || $type === 'experience') {
            $search->registerAspect(new SearchAspect(Experience::class, ['title', 'slug']));
        }

        return $search->search($query);
    }
}
