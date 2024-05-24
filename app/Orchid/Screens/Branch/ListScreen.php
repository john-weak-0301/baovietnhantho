<?php

namespace App\Orchid\Screens\Branch;

use App\Model\Branch;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\BranchRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var BranchRepository
     */
    protected $branchs;

    /**
     * Constructor.
     *
     * @param  BranchRepository  $branchs
     */
    public function __construct(BranchRepository $branchs)
    {
        parent::__construct();

        $this->branchs = $branchs;

        $this->name = __('Chi nhánh');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->branchs;
    }

    public function query(): array
    {
        $this->authorize(Branch::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.branchs.create')
                ->canSee($this->currentUserCan(Branch::PERMISSION_TOUCH))
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
