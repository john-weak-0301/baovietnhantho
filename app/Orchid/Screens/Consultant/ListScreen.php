<?php

namespace App\Orchid\Screens\Consultant;

use App\Orchid\Actions\UpdateConsultantStatus;
use Illuminate\Http\Request;
use Orchid\Screen\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Fields\Select;
use App\Model\Consultant;
use App\Exports\ConsultantsExporter;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Actions\DeleteAction;
use App\Repositories\ConsultantRepository;
use Core\Screens\InteractsWithActions;
use Core\Screens\HasRepository;
use Core\Database\Repository;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ConsultantRepository
     */
    protected $consultants;

    /**
     * Constructor.
     *
     * @param ConsultantRepository $consultants
     */
    public function __construct(ConsultantRepository $consultants)
    {
        parent::__construct();

        $this->consultants = $consultants;

        $this->name = __('Tư vấn khách hàng');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->consultants;
    }

    /**
     * @return array
     */
    public function query(): array
    {
        $this->authorize(Consultant::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [
            new UpdateConsultantStatus(request()->status ?: 'pending'),
            new DeleteAction(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::name('Export')
                ->icon('icon-share-alt')
                ->modal('export')
                ->method('exports'),

            $this->addLink(__('Thêm mới'), 'platform.consultants.create')
                ->canSee($this->currentUserCan(Consultant::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            Layout::wrapper('platform.consultant.index', [
                'table' => (new Layouts\ListTable)->queryFrom($this->getRepository()),
            ]),

            Layout::modal('export', [
                Layout::rows([
                    Select::make('year')
                        ->title('Năm')
                        ->required()
                        ->options(
                            Consultant::selectRaw('YEAR(created_at) as year')
                                ->distinct()
                                ->pluck('year', 'year')
                        ),

                    Select::make('status')
                        ->title(__('Trạng thái'))
                        ->required()
                        ->options([
                            'withConsulted' => 'Tất cả',
                            'onlyConsulted' => 'Đã tư vấn',
                            'withoutConsulted' => 'Chưa tư vấn',
                        ]),
                ]),
            ])->applyButton('Download'),
        ];
    }

    public function exports(Request $request)
    {
        $year = $request->year ?: (int) date('Y');

        if (!in_array(
            $method = $request->status,
            ['onlyConsulted', 'withoutConsulted', 'withConsulted']
        )) {
            $method = 'withConsulted';
        }

        return (new ConsultantsExporter)
            ->forYear($year)
            ->{$method}()
            ->download("BVNT-TVKH-${year}.xlsx");
    }
}
