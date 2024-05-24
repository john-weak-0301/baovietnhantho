<?php

namespace App\Orchid\Screens\FundCost\Layouts;

use App\Model\FundCost;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\LinkBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Orchid\Layout\TwoColumnsLayout;

class ListTable extends Table
{
    /**
     * @return void
     */
    public function prepare(Request $request, Builder $query)
    {
        $importFundId = (int) $request->route('id');
        return $query->where('imported_id', $importFundId);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('date', __('Ngày công bố giá'))
                ->width(150)
                ->align(Column::ALIGN_CENTER)
                ->displayUsing(function ($item) {
                    return $item->date;
                }),

            Column::make('value', __('Giá trị'))
                ->width(150),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->align(Column::ALIGN_CENTER)
                ->displayUsing(function ($item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
