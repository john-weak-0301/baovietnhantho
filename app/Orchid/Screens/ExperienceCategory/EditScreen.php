<?php

namespace App\Orchid\Screens\ExperienceCategory;

use App\Model\ExperienceCategory;
use App\Model\User;
use App\Orchid\Requests\StoreExpCategoryRequest;
use App\Orchid\Screens\AbstractScreen;

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
     * @param  ExperienceCategory  $category
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, ExperienceCategory $category): array
    {
        $this->authorize(ExperienceCategory::PERMISSION_TOUCH);

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
                ->canSee($this->exist && $user->can(ExperienceCategory::PERMISSION_DELETE)),

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
     * @param  ExperienceCategory  $category
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreExpCategoryRequest $request, ExperienceCategory $category): RedirectResponse
    {
        $validated = $request->validated()['category'] ?? [];

        $category->fill([
            'name' => sanitize_text_field($validated['name'] ?? ''),
            'description' => wp_kses_post($validated['description'] ?? ''),
            'slug' => sanitize_text_field($validated['slug'] ?? ''),
        ]);

        $category ->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.exps_categories.edit', $category);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  ExperienceCategory  $category
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(ExperienceCategory $category): RedirectResponse
    {
        $this->checkPermission(ExperienceCategory::PERMISSION_DELETE);

        $category->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.exps_categories');
    }
}
