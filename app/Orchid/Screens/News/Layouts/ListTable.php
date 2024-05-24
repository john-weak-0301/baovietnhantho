<?php

namespace App\Orchid\Screens\News\Layouts;

use App\Model\News;
use App\Orchid\Filters\NewsCategoryFilter;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\ID;

class ListTable extends Table
{
    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('title', __('Tiêu đề'))
                ->sortable()
                ->linkTo(function (LinkBuilder $link, News $news) {
                    $link->link($news->url->edit);
                }),

            Column::make('category', __('Danh mục'))
                ->displayUsing(function ($item) {
                    return $item->categories->pluck('name')->join(', ');
                }),

            Column::make('slug', __('Đường dẫn tĩnh'))
                ->linkTo(function (LinkBuilder $link, News $news) {
                    $link->link($news->url->link)->openNewTab();
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (News $item) {
                    return format_date($item->created_at);
                }),
        ];
    }

    /**
     * @return array
     */
    public function filters()
    {
        return [
            new NewsCategoryFilter(),
        ];
    }
}
