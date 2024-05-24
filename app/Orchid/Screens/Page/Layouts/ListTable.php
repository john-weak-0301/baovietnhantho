<?php

namespace App\Orchid\Screens\Page\Layouts;

use App\Model\Page;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\ID;

class ListTable extends Table
{
    /**
     * {@inheritdoc}
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('title', __('Tiêu đề'))
                ->linkTo(function (LinkBuilder $link, Page $page) {
                    $link->link($page->url->edit);
                }),

            Column::make('slug', __('Đường dẫn tĩnh'))
                ->width(250)
                ->linkTo(function (LinkBuilder $link, Page $page) {
                    $link->link($page->url->link)->openNewTab();
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Page $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
