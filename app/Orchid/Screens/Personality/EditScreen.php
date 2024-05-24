<?php

namespace App\Orchid\Screens\Personality;

use App\Model\Personality;
use App\Model\User;
use App\Orchid\Requests\StorePersonalityRequest;
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
     * @param  Personality  $personality
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Personality $personality): array
    {
        $this->authorize(Personality::PERMISSION_TOUCH);

        $this->exist = $personality->exists;

        return ['personality' => $personality];
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
                ->canSee($this->exist && $user->can(Personality::PERMISSION_DELETE)),

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
     * @param  Personality  $personality
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StorePersonalityRequest $request, Personality $personality): RedirectResponse
    {
        $validated = $request->validated()['personality'] ?? [];

        $personality->fill([
            'name' => sanitize_text_field($validated['name'] ?? ''),
            'description' => wp_kses_post($validated['description'] ?? ''),
            'slug' => sanitize_text_field($validated['slug'] ?? ''),
        ]);

        $personality   ->saveOrFail();
        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.personalities.edit', $personality);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Personality  $personality
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Personality $personality): RedirectResponse
    {
        $this->checkPermission(Personality::PERMISSION_DELETE);

        $personality->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.personalities');
    }
}
