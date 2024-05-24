<?php

namespace App\Orchid\Screens\Category\Layouts;

use App\Model\Category;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\LinkBuilder;
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
                ->displayUsing(function (Category $item) {
                    $prepend = $item->parent_id ? '<span class="span-submenu-tree"></span>' : '';

                    $prepend .= sprintf(
                        '<input class="form-control input-order" name="orders[%1$d]" type="number" value="%2$d">',
                        esc_attr($item->getKey()),
                        esc_attr($item->order ?: 0)
                    );

                    $link = sprintf(
                        '<a href="%2$s">%1$s</a>',
                        $item->name,
                        route('platform.categories.edit', $item)
                    );

                    return new HtmlString($prepend.$link);
                }),

            Column::make('slug', __('URL'))
                ->width(200)
                ->linkTo(function (LinkBuilder $linkBuilder, $item) {
                    $linkBuilder->route('news.category', $item->slug);
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Category $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
