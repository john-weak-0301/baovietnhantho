<?php

namespace App\Orchid\Screens\Branch\Layouts;

use App\Model\Branch;
use App\Model\Province;
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

            Column::make('name', __('Tên chi nhánh'))
                ->linkTo(function (LinkBuilder $link, Branch $branch) {
                    $link->route('platform.branchs.edit', $branch);
                }),

            Column::make('address', __('Địa chỉ')),

            Column::make('province_code', __('Tỉnh/Thành phố'))
                ->displayUsing(function ($item) {
                    return rescue(function () use ($item) {
                        return Province::getByCode($item->province_code)->name;
                    });
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Branch $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
