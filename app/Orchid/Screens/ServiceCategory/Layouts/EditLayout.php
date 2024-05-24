<?php

namespace App\Orchid\Screens\ServiceCategory\Layouts;

use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
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
            Input::make('category.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên')),

            TextArea::make('category.description')
                ->title(__('Mô tả'))
                ->rows(5),
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

            CheckBox::make('category.show_in_menu')
                ->value(1)
                ->placeholder(__('Hiển thị trên menu')),

            Picture::make('category.icon')
                ->title('Ảnh Icon')
                ->targetUrl(),

            Input::make('category.order')
                ->type('number')
                ->title(__('Thứ tự sắp xếp'))
                ->help('Số lớn hơn sẽ ưu tiên lên trước'),
        ];
    }
}
