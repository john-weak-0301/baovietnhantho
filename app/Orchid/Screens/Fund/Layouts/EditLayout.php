<?php

namespace App\Orchid\Screens\Fund\Layouts;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Quill;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('fund.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên'))
                ->help('Nhập tên quỹ liên kết đơn vị'),

            TextArea::make('fund.desc_target')
                ->title(__('Mục tiêu')),

            TextArea::make('fund.desc_profit')
                ->title(__('Lợi nhuận kỳ vọng')),

            TextArea::make('fund.risks_of_investing')
                ->title(__('Rủi ro đầu tư'))
                ->placeholder(__('Thấp/Trung bình/Cao')),

            Quill::make('fund.desc_invest')
                ->title(__('Danh mục đầu tư (% tài sản)')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Input::make('fund.order')
                ->type('number')
                ->max(255)
                ->title(__('Thứ tự'))
                ->placeholder(__('Nhập thứ tự hiển thị')),
        ];
    }
}
