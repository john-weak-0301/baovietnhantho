<?php

namespace App\Orchid\Screens\FundCost;

use App\Model\Fund;
use App\Model\FundCost;
use App\Model\FundImport;
use App\Orchid\Screens\AbstractScreen;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;
use Orchid\Screen\Fields\Input;
use App\Orchid\Actions\DeleteAction;
use Illuminate\Http\RedirectResponse;
use App\Repositories\FundCostRepository;
use Illuminate\Support\HtmlString;
use App\Orchid\Requests\UpdateFundImportRequest;

class ListScreen extends AbstractScreen
{
    /**
     * Query data.
     *
     * @param  Request  $request
     * @param  FundImport  $fundImport
     * @return array
     *
     * @throws \Throwable
     */
    public function query(FundImport $fundImport): array
    {
        $this->authorize(Fund::PERMISSION_VIEW);

        return ['fundImport' => $fundImport];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            $this->addLink(__('Lưu'))
                ->icon('icon-check')
                ->canSee($this->currentUserCan(Fund::PERMISSION_TOUCH))
                ->method('save'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            Layouts\EditLayout::class,
            (new Layouts\ListTable())
                ->queryFrom(app(FundCostRepository::class))
        ];
    }

    public function save(FundImport $fundImport, UpdateFundImportRequest $request): RedirectResponse
    {
        $validated = $request->validated()['fundImport'] ?? [];

        $fundImport->fill([
            'status' => clean($validated['status'] ?? 1),
        ]);

        $fundImport->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.funds.edit', $fundImport->quy_lkdv_id);
    }

    public function update($id, Request $request): RedirectResponse
    {
        $input = $request->all();
        if (isset($input['status'])) {
            if (!in_array($input['status'], FundImport::STATUS_KEYS)) {
                alert()->error(__('Lưu thất bại.'));
                return redirect()->route('platform.funds.edit', $fundImport->quy_lkdv_id);
            }
        }
        $fundImport = FundImport::where('id', $id)->firstOrFail();

        $fundImport->fill([
            'status' => clean($input['status'] ?? FundImport::IMPORTING),
            'approved_at' => $input['status'] == FundImport::APPROBED ? now() : null,
        ]);

        $fundImport->saveOrFail();

        alert()->success(__('Lưu thành công.'));

        return redirect()->route('platform.funds.edit', $fundImport->quy_lkdv_id);
    }

    public function delete($id): RedirectResponse
    {
        $fundImport = FundImport::find($id);
        $fundImport->delete();
        FundCost::where('imported_id', $id)->delete();
        alert()->success(__('Xóa thành công.'));
        return redirect()->route('platform.funds.edit', $fundImport->quy_lkdv_id);
    }
}
