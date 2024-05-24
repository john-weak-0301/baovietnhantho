<?php

namespace App\Orchid\Screens\Fund\Layouts;

use App\Model\Fund;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\LinkBuilder;
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

            Column::make('name', __('Tiêu đề'))
                ->width(150)
                ->linkTo(function (LinkBuilder $link, Fund $fund) {
                    $link->route('platform.funds.edit', $fund);
                }),

            Column::make('desc_target', __('Mục tiêu'))
                ->width(250),

            Column::make('risks_of_investing', __('Rủi ro'))
                ->width(250),

            Column::make('desc_profit', __('Lợi nhuận'))
                ->width(250),

            Column::make('desc_invest', __('Danh mục đầu tư'))
                ->width(150)
                ->displayUsing(function (Fund $fund) {
                    return new HtmlString($fund->desc_invest);
                }),

            Column::make('order', __('Thứ tự'))
                ->width(80)
                ->sortable(),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Fund $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
