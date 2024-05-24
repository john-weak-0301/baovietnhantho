<?php

namespace App\Orchid\Screens\Fund;

use App\Model\Fund;
use App\Orchid\Screens\AbstractScreen;
use App\Orchid\Actions\DeleteAction;
use App\Repositories\FundRepository;
use Core\Screens\InteractsWithActions;
use Core\Screens\HasRepository;
use Core\Database\Repository;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var FundRepository
     */
    protected $funds;

    /**
     * @var string
     */
    protected $fundType;

    /**
     * Constructor.
     *
     * @param FundRepository $contacts
     */
    public function __construct(FundRepository $funds)
    {
        parent::__construct();

        $this->funds = $funds;

        $this->name = 'Quỹ liên kết';
    }

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'ListScreen';

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->funds;
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $this->authorize(Fund::PERMISSION_VIEW);

        return [];    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        $addLink = route('platform.funds.create');

        if ($this->fundType) {
            $addLink = $addLink.'?'.Arr::query(['type' => $this->fundType]);
        }

        return [
            $this->addLink(__('Thêm mới'))
                ->link($addLink)
                ->canSee($this->currentUserCan(Fund::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            (new Layouts\ListTable)->queryFrom($this->getRepository()),
        ];
    }
}
