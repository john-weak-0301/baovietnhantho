<?php

namespace App\Orchid\Screens\BranchService;

use App\Model\BranchService;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\BranchServiceRepository;
use App\Repositories\PopupRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var BranchServiceRepository
     */
    protected $services;

    /**
     * Constructor.
     *
     * @param  BranchServiceRepository  $services
     */
    public function __construct(BranchServiceRepository $services)
    {
        parent::__construct();

        $this->services = $services;

        $this->name = __('Dịch vụ');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->services;
    }

    public function query(): array
    {
        $this->authorize(BranchService::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.branchs.services.create')
                ->canSee($this->currentUserCan(BranchService::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            (new Layouts\ListTable)
                ->queryFrom($this->getRepository()),
        ];
    }
}
