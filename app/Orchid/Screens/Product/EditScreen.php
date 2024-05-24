<?php

namespace App\Orchid\Screens\Product;

use App\Dashboard\Layouts\SEOLayout;
use App\Model\Product;
use App\Orchid\Layout\PageTitleSetting;
use App\Orchid\Requests\StoreProductRequest;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Layout;

class EditScreen extends AbstractScreen
{
    /**
     * Indicator the model's exists or not.
     *
     * @var bool
     */
    protected $exist = false;

    /**
     * @var Product
     */
    protected $product;

    /**
     * @var string
     */
    protected $productType;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Sản phẩm';

        if (request()->has('type') && request()->get('type') === 'addition') {
            $this->productType = 'addition';
        }
    }

    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  Product  $product
     * @return array
     */
    public function query(Request $request, Product $product): array
    {
        $this->authorize(Product::PERMISSION_TOUCH);

        $this->product = $product;
        $this->exist   = $product->exists;

        return [
            'product'  => $product,
            'options'  => $product->getOptions(),
            'seotools' => $product->getOptions()->get('seotools'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        $user = $this->request->user();

        return [
            $this->addLink(__('Tạo'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            $this->addLink(__('Chi tiết'))
                ->icon('icon-doc')
                ->link($this->product->url)
                ->canSee($this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Product::PERMISSION_DELETE)),

            $this->addLink(__('Lưu'))
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->exist),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        $collapses = collect(config('press.compare_attributes'))
            ->map(function ($name, $key) {
                return Layout::collapse([
                    Quill::make('product.compare_attributes.'.$key)->rows(5),
                ])->label($name);
            })->values();

        return [
            Layout::tabs([
                __('Thông tin')     => Layouts\EditLayout::class,
                __('Tiêu đề trang') => PageTitleSetting::class,
                __('So sánh')       => Layout::blank($collapses->all()),
                __('SEO')           => SEOLayout::class,
            ]),

            Layout::rows([
                Input::make('type')
                    ->type('hidden')
                    ->value($this->exist ? $this->product->type : request()->get('type')),
            ]),
        ];
    }

    public function save(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated()['product'] ?? [];

        $product->fill([
            'title'       => clean($validated['title'] ?? ''),
            'slug'        => clean($validated['slug'] ?? ''),
            'excerpt'     => clean($validated['excerpt'] ?? '', true),
            'image'       => clean($validated['image'] ?? ''),
            'order'       => clean($validated['order'] ?? 0),
            'is_featured' => $request->input('product.is_featured') === null ? '0' : '1',
            'status'      => 'publish',
        ]);

        if (!$product->exists) {
            $product->type = $this->productType;
        }

        $product->options->set(
            'seotools',
            SEOLayout::values($request)
        );

        (new PageTitleSetting)->handle($request, $product->options);

        if ($compare_attributes = $request->input('product.compare_attributes')) {
            $product->compare_attributes = map_deep((array) $compare_attributes, 'wp_kses_post');
        } else {
            $product->compare_attributes = null;
        }

        $product->saveOrFail();

        $product->categories()->sync((array) ($validated['categories'] ?? []));
        $product->related()->sync((array) ($validated['related'] ?? []));

        if ($product->type === null) {
            $product->additions()->sync((array) ($validated['additions'] ?? []));
        }

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.products.edit', $product);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->checkPermission(Product::PERMISSION_DELETE);

        $product->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.products');
    }
}
