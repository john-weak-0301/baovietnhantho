<?php

namespace App\Orchid\Screens\Popup;

use App\Model\Popup;
use App\Model\Page;
use App\Model\User;
use App\Orchid\Requests\StorePopupRequest;
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

    /**
     * @var Popup
     */
    protected $popup;

    /**
     * Query data.
     *
     * @param Request $request
     * @param Popup $popup
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Popup $popup): array
    {
        $this->authorize(Popup::PERMISSION_TOUCH);

        $this->exist = $popup->exists;
        $this->popup = $popup;

        return [
            'popup' => $popup,
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

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Popup::PERMISSION_DELETE)),

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
     * @param StorePopupRequest $request
     * @param Popup $popup
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StorePopupRequest $request, Popup $popup): RedirectResponse
    {
        $validated = $request->validated()['popup'] ?? [];

        $popup->fill([
            'title1' => sanitize_text_field($validated['title1'] ?? ''),
            'order' => clean($validated['order'] ?? 1),
            'title2' => sanitize_text_field($validated['title2'] ?? ''),
            'description' => wp_kses_post($validated['description'] ?? ''),
            'layout' => sanitize_text_field($validated['layout'] ?? 'left'),
            'image' => sanitize_text_field($validated['image'] ?? ''),
            'cta_text' => sanitize_text_field($validated['cta_text'] ?? ''),
            'cta_link' => sanitize_text_field($validated['cta_link'] ?? null),
            'show_all' => $request->input('popup.show_all') === null ? 0 : 1,
            'show_products' => $request->input('popup.show_products') === null ? 0 : 1,
            'show_posts' => $request->input('popup.show_posts') === null ? 0 : 1,
            'show_pages' => $request->input('popup.show_pages') === null ? 0 : 1,
            'show_service' => $request->input('popup.show_service') === null ? 0 : 1,
            'show_expert' => $request->input('popup.show_expert') === null ? 0 : 1,
            'show_home_page' => $request->input('popup.show_home_page') === null ? 0 : 1,
            'show_more_links' => sanitize_text_field($validated['show_more_links'] ?? null),
        ]);

        // $popup->options->show_page_title = $request->input('options.show_page_title') === null ? '0' : '1';
        // $popup->options->title_image = $request->input('options.title_image');
        // $popup->options->style = $request->input('options.style');

        $popup->saveOrFail();
        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.popups.edit', $popup);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param Popup $popup
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Popup $popup): RedirectResponse
    {
        $this->checkPermission(Popup::PERMISSION_DELETE);

        $popup->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.popups');
    }
}
