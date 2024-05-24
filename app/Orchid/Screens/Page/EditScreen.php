<?php

namespace App\Orchid\Screens\Page;

use App\Model\Page;
use App\Model\User;
use App\Orchid\Requests\StorePageRequest;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EditScreen extends AbstractScreen
{
    /**
     * Indicator the model's exists or not.
     *
     * @var bool
     */
    protected $exist = false;

    protected $page;

    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  Page  $page
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Page $page): array
    {
        $this->authorize(Page::PERMISSION_TOUCH);

        $this->exist = $page->exists;
        $this->page = $page;

        return [
            'page' => $page,
            'options' => $page->getOptions(),
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
                ->link($this->page->url)
                ->canSee($this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Page::PERMISSION_DELETE)),

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
     * @param  Page  $page
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StorePageRequest $request, Page $page): RedirectResponse
    {
        $validated = $request->validated()['page'] ?? [];

        $page->fill([
            'title' => sanitize_text_field($validated['title'] ?? ''),
            'slug' => sanitize_text_field($validated['slug'] ?? ''),
            'status' => sanitize_text_field($validated['status'] ?? ''),
        ]);

        $page->options->show_page_title = $request->input('options.show_page_title') === null ? '0' : '1';
        $page->options->title_image = $request->input('options.title_image');
        $page->options->style = $request->input('options.style');

        $page->saveOrFail();
        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.pages.edit', $page);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Page  $page
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Page $page): RedirectResponse
    {
        $this->checkPermission(Page::PERMISSION_DELETE);

        $page->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.pages');
    }
}
