<?php

namespace App\Orchid\Screens\Fund;

use App\Model\Fund;
use App\Imports\FundImport;
use App\Model\FundImport as FundImportModel;
use App\Orchid\Requests\StoreFundRequest;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\FundImportRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Fields\Input;
use Maatwebsite\Excel\Facades\Excel;

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
     * @param  Fund  $fund
     * @return array
     *
     * @throws \Throwable
     */
    public function query(Fund $fund): array
    {
        $this->authorize(Fund::PERMISSION_TOUCH);

        $this->exist = $fund->exists;

        return ['fund' => $fund];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        /* @var $user User */
        $user = $this->request->user();

        return [
            $this->addLink(__('Import giá quỹ'))
                ->canSee($this->exist && $user->can(Fund::PERMISSION_DELETE))
                ->icon('icon-cloud-upload')
                ->method('import')
                ->modal('import'),

            $this->addLink(__('Thêm'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            $this->addLink(__('Xóa'))
                ->icon('icon-trash')
                ->method('destroy')
                ->confirm(__('Bạn có muốn xóa?'))
                ->canSee($this->exist && $user->can(Fund::PERMISSION_DELETE)),

            $this->addLink(__('Lưu'))
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->exist),
        ];
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        // Get fund model
        $fund = Fund::findOrFail($request->id);

        // Create new fund import
        $fundImport = FundImportModel::create([
            'quy_lkdv_id' => $fund->id,
            'status' => FundImportModel::IMPORTING,
        ]);

        try {
            $extension = pathinfo($request->file->getClientOriginalName(), PATHINFO_EXTENSION);

            Excel::import(
                $import = new FundImport([
                    "fund_id" => $fund->id,
                    "imported_id" => $fundImport->id,
                ]),
                $request->file('file'),
                null,
                ucfirst($extension)
            );
        } catch (\Exception $e) {
            $fundImport->delete();

            alert()->error('Vui lòng chọn tận tin .xlsx hoặc .xls hoặc .csv');

            return redirect("/dashboard/funds/{$fund->id}/edit");
        }

        if ($import->errors()->isNotEmpty()) {
            alert()->error('Có một số lỗi xảy ra trong quá trình import');
        }

        if ($count = $import->getImported()) {
            alert()->success(sprintf('Import thành công %s giá quỹ', $count));
        } else {
            alert()->warning('Không có giá quỹ nào được import');
        }

        // Update fund import's status
        $fundImport->status = FundImportModel::PENDING;
        $fundImport->save();

        return redirect("/dashboard/funds/{$fund->id}/edit");
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layouts\EditLayout::class,

            (new Layouts\ImportedListTable())
                ->queryFrom(app(FundImportRepository::class))
                ->showSearchInput(false),

            Layout::modal('import', [
                Layout::rows([
                    Input::make('file')
                        ->type('file')
                        ->title(__('Chọn file CSV/Excel tải lên'))
                        ->required(),
                ]),
            ]),
        ];
    }

    /**
     * Handle the "save" command action.
     *
     * @param  Request  $request
     * @param  Fund  $fund
     * @return RedirectResponse
     *
     * @throws \Throwable
     */
    public function save(Fund $fund, StoreFundRequest $request): RedirectResponse
    {
        $validated = $request->validated()['fund'] ?? [];

        $fund->fill([
            'name' => sanitize_text_field($validated['name'] ?? ''),
            'risks_of_investing' => wp_kses_post($validated['risks_of_investing'] ?? ''),
            'desc_target' => wp_kses_post($validated['desc_target'] ?? ''),
            'desc_profit' => wp_kses_post($validated['desc_profit'] ?? ''),
            'desc_invest' => wp_kses_post($validated['desc_invest'] ?? ''),
            'order' => clean($validated['order'] ?? 1),
        ]);

        $fund ->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.funds.edit', $fund);
    }

    /**
     * Handle the "destroy" command action.
     *
     * @param  Fund  $fund
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Fund $fund): RedirectResponse
    {
        $this->checkPermission(Fund::PERMISSION_DELETE);

        $fund->delete();
        alert()->success(__('Xóa thành công.'));

        return redirect()->route('platform.funds');
    }
}
