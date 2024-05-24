<?php

namespace App\Orchid\Screens\Popup\Layouts;

use App\Model\Page;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Layout\TwoColumnsLayout;
use App\Orchid\Layout\ViewField;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Switcher;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('popup.title1')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tiêu đề 1'))
                ->help(__('Nhập vào tiêu đề')),

            Input::make('popup.title2')
                ->type('text')
                ->maxlength(255)
                ->title(__('Tiêu đề 2'))
                ->help(__('Nhập vào tiêu đề')),

            Quill::make('popup.description')
                ->title(__('Mô tả'))
                ->help(__('Nhập vào mô tả')),

            Input::make('popup.cta_text')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Nút chuyển link'))
                ->help(__('Tiêu đề của nút chuyển link')),

            Input::make('popup.cta_link')
                ->type('text')
                ->maxlength(255)
                ->title(__('Chuyển đến màn hình'))
                ->help(__('Đường dẫn chuyển hướng tới')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            // Select::make('popup.status')
            //     ->options(Page::getStatus())
            //     ->title(__('Trạng thái')),

            // Switcher::make('options.show_page_title')
            //     ->value(1)
            //     ->placeholder(__('Hiển thị tiêu đề'))
            //     ->hr(),
            Input::make('popup.order')
                ->title(__('Thứ tự hiển thị'))
                ->type('number')
                ->value(1)
                ->min(1)
                ->max(100)
                ->required(),

            Picture::make('popup.image')
                ->required()
                ->title(__('Ảnh cover')),

            CheckBox::make()
                ->name('popup.show_all')
                ->placeholder(__('Tất cả các trang'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_home_page')
                ->placeholder(__('Trang chủ'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_products')
                ->placeholder(__('Sản phẩm'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_posts')
                ->placeholder(__('Bài viết'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_pages')
                ->placeholder(__('Trang'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_expert')
                ->placeholder(__('Góc chuyên gia'))
                ->value(0),

            CheckBox::make()
                ->name('popup.show_service')
                ->placeholder(__('Tư vấn dịch vụ'))
                ->value(0),

            TextArea::make('popup.show_more_links')
                ->title(__('Tùy chỉnh đường dẫn'))
                ->type('text')
                ->help(__('Mỗi đường dẫn cách nhau bởi dấy phẩy ,')),

            Select::make('popup.layout')
                ->options(['left' => 'Trái', 'right' => 'Phải'])
                ->value('left')
                ->required()
                ->title('Vị trí hiển thị ảnh'),
        ];
    }
}
