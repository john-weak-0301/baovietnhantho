<?php

namespace Core\Dashboard\Controllers;

use App\Model\Category;
use App\Model\Experience;
use App\Model\ExperienceCategory;
use App\Model\News;
use App\Model\Page;
use App\Model\Product;
use App\Model\ProductCategory;
use App\Model\Service;
use App\Model\ServiceCategory;
use Core\Support\ModelWithLatestSearchAspect;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;

class AjaxLinksController
{
    public function links()
    {
        return [
            'data' => $this->getTypes(),
        ];
    }

    public function searchLinks(Request $request)
    {
        $query = $request->input('query');
        $rawTypes = $request->input('types', 'all_type');

        $searchAspectCallbacks = [
            'pages' => function ($filterCategory = null) use ($query) {
                return new ModelWithLatestSearchAspect(
                    Page::class,
                    $this->queryByTitleAndSlug($query, $filterCategory)
                );
            },
            'news' => function ($filterCategory = null) use ($query) {
                return new ModelWithLatestSearchAspect(
                    News::class,
                    $this->queryByTitleAndSlug($query, $filterCategory)
                );
            },
            'exps' => function ($filterCategory = null) use ($query) {
                return new ModelWithLatestSearchAspect(
                    Experience::class,
                    $this->queryByTitleAndSlug($query, $filterCategory)
                );
            },
            'products' => function ($filterCategory = null) use ($query) {
                return new ModelWithLatestSearchAspect(
                    Product::class,
                    $this->queryByTitleAndSlug($query, $filterCategory)
                );
            },
            'services' => function ($filterCategory = null) use ($query) {
                return new ModelWithLatestSearchAspect(
                    Service::class,
                    $this->queryByTitleAndSlug($query, $filterCategory)
                );
            },
        ];

        $types = $this->parseTypes($rawTypes);
        $isAllType = (is_array($rawTypes) && $rawTypes[0] === 'all_type') || $rawTypes === 'all';

        $search = new Search();
        foreach ($searchAspectCallbacks as $code => $searchAspectCallback) {
            if ($isAllType || array_key_exists($code, $types)) {
                $filterCategory = $types[$code] ?? null;

                $search->registerAspect($searchAspectCallback($filterCategory));
            }
        }

        $results = $search
            ->limitAspectResults(25)
            ->search((string) $query);

        return [
            'data' => $results->map(function ($data) {
                unset($data->searchable);

                return $data;
            })->groupByType(),
        ];
    }

    private function queryByTitleAndSlug($query, $cateFilter = null)
    {
        return function (ModelWithLatestSearchAspect $aspect) use ($cateFilter, $query) {
            if (!$query) {
                $aspect->latest('created_at');
            } else {
                $aspect->addSearchableAttribute('title')
                    ->addSearchableAttribute('slug');
            }

            $aspect->select(['id', 'title', 'slug']);

            if (is_array($cateFilter) && count($cateFilter)) {
                switch ($aspect->getType()) {
                    case 'exps';
                    case 'news';
                        $aspect->whereHas('categories', function ($query) use ($cateFilter) {
                            $query->whereIn('categories.id', $cateFilter);
                        });
                        break;

                    case 'products';
                        $aspect->whereHas('categories', function ($query) use ($cateFilter) {
                            $query->whereIn('product_categories.id', $cateFilter);
                        });
                        break;

                    case 'services';
                        $aspect->whereHas('categories', function ($query) use ($cateFilter) {
                            $query->whereIn('service_categories.id', $cateFilter);
                        });
                        break;
                }
            }
        };
    }

    private function parseTypes($types)
    {
        $types = is_array($types)
            ? array_unique($types)
            : explode(',', $types);

        $parsed = [];

        foreach ($types as $_type) {
            if (preg_match('/^([a-z_]+):(\d+)$/', $_type, $matches)) {
                if (isset($parsed[$matches[1]])) {
                    $parsed[$matches[1]] = array_unique(array_merge($parsed[$matches[1]], [(int) $matches[2]]));
                } else {
                    $parsed[$matches[1]] = [(int) $matches[2]];
                }
            } else {
                $parsed[$_type] = [];
            }
        }

        return $parsed;
    }

    private function getTypes()
    {
        return [
            [
                'type' => 'pages',
                'name' => 'Pages',
                'categories' => null,
            ],
            [
                'type' => 'news',
                'name' => 'Tin tức',
                'categories' => $this->resolveCategories('news'),
            ],
            [
                'type' => 'exps',
                'name' => 'Góc chuyên gia',
                'categories' => $this->resolveCategories('exps'),
            ],
            [
                'type' => 'products',
                'name' => 'Sản phẩm',
                'categories' => $this->resolveCategories('products'),
            ],
            [
                'type' => 'services',
                'name' => 'Dịch vụ khách hàng',
                'categories' => $this->resolveCategories('services'),
            ],
        ];
    }

    private function resolveCategories(string $type)
    {
        switch ($type) {
            case 'news':
                return Category::query()
                    ->latest('created_at')
                    ->take(25)
                    ->get(['id', 'name', 'slug']);

            case 'exps':
                return ExperienceCategory::query()
                    ->latest('created_at')
                    ->take(25)
                    ->get(['id', 'name', 'slug']);

            case 'services':
                return ServiceCategory::query()
                    ->latest('created_at')
                    ->take(25)
                    ->get(['id', 'name', 'slug']);

            case 'products':
                return ProductCategory::query()
                    ->whereIsRoot()
                    ->orderByDesc('order')
                    ->take(25)
                    ->get(['id', 'name', 'slug']);
        }

        return null;
    }
}
