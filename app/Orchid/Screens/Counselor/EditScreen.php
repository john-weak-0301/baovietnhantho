<?php

namespace App\Orchid\Screens\Counselor;

use App\Model\User;
use App\Model\Counselor;
use App\Model\Personality;
use App\Orchid\Requests\StoreCounselorRequest;
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
     * @param  Counselor  $counselor
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Request $request, Counselor $counselor): array
    {
        $this->authorize(Counselor::PERMISSION_TOUCH);

        $this->exist = $counselor->exists;

        return [
            'counselor'   => $counselor,
            'personality' => Personality::getPersonalities($counselor->id),
            '__area'      => [
                'province' => $counselor->province_id,
                'district' => $counselor->district_id,
            ],
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
                ->canSee($this->exist && $user->can(Counselor::PERMISSION_DELETE)),

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
     * @param  StoreCounselorRequest  $request
     * @param  Counselor  $counselor
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(StoreCounselorRequest $request, Counselor $counselor): RedirectResponse
    {
        $validated  = $request->validated();
        $attributes = $validated['counselor'];

        $counselor->fill([
            'uid'           => clean($attributes['uid']),
            'company_name'  => clean($attributes['company_name'] ?? ''),
            'first_name'    => clean($attributes['first_name'] ?? ''),
            'last_name'     => clean($attributes['last_name'] ?? ''),
            'display_name'  => clean($attributes['display_name'] ?? ''),
            'year_of_birth' => $attributes['year_of_birth'] ?? 0,
            'avatar'        => clean($attributes['avatar'] ?? ''),
            'gender'        => clean($attributes['gender'] ?? ''),
            'rate_value'    => (int) $attributes['rate_value'],
            'province_id'   => (int) $validated['__area']['province'],
            'district_id'   => (int) $validated['__area']['district'],
        ]);

        $counselor->saveOrFail();

        $personality = $validated['personality'] ?? [];
        $counselor->personality()->sync($personality);

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.counselors.edit', $counselor);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Counselor  $counselor
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Counselor $counselor): RedirectResponse
    {
        $this->checkPermission(Counselor::PERMISSION_DELETE);

        $counselor->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.counselors');
    }
}
