<?php

namespace App\Orchid\Screens\ProductCategory\Layouts;

use App\Model\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use App\Orchid\Layout\TwoColumnsLayout;
use Orchid\Screen\Fields\TinyMCE;

class EditLayout extends TwoColumnsLayout
{
    /**
     * {@inheritdoc}
     */
    public function mainFields(): array
    {
        return [
            Input::make('category.name')
                ->type('text')
                ->maxlength(255)
                ->required()
                ->title(__('Tên'))
                ->help(__('Nhập tên danh mục')),

            TextArea::make('category.subtitle')
                ->rows(5)
                ->title(__('Subtitle')),

            TinyMCE::make('category.description')
                ->title(__('Mô tả')),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sidebarFields(): array
    {
        /* @var ProductCategory $editing */
        $editing = request()->route()->parameter('productCategoryId');

        if ($editing && $editing->isRoot() && $editing->children()->count() > 0) {
            $options = [];
        } else {
            $options = $this->getOptions((bool) $editing, $editing ? $editing->getKey() : null);
        }

        return [
            Select::make('category.parent_id')
                ->title(__('Parent'))
                ->disabled(!$options)
                ->options($options),

            Input::make('category.order')
                ->type('number')
                ->title(__('Thứ tự sắp xếp'))
                ->help('Số lớn hơn sẽ ưu tiên lên trước'),

            Input::make('category.slug')
                ->type('text')
                ->max(255)
                ->title(__('Đường dẫn tĩnh'))
                ->placeholder(__('Nhập url duy nhất')),
        ];
    }

    /**
     * //
     *
     * @param  bool  $rootOnly
     * @param  array|int|null  $excepts
     * @return array
     */
    public function getOptions($rootOnly = true, $excepts = null): array
    {
        $options = ['' => '___'];

        if ($rootOnly) {
            $items = ProductCategory::query()
                ->when($excepts, function (Builder $builder) use ($excepts) {
                    $builder->whereNotIn('id', Arr::wrap($excepts));
                })
                ->whereIsRoot()
                ->take(100)
                ->get(['id', 'name']);

            foreach ($items as $item) {
                $options[$item->getKey()] = new HtmlString(e($item->name ?? ''));
            }
        } else {
            $items = ProductCategory::whereIsRoot()->take(100)->get()->toTree();

            $traverse = function ($categories, $prefix = '') use (&$traverse, &$options) {
                foreach ($categories as $category) {
                    $options[$category->getKey()] = new HtmlString($prefix.' '.e($category->name) ?? '');

                    $traverse($category->children, $prefix.'&nbsp;&nbsp;&nbsp;&nbsp;');
                }
            };

            $traverse($items);
        }

        return $options;
    }
}
