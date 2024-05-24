<?php

namespace App\Orchid\Screens\Page\Layouts;

use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Picture;

class HomeSliderForm extends Rows
{
    /**
     * @var int
     */
    private $index;

    public function __construct(int $index = 0)
    {
        $this->index = $index;
    }

    public function fields(): array
    {
        return [
            Input::make('sliders.'.$this->index.'.title')
                 ->type('text')
                 ->maxlength(255)
                 ->title(__('Tiêu đề'))
                 ->help(__('Nhập vào tiêu đề')),

            TextArea::make('sliders.'.$this->index.'.description')
                    ->title(__('Mô tả')),

            Picture::make('sliders.'.$this->index.'.background')
                   ->title(__('Ảnh')),

            Input::make('sliders.'.$this->index.'.link')
                 ->type('url')
                 ->maxlength(255)
                 ->title(__('Link')),

            Input::make('sliders.'.$this->index.'.text_link')
                 ->maxlength(255)
                 ->value(__('Tìm hiểu'))
                 ->title(__('Text link')),
        ];
    }
}
