<?php

namespace App\Orchid\Screens\News\Layouts;

use App\Model\Category;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Layout\TwoColumnsLayout;
use Core\Elements\Fields\Tags;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
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
            Input::make('news.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Tiêu đề'))
                ->help(__('Nhập vào tiêu đề')),

            Input::make('news.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),

            Picture::make('news.image')
                ->targetId()
                ->title(__('Ảnh')),

            Picture::make('news.image_slider')
                ->targetUrl()
                ->title(__('Ảnh slider')),

            TextArea::make('news.excerpt')
                ->rows(5)
                ->title(__('Đoạn trích')),

            OpenEditor::make('__content__')
                ->link(route('platform.news.editor', [$this->query->get('news.id')]))
                ->canSee($this->query->get('news.id') !== null),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Select::make('news.status')
                ->title(__('Trạng thái'))
                ->hr()
                ->options([
                    'pending' => 'Pending',
                    'publish' => 'Publish',
                ]),

            DateTimer::make('news.published_date')
                ->title(__('Ngày đăng'))
                ->enableTime(),

            Select::make('category.')
                ->options(Category::pluck('name', 'id'))
                ->multiple()
                ->title(__('Danh mục')),

            Tags::make('news.tags')
                ->title('Tags')
                ->hr(),

            CheckBox::make()
                ->name('news.is_featured')
                ->value(0)
                ->placeholder(__('Đánh dấu bài viết nổi bật')),

            CheckBox::make()
                ->name('news.in_slider')
                ->value(0)
                ->placeholder(__('Hiển thị trong slider')),

            CheckBox::make()
                ->name('news.options.show_toc')
                ->value(0)
                ->placeholder(__('Hiển thị Table of Contents?')),
        ];
    }
}
