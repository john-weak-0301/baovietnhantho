<?php

namespace App\Orchid\Screens\Experience\Layouts;

use App\Model\ExperienceCategory;
use App\Orchid\Fields\OpenEditor;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Core\Elements\Fields\Tags;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('exp.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Tiêu đề'))
                ->help(__('Nhập vào tiêu đề')),

            Input::make('exp.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),

            Picture::make('exp.image')
                ->targetId()
                ->title(__('Ảnh')),

            Picture::make('exp.image_slider')
                ->targetUrl()
                ->title(__('Ảnh slider')),

            TextArea::make('exp.excerpt')
                ->rows(5)
                ->title(__('Đoạn trích')),

            OpenEditor::make('__content__')
                ->link(route('platform.exps.editor', [$this->query->get('exp.id')]))
                ->canSee($this->query->get('exp.id') !== null),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Select::make('exp.status')
                ->title(__('Trạng thái'))
                ->hr()
                ->options([
                    'pending' => 'Pending',
                    'publish' => 'Publish',
                ]),

            DateTimer::make('exp.published_date')
                ->title(__('Ngày đăng'))
                ->enableTime(),

            Select::make('categories.')
                ->multiple()
                ->options(ExperienceCategory::pluck('name', 'id'))
                ->title(__('Danh mục')),

            Tags::make('exp.tags')
                ->title('Tags')
                ->hr(),

            CheckBox::make()
                ->name('exp.is_featured')
                ->value(0)
                ->placeholder(__('Đánh dấu bài viết nổi bật')),

            CheckBox::make()
                ->name('exp.in_slider')
                ->value(0)
                ->placeholder(__('Hiển thị trong slider')),

            CheckBox::make()
                ->name('exp.options.show_toc')
                ->value(0)
                ->placeholder(__('Hiển thị Table of Contents?')),
        ];
    }
}
