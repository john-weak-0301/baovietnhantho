<?php

namespace App\Orchid\Screens\Product\Layouts;

use App\Model\Product;
use Core\Elements\Table\ID;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;
use Core\Elements\Table\LinkBuilder;

class ListTable extends Table
{
    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('title', __('Tiêu đề'))
                ->linkTo(function (LinkBuilder $link, Product $product) {
                    $link->link($product->url->edit);
                }),

            Column::make('category', __('Danh mục sản phẩm'))
                ->width(150)
                ->canSee(function () {
                    return !request()->is('*/addition-products');
                })
                ->displayUsing(function (Product $product, $category) {
                    return $category ? optional($category)->name : '-';
                }),

            Column::make('slug', __('Đường dẫn tĩnh'))
                ->width(350)
                ->linkTo(function (LinkBuilder $link, $product) {
                    $link->link($product->url->link())->openNewTab();
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width(150)
                ->filter('date')
                ->align(Column::ALIGN_RIGHT)
                ->sortable()
                ->displayUsing(function (Product $item) {
                    return format_date($item->created_at);
                }),
        ];
    }
}
