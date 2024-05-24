<?php

namespace App\Orchid\Screens\Counselor;

use App\Imports\CounselorsImport;
use App\Model\Counselor;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\CounselorRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layout;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var CounselorRepository
     */
    protected $counselors;

    /**
     * Constructor.
     *
     * @param  CounselorRepository  $counselors
     */
    public function __construct(CounselorRepository $counselors)
    {
        parent::__construct();

        $this->counselors = $counselors;

        $this->name = __('Nhân viên tư vấn');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->counselors;
    }

    /**
     * {@inheritdoc}
     */
    public function query(): array
    {
        $this->authorize(Counselor::PERMISSION_VIEW);

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            $this->addLink(__('Import TVV'), 'platform.counselors.create')
                ->canSee($this->currentUserCan(Counselor::PERMISSION_TOUCH))
                ->icon('icon-cloud-upload')
                ->method('import')
                ->modal('import'),

            $this->addLink(__('Thêm mới'), 'platform.counselors.create')
                ->canSee($this->currentUserCan(Counselor::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        try {
            Excel::import($import = new CounselorsImport, $request->file('file'), null, 'Xlsx');
        } catch (\Exception $e) {
            alert()->error('Vui lòng chọn tận tin .xlsx');

            return redirect('/dashboard/counselors');
        }

        if ($import->errors()->isNotEmpty()) {
            alert()->error('Có một số lỗi xảy ra trong quá trình import');
        }

        if ($count = $import->getImported()) {
            alert()->success(sprintf('Import thành công %s tư vấn viên', $count));
        } else {
            alert()->warning('Không có tư vấn viên nào được import');
        }

        return redirect('/dashboard/counselors');
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            (new Layouts\ListTable)->queryFrom($this->getRepository()),

            Layout::modal('import', [
                Layout::rows([
                    Input::make('file')
                        ->type('file')
                        ->title(__('Chọn file Excel tải lên'))
                        ->required(),
                ]),
            ]),
        ];
    }
}
