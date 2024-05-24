<?php

namespace App\Orchid\Screens\ProductCategory;

use App\Model\ProductCategory;
use App\Model\User;
use App\Orchid\Layout\PageTitleSetting;
use App\Orchid\Requests\StoreProductCategoryRequest;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
     * Query data.
     *
     * @param  Request  $request
     * @param  ProductCategory  $category
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, ProductCategory $category): array
    {
        $this->authorize(ProductCategory::PERMISSION_TOUCH);

        $this->exist = $category->exists;

        return [
            'category' => $category,
            'options'  => $category->getOptions(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        /* @var $user User */
        $user = $this->request->user();

        return [
            $this->addLink(__('Thêm'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(ProductCategory::PERMISSION_DELETE)),

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
        return [
            Layout::tabs([
                __('Thông tin')     => Layouts\EditLayout::class,
                __('Tiêu đề trang') => PageTitleSetting::class,
            ]),
        ];
    }

    /**
     * Handle the "save" command action.
     *
     * @param  Request  $request
     * @param  ProductCategory  $category
     * @return RedirectResponse
     */
    public function save(StoreProductCategoryRequest $request, ProductCategory $category): RedirectResponse
    {
        $attributes = $request->validated()['category'] ?? [];

        $category->name        = clean($attributes['name'] ?? '');
        $category->slug        = clean($attributes['slug'] ?? '');
        $category->subtitle    = clean($attributes['subtitle'] ?? '');
        $category->description = clean($attributes['description'] ?? '', true);
        $category->order       = clean($attributes['order'] ?? 0);

        (new PageTitleSetting)->handle($request, $category->options);

        try {
            if (empty($attributes['parent_id'])) {
                $category->saveAsRoot();
            } else {
                $category->parent_id = (int) $attributes['parent_id'];
                $category->saveOrFail();
            }
        } catch (\LogicException $e) {
            ProductCategory::fixTree();
        }

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.products.categories.edit', $category);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  ProductCategory  $category
     * @return RedirectResponse
     */
    public function destroy(ProductCategory $category): RedirectResponse
    {
        $this->checkPermission(ProductCategory::PERMISSION_DELETE);

        $category->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.products.categories');
    }
}
