<?php

namespace App\Orchid\Screens\Product\Layouts;

use App\Model\AdditionProduct;
use App\Model\ProductCategory;
use App\Orchid\Fields\OpenEditor;
use App\Orchid\Layout\TwoColumnsLayout;
use App\Orchid\Layout\ViewField;
use Core\Elements\Fields\Tags;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        $product = request()->route('productId');

        $isAddition = $product
            ? $product->type === 'addition'
            : request()->get('type') === 'addition';

        return [
            Input::make('type')
                ->type('hidden')
                ->value($isAddition ? 'addition' : ''),

            Relation::make('product.categories.')
                ->fromModel(ProductCategory::class, 'name', 'id')
                ->title(__('Danh mục SP'))
                ->multiple()
                ->canSee(!$isAddition),

            Input::make('product.title')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Tiêu đề'))
                ->help(__('Nhập vào tiêu đề')),

            OpenEditor::make('content')
                ->link(route('platform.products.editor', [$this->query->get('product.id')]))
                ->canSee((bool) $this->query->get('product.id')),

            ViewField::make('product.additions')
                ->title(__('Sản phẩm liên quan'))
                ->title(__('Sản phẩm bổ trợ'))
                ->canSee(!$isAddition)
                ->view('platform.fields.addition-products'),

            ViewField::make('product.related')
                ->title(__('Sản phẩm liên quan'))
                ->view('platform.fields.related-products'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        return [
            Input::make('product.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),

            Picture::make('product.image')
                ->title(__('Ảnh')),

            TextArea::make('product.excerpt')
                ->rows('5')
                ->title(__('Đoạn trích')),

            CheckBox::make('product.is_featured')
                ->value(1)
                ->placeholder(__('Đánh dấu sản phẩm phổ biến')),

            Tags::make('tags')
                ->title('Tags')
                ->help('Keywords'),

            Input::make('product.order')
                ->type('number')
                ->title(__('Thứ tự hiển thị'))
                ->help(__('Lớn ưu sẽ ưu tiên hơn')),
        ];
    }
}
