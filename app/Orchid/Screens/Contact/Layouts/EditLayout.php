<?php

namespace App\Orchid\Screens\Contact\Layouts;

use App\Model\Counselor;
use App\Model\Province;
use App\Orchid\Fields\SelectArea;
use App\Orchid\Layout\TwoColumnsLayout;
use App\Orchid\Layout\ViewField;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('contact.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên khách hàng')),

            Input::make('contact.phone_number')
                ->type('text')
                ->title(__('Sdt khách hàng')),

            Input::make('contact.email')
                ->type('email')
                ->maxlength(100)
                ->title(__('Email khách hàng')),

            Input::make('contact.address')
                ->type('text')
                ->maxlength(100)
                ->title(__('Địa chỉ khách hàng')),

            Select::make('contact.province_code')
                ->options(Province::all()->pluck('name', 'code'))
                ->title(__('Tỉnh/TP')),

            TextArea::make('contact.message')
                ->type('text')
                ->title(__('Tin nhắn')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
        ];
    }
}
