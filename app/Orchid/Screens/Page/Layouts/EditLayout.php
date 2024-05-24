<?php

namespace App\Orchid\Screens\Page\Layouts;

use App\Model\Page;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Layout\TwoColumnsLayout;
use App\Orchid\Layout\ViewField;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('page.title')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tiêu đề'))
                ->help(__('Nhập vào tiêu đề')),

            OpenEditor::make('content')
                ->link(route('platform.pages.editor', [$this->query->get('page.id')]))
                ->canSee($this->query->get('page.id') != null),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Input::make('page.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),

            Select::make('page.status')
                ->options(Page::getStatus())
                ->title(__('Trạng thái'))
                ->hr(),

            Switcher::make('options.show_page_title')
                ->value(1)
                ->placeholder(__('Hiển thị tiêu đề')),

            Picture::make('options.title_image')
                ->title(__('Ảnh cover')),

            Select::make('options.style')
                ->options(['style_1' => 'Style 1', 'style_2' => 'Style 2'])
                ->title('Style')
        ];
    }
}
