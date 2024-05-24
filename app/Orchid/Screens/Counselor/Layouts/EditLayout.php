<?php

namespace App\Orchid\Screens\Counselor\Layouts;

use App\Model\Counselor;
use App\Model\Personality;
use App\Model\Province;
use App\Orchid\Fields\SelectArea;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use App\Orchid\Layout\TwoColumnsLayout;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            SelectArea::make('__area')
                ->title(__('Khu Vực')),

            Input::make('counselor.uid')
                ->maxlength(60)
                ->required()
                ->title(__('Mã nhân viên')),

            Input::make('counselor.company_name')
                ->maxlength(100)
                ->required()
                ->title(__('Công ty')),

            Input::make('counselor.last_name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Họ')),

            Input::make('counselor.first_name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên')),

            Input::make('counselor.display_name')
                ->type('text')
                ->maxlength(255)
                ->title(__('Tên hiển thị')),

            Input::make('counselor.year_of_birth')
                ->type('text')
                ->title(__('Năm sinh')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Picture::make('counselor.avatar')
                ->title(__('Ảnh')),

            Select::make('counselor.gender')
                ->options(Counselor::getGender())
                ->title(__('Giới tính')),

            Select::make('personality.')
                ->options(function () {
                    return Personality::getAllPersonalities();
                })
                ->multiple()
                ->title(__('Tính cách')),

            Select::make('counselor.rate_value')
                ->options(['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'])
                ->title(__('Đánh giá')),
        ];
    }
}
