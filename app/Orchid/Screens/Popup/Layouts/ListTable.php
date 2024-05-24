<?php

namespace App\Orchid\Screens\Popup\Layouts;

use App\Model\Page;
use App\Model\Popup;
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

            Column::make('title1', __('Tiêu đề 1'))
                ->width(200)
                ->linkTo(function (LinkBuilder $link, Popup $popup) {
                    $link->link(route('platform.popups.edit', $popup->id));
                }),

            Column::make('title2', __('Tiêu đề 2'))
                ->width(200),

            Column::make('order', __('Thứ tự'))
                ->width(80),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Popup $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
