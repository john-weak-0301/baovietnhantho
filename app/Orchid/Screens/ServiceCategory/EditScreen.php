<?php

namespace App\Orchid\Screens\ServiceCategory;

use App\Dashboard\Layouts\SEOLayout;
use App\Model\User;
use App\Model\ServiceCategory;
use App\Orchid\Requests\StoreServiceCategoryRequest;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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
     * @param  ServiceCategory  $category
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, ServiceCategory $category): array
    {
        $this->authorize(ServiceCategory::PERMISSION_TOUCH);

        $this->exist = $category->exists;

        return [
            'category' => $category,
            'seotools' => [],
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
                ->canSee($this->exist && $user->can(ServiceCategory::PERMISSION_DELETE)),

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
                'Nội dung' => Layouts\EditLayout::class,
            ]),
        ];
    }

    /**
     * Handle the "save" command action.
     *
     * @param  Request  $request
     * @param  ServiceCategory  $category
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreServiceCategoryRequest $request, ServiceCategory $category): RedirectResponse
    {
        $validated = $request->validated()['category'] ?? [];

        $category->fill([
            'name'         => clean($validated['name'] ?? ''),
            'description'  => clean($validated['description'] ?? '', true),
            'slug'         => clean($validated['slug'] ?? ''),
            'order'        => (int) ($validated['order'] ?? 0),
            'show_in_menu' => isset($validated['show_in_menu']) && null !== $validated['show_in_menu'],
            'icon'         => $validated['icon'] ?? '',
        ]);

        $category->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.services_categories.edit', $category);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  ServiceCategory  $category
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(ServiceCategory $category): RedirectResponse
    {
        $this->checkPermission(ServiceCategory::PERMISSION_DELETE);

        $category->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.services_categories');
    }
}
