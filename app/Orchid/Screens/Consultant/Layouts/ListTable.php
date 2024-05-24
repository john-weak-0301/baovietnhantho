<?php

namespace App\Orchid\Screens\Consultant\Layouts;

use App\Model\Consultant;
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

            Column::make('customer_name', __('Tên khách hàng'))
                ->linkTo(function (LinkBuilder $link, $consultant) {
                    $link->route('platform.consultants.edit', $consultant);
                }),

            Column::make('customer_phone', __('SĐT')),

            Column::make('customer_email', __('Email')),

            Column::make('customer_address', __('Địa chỉ')),

            Column::make('counselor_id', __('NV tư vấn'))
                ->displayUsing(function ($item) {
                    return optional($item->counselor)->display_name;
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Consultant $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
