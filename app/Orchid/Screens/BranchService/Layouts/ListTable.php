<?php

namespace App\Orchid\Screens\BranchService\Layouts;

use App\Model\BranchService;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\ID;

class ListTable extends Table
{
    /**
     * {@inheritdoc}
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('name', __('Tên'))
                ->linkTo(function (LinkBuilder $link, $service) {
                    $link->route('platform.branchs.services.edit', $service);
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (BranchService $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
