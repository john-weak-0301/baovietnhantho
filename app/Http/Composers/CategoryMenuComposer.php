<?php

namespace App\Http\Composers;

use App\Model\Category;
use App\Model\ExperienceCategory;
use Illuminate\Support\Facades\Route;
use Lavary\Menu\Facade as Menu;
use Lavary\Menu\Builder;

class CategoryMenuComposer
{
    public function compose(): void
    {
        Menu::make('categoryMenu', function (Builder $menu) {
            $isNews = ! Route::is(['exps', 'exps.*', 'exp']);

            $query = $isNews ? Category::query() : ExperienceCategory::query();

            $categories = $query->take(100)->get();
            $menu->add(__('Tất cả'), $isNews ? route('news.home') : route('exps.home'));

            foreach ($categories as $category) {
                $menu->add(
                    $category->name,
                    route($isNews ? 'news.category' : 'exps.category', $category['slug'])
                );
            }
        });
    }
}
