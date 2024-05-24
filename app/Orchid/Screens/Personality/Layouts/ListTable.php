<?php

namespace App\Orchid\Screens\Personality\Layouts;

use App\Model\Personality;
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
                ->linkTo(function (LinkBuilder $link, $personality) {
                    $link->route('platform.personalities.edit', $personality);
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Personality $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}

