<?php

namespace App\Orchid\Screens\Consultant\Layouts;

use App\Model\Counselor;
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
            Input::make('consultant.customer_name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên khách hàng')),

            Input::make('consultant.customer_phone')
                ->type('text')
                ->title(__('Sdt khách hàng')),

            Input::make('consultant.customer_email')
                ->type('email')
                ->maxlength(100)
                ->title(__('Email khách hàng')),

            Input::make('consultant.customer_address')
                ->type('text')
                ->maxlength(100)
                ->title(__('Địa chỉ khách hàng')),

            TextArea::make('consultant.private_note')
                ->type('text')
                ->title(__('Ghi chú')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Relation::make('consultant.counselor_id')
                ->fromModel(Counselor::class, 'display_name')
                ->title(__('Nhân viên tư vấn')),

            ViewField::make('consultant.data')
                ->view('platform.layouts.table.schedule_at'),
        ];
    }
}
