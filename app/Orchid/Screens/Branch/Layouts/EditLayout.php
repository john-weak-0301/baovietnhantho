<?php

namespace App\Orchid\Screens\Branch\Layouts;

use App\Model\Branch;
use App\Model\BranchService;
use App\Model\Province;
use App\Orchid\Layout\TwoColumnsLayout;
use App\Orchid\Layout\ViewField;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Map;
use Orchid\Screen\Fields\Select;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        $province = Province::all();

        $province = $province->mapWithKeys(function ($item) {
            return [$item->code => $item->name];
        })->toArray();

        return [
            Select::make('branch.province_code')
                ->options($province)
                ->title(__('Tỉnh/Thành phố')),

            Input::make('branch.name')
                ->type('text')
                ->required()
                ->title(__('Tên chi nhánh')),

            Input::make('branch.address')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Địa chỉ')),

            Map::make('branch.location')
                ->required()
                ->zoom(15)
                ->value([
                    'lat' => '21.023999',
                    'lng' => '105.842289',
                ])
                ->title(__('Vị trí trên bản đồ')),

            ViewField::make('branch.working_days')
                ->view('platform.layouts.table.workingday'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Input::make('branch.phone_number')
                ->type('text')
                ->title(__('Điện thoại')),

            Input::make('branch.fax_number')
                ->type('text')
                ->title(__('Fax')),

            Input::make('branch.email')
                ->type('email')
                ->value('baovietnhantho@baoviet.com.vn')
                ->max(255)
                ->required()
                ->title('Email'),

            Select::make('branch.type')
                ->options([
                    Branch::HEADQUARTERS      => __('Trụ sở chính'),
                    Branch::TRANSACTION_POINT => __('Điểm giao dịch'),
                ])
                ->title(__('Trụ sở')),

            Select::make('service.')
                ->options(BranchService::getAllServices())
                ->multiple()
                ->title(__('Dịch vụ')),

        ];
    }
}
