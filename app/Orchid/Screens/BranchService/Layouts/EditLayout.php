<?php

namespace App\Orchid\Screens\BranchService\Layouts;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('service.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên'))
                ->help(__('Nhập tên dịch vụ')),

            TextArea::make('service.description')
                ->title(__('Mô tả')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [];
    }
}
