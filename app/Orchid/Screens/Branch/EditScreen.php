<?php

namespace App\Orchid\Screens\Branch;

use App\Model\Branch;
use App\Model\BranchService;
use App\Orchid\Requests\StoreBranchRequest;
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
     * Query data.
     *
     * @param  Branch  $branch
     * @return array
     */
    public function query(Request $request, Branch $branch): array
    {
        $this->authorize(Branch::PERMISSION_TOUCH);

        $this->exist      = $branch->exists;
        $branch->location = [
            'lat' => $branch->latitude,
            'lng' => $branch->longitude,
        ];

        $dates   = Branch::getDate();
        $service = BranchService::getServices($branch->id);
        view()->share(compact('branch', 'dates'));

        return [
            'branch'  => $branch,
            'service' => $service,
        ];
    }

    /**
     * {@inheritdoc}
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
                ->canSee($this->exist && $user->can(Branch::PERMISSION_DELETE)),

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

    public function save(StoreBranchRequest $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validated()['branch'] ?? [];

        $branch->fill([
            'address'       => sanitize_text_field($validated['address'] ?? ''),
            'province_code' => (int) $validated['province_code'],
            'ward_code'     => 0,
            'district_code' => 0,
            'name'          => $validated['name'],
            'latitude'      => $request->branch['location']['lat'],
            'longitude'     => $request->branch['location']['lng'],
            'phone_number'  => $validated['phone_number'],
            'fax_number'    => clean($validated['fax_number'] ?? ''),
            'email'         => clean($validated['email'] ?? ''),
            'type'          => $validated['type'],
            'working_days'  => $validated['working_days'],
        ]);
        $branch->saveOrFail();

        $service = $request->validated()['service'] ?? [];
        $branch->services()->sync($service);

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.branchs.edit', $branch);
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->checkPermission(Branch::PERMISSION_DELETE);

        $branch->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.branchs');
    }
}
