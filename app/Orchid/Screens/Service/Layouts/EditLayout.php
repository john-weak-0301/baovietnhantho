<?php

namespace App\Orchid\Screens\Service\Layouts;

use App\Model\Service;
use App\Model\ServiceCategory;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Layout\ViewField;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
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
            Input::make('service.title')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tiêu đề'))
                ->help(__('Nhập vào tiêu đề')),

            Input::make('service.order')
                ->type('number')
                ->title(__('Thứ tự')),

            OpenEditor::make('content')
                ->link(route('platform.services.editor', [$this->query->get('service.id')]))
                ->canSee($this->query->get('service.id') != null),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Picture::make('service.image')
                ->title(__('Ảnh')),

            Input::make('service.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),

            Select::make('category')
                ->options(ServiceCategory::getAllCategories())
                ->title(__('Danh mục'))
                ->help(__('Lựa chọn danh mục')),

            Select::make('service.status')
                ->options(Service::getStatus())
                ->title(__('Trạng thái')),
        ];
    }
}
