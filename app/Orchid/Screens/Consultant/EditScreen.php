<?php

namespace App\Orchid\Screens\Consultant;

use App\Model\Categoriable;
use App\Model\Consultant;
use App\Orchid\Requests\StoreConsultantRequest;
use App\Orchid\Screens\AbstractScreen;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class EditScreen extends AbstractScreen
{
    /**
     * Indicator the model's exists or not.
     *
     * @var bool
     */
    protected $exist = false;

    protected $reserved = false;

    /**
     * @param  Request  $request
     * @param  Consultant  $consultant
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function query(Request $request, Consultant $consultant): array
    {
        $this->authorize(Consultant::PERMISSION_TOUCH);

        $this->exist = $consultant->exists;

        $this->reserved = $consultant->reserved_at;

        $dates = Consultant::getDate();
        view()->share(compact('consultant', 'dates'));

        return [
            'consultant' => $consultant,
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
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
                ->canSee($this->exist && $user->can(Consultant::PERMISSION_DELETE)),

            $this->addLink(__('Đã tư vấn'))
                ->icon('icon-friends')
                ->method('saveReserved')
                ->canSee($this->exist && $this->reserved === null),

            $this->addLink(__('Chưa tư vấn'))
                ->icon('icon-friends')
                ->method('saveReserved')
                ->canSee($this->exist && $this->reserved !== null),

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

    public function save(StoreConsultantRequest $request, Consultant $consultant): RedirectResponse
    {
        $validated = $request->validated()['consultant'] ?? [];

        $consultant->fill([
            'customer_name'    => clean($validated['customer_name'] ?? ''),
            'customer_phone'   => clean($validated['customer_phone'] ?? ''),
            'customer_email'   => clean($validated['customer_email'] ?? ''),
            'customer_address' => clean($validated['customer_address'] ?? ''),
            'private_note'     => clean($validated['private_note'] ?? ''),
            'counselor_id'     => (int) $validated['counselor_id'],
            'data'             => isset($request->consultant['data']) ? $request->consultant['data'] : '',
        ]);

        $consultant->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.consultants.edit', $consultant);
    }

    public function saveReserved(Consultant $consultant): RedirectResponse
    {
        if ($consultant->reserved_at) {
            $consultant->reserved_at = null;
        } else {
            $consultant->reserved_at = Carbon::now();
        }

        $consultant->saveOrFail();
        alert()->success(__('Khách hàng ').$consultant->customer_name.__(' cập nhật trạng thái'));

        return redirect()->back();
    }

    public function destroy(Consultant $consultant): RedirectResponse
    {
        $this->checkPermission(Consultant::PERMISSION_DELETE);

        $consultant->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.consultants');
    }
}
