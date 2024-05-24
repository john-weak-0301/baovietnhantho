<?php

namespace App\Orchid\Screens\Service\Layouts;

use App\Model\Service;
use Core\Elements\Table\Column;
use Core\Elements\Table\ID;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;

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
                ->linkTo(function (LinkBuilder $link, $service) {
                    $link->route('platform.services.edit', $service);
                }),

            Column::make('category', __('Danh mục'))
                ->width(200)
                ->displayUsing(function ($item) {
                    return ($item->categories->pluck('name')->join(', '));
                }),

            Column::make('slug', __('Đường dẫn tĩnh'))
                ->width(250)
                ->linkTo(function (LinkBuilder $link, $service) {
                    $link->route('service', $service->slug)->openNewTab();
                }),

            Column::make('order', __('Thứ tự'))
                ->width(70)
                ->sortable(),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Service $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
