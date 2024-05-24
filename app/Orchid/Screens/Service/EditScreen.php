<?php

namespace App\Orchid\Screens\Service;

use App\Dashboard\Layouts\SEOLayout;
use App\Model\Service;
use App\Model\ServiceCategory;
use App\Model\User;
use App\Orchid\Requests\StoreServiceRequest;
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

    protected $service;

    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  Service  $service
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Service $service): array
    {
        $this->authorize(Service::PERMISSION_TOUCH);

        $this->exist = $service->exists;

        $this->service = $service;

        $category = ServiceCategory::getCategory($service->id);

        return [
            'service'  => $service,
            'category' => $category,
            'seotools' => $service->getOptions()->get('seotools'),
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
            $this->addLink(__('Tạo'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            $this->addLink(__('Chi tiết'))
                ->icon('icon-doc')
                ->link(route('service', [$this->service->slug]))
                ->canSee($this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Service::PERMISSION_DELETE)),

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
                'SEO'      => SEOLayout::class,
            ]),
        ];
    }

    /**
     * Handle the "save" command action.
     *
     * @param  Request  $request
     * @param  Service  $service
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated()['service'] ?? [];

        $service->fill([
            'title'  => sanitize_text_field($validated['title'] ?? ''),
            'order'  => (int) $validated['order'],
            'slug'   => sanitize_text_field($validated['slug'] ?? ''),
            'status' => sanitize_text_field($validated['status'] ?? ''),
            'image'  => sanitize_text_field($validated['image'] ?? ''),
        ]);

        $service->options->set('seotools', SEOLayout::values($request));
        $service->saveOrFail();

        $category = $request->validated()['category'] ?? [];
        $service->categories()->sync($category);

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.services.edit', $service);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Service  $service
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Service $service): RedirectResponse
    {
        $this->checkPermission(Service::PERMISSION_DELETE);
        $service->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.services');
    }
}
