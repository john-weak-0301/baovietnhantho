<?php

namespace App\Orchid\Screens\Contact\Layouts;

use App\Model\Consultant;
use App\Model\Province;
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

            Column::make('name', __('Khách hàng'))
                ->linkTo(function (LinkBuilder $link, $contact) {
                    $link->route('platform.contacts.show', $contact);
                }),

            Column::make('phone_number', __('SĐT')),

            Column::make('email', __('Email')),

            Column::make('address', __('Địa chỉ'))
                ->displayUsing(function ($item) {
                    $province = $item->province_code ? rescue(function () use ($item) {
                        return Province::getByCode($item->province_code)->getName();
                    }) : '';

                    return implode(', ', array_filter([$item->address, $province]));
                }),

            Column::make('created_at', __('Ngày gửi'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function ($item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
