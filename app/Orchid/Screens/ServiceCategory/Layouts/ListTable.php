<?php

namespace App\Orchid\Screens\ServiceCategory\Layouts;

use App\Model\ServiceCategory;
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

            Column::make('icon', __('Icon'))
                ->width(70)
                ->displayUsing(function (ServiceCategory $serviceCategory, $icon) {
                    return view('platform.layouts.td.icon', compact('icon'));
                }),

            Column::make('name', __('Tên'))
                ->linkTo(function (LinkBuilder $link, $category) {
                    $link->route('platform.services_categories.edit', $category);
                }),

            Column::make('order', __('Thứ tự'))
                ->sortable()
                ->width(70),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (ServiceCategory $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
