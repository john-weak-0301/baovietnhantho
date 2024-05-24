<?php

namespace App\Orchid\Screens\ExperienceCategory\Layouts;

use App\Model\ExperienceCategory;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\ID;
use Illuminate\Support\HtmlString;

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
                ->displayUsing(function (ExperienceCategory $item) {
                    $prepend = $item->parent_id ? '<span class="span-submenu-tree"></span>' : '';

                    $prepend .= sprintf(
                        '<input class="form-control input-order" name="orders[%1$d]" type="number" value="%2$d">',
                        esc_attr($item->getKey()),
                        esc_attr($item->order ?: 0)
                    );

                    $link = sprintf(
                        '<a href="%2$s">%1$s</a>',
                        $item->name,
                        route('platform.exps_categories.edit', $item)
                    );

                    return new HtmlString($prepend.$link);
                }),

            Column::make('slug', __('URL'))
                ->width(200)
                ->linkTo(function (LinkBuilder $linkBuilder, ExperienceCategory $item) {
                    $linkBuilder->route('exps.category', $item->slug);
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (ExperienceCategory $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
