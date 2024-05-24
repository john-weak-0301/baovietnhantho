<?php

namespace App\Orchid\Screens\Counselor\Layouts;

use App\Model\Counselor;
use App\Model\District;
use App\Model\Province;
use App\Orchid\Filters\AreaFilter;
use App\Orchid\Filters\CounselorsByCompanyFilter;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Illuminate\Support\HtmlString;

class ListTable extends Table
{
    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('uid', __('Mã NV'))
                ->width(110)
                ->sortable()
                ->filter()
                ->displayUsing(function ($item, $value) {
                    return new HtmlString("<strong>{$value}</strong>");
                })
                ->linkTo(function (LinkBuilder $linkBuilder, $item) {
                    $linkBuilder->route('platform.counselors.edit', $item->id);
                }),

            Column::make('display_name', __('Tên'))
                ->sortable()
                ->filter()
                ->displayUsing(function ($item) {
                    return new HtmlString(view('platform.layouts.td.image', compact('item')));
                }),

            Column::make('company_name', __('Cty'))
                ->width(150),

            Column::make('__khu_vuc__', __('Khu vực'))
                ->width(200)
                ->displayUsing(function (Counselor $counselor) {
                    return $counselor->province_id
                        ? rescue(function () use ($counselor) {
                            return sprintf(
                                '%s - %s',
                                Province::getByCode((string) $counselor->province_id)->getName(),
                                District::getByCode((string) $counselor->district_id)->getNameWithType()
                            );
                        })
                        : '';
                }),

            Column::make('gender', __('Giới tính'))
                ->width(75)
                ->displayUsing(function ($item) {
                    return Counselor::getGender()[$item->gender];
                }),

            Column::make('year_of_birth', __('Năm sinh'))
                ->sortable()
                ->width(75),

            Column::make('created_at', __('Ngày tạo'))
                ->sortable()
                ->filter('date')
                ->width(150)
                ->align(Column::ALIGN_RIGHT)
                ->displayUsing(function ($item) {
                    return format_date($item->created_at);
                }),
        ];
    }

    /**
     * @return array
     */
    public function filters()
    {
        return [
            new AreaFilter,
            new CounselorsByCompanyFilter,
        ];
    }
}
