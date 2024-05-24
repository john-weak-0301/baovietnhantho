<?php

namespace App\Orchid\Screens\Experience;

use App\Model\Experience;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ExpRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ExpRepository
     */
    protected $exps;

    /**
     * Constructor.
     *
     * @param  ExpRepository  $exps
     */
    public function __construct(ExpRepository $exps)
    {
        parent::__construct();

        $this->exps = $exps;

        $this->name = __('Kinh nghiệm');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->exps;
    }

    public function query(): array
    {
        $this->authorize(Experience::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.exps.create')
                ->canSee($this->currentUserCan(Experience::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            new Layouts\ListTable,
        ];
    }
}
