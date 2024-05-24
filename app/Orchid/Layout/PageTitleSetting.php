<?php

namespace App\Orchid\Layout;

use Illuminate\Http\Request;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Core\Database\Helpers\OptionsAttributes;

class PageTitleSetting extends Rows
{
    public function fields(): array
    {
        return [
            CheckBox::make('options.show_page_title')
                ->placeholder(__('Hiển thị tiêu đề')),

            Field::group([
                Picture::make('options.page_title_image')
                    ->title(__('Ảnh cover')),

                Select::make('options.page_title_style')
                    ->options(['style_1' => 'Style 1', 'style_2' => 'Style 2'])
                    ->title('Style'),
            ]),
        ];
    }

    public function handle(Request $request, OptionsAttributes $options)
    {
        $options->show_page_title  = null === $request->input('options.show_page_title') ? '0' : '1';
        $options->page_title_style = clean($request->input('options.page_title_style'));
        $options->page_title_image = clean($request->input('options.page_title_image'));

        return $options;
    }
}
