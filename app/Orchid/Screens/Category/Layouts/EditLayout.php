<?php

namespace App\Orchid\Screens\Category\Layouts;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\TinyMCE;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('category.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên'))
                ->help('Nhập tên danh mục'),

            TextArea::make('category.description')
                ->title(__('Mô tả')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Input::make('category.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),
        ];
    }
}
