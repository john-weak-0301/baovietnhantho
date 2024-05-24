<?php

namespace App\Orchid\Screens\BranchService;

use App\Model\BranchService;
use App\Model\Category;
use App\Model\User;
use App\Orchid\Requests\StoreBranchServiceRequest;
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
     * @param  BranchService  $category
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, BranchService $service): array
    {
        $this->authorize(BranchService::PERMISSION_TOUCH);

        $this->exist = $service->exists;

        return ['service' => $service];
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
                ->canSee($this->exist && $user->can(BranchService::PERMISSION_DELETE)),

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
     * @param  BranchService  $service
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreBranchServiceRequest $request, BranchService $service): RedirectResponse
    {
        $validated = $request->validated()['service'] ?? [];

        $service->fill([
            'name' => sanitize_text_field($validated['name'] ?? ''),
            'description' => wp_kses_post($validated['description'] ?? ''),
        ]);

        $service->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.branchs.services.edit', $service);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  BranchService  $service
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(BranchService $service): RedirectResponse
    {
        $this->checkPermission(BranchService::PERMISSION_DELETE);

        $service->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.branchs.services');
    }
}
