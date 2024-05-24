<?php

namespace App\Orchid\Screens\Fund\Layouts;

use App\Model\FundImport;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\LinkBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class ImportedListTable extends Table
{
    /**
     * @return void
     */
    public function prepare(Request $request, Builder $query)
    {
        $fundId = (int) $request->route('id');
        return $query->where('quy_lkdv_id', $fundId);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        $statuses = FundImport::STATUSES;
        return [
            ID::make(),

            Column::make('', 'Đường dẫn')
                ->width(150)
                ->linkTo(function (LinkBuilder $link, $fundImport) {
                    $link->route('platform.import_funds', $fundImport);
                })
                ->displayUsing(function () {
                    return 'Xem chi tiết';
                }),

            Column::make('status', __('Trạng thái'))
                ->width(150)
                ->displayUsing(function (FundImport $fundImport) use ($statuses) {
                    return $statuses["{$fundImport->status}"];
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->align(Column::ALIGN_RIGHT)
                ->displayUsing(function ($item) {
                    return format_date($item->created_at);
                }),

            Column::make('actions', __('Hành động'))
                ->width(150)
                ->align(Column::ALIGN_CENTER)
                ->displayUsing(function ($item) {
                    return new HtmlString(view('components.fund_cost_actions', ['id' => $item->id])->render());
                }),
        ];
    }
}
