<?php

namespace App\Orchid\Screens\Category;

use App\Model\Category;
use App\Model\User;
use App\Orchid\Requests\StoreCategoryRequest;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Requests\StorePageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

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
     * @param  Category  $category
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Category $category): array
    {
        $this->authorize(Category::PERMISSION_TOUCH);

        $this->exist = $category->exists;

        return ['category' => $category];
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
                ->canSee($this->exist && $user->can(Category::PERMISSION_DELETE)),

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
            Layouts\EditLayout::class,
        ];
    }

    /**
     * Handle the "save" command action.
     *
     * @param  Request  $request
     * @param  Category  $category
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated()['category'] ?? [];

        $category->fill([
            'name' => sanitize_text_field($validated['name'] ?? ''),
            'description' => wp_kses_post($validated['description'] ?? ''),
            'slug' => sanitize_text_field($validated['slug'] ?? ''),
        ]);

        $category ->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.categories.edit', $category);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Category  $category
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->checkPermission(Category::PERMISSION_DELETE);

        $category->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.categories');
    }
}
