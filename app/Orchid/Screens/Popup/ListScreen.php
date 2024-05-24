<?php

namespace App\Orchid\Screens\Popup;

use App\Model\Popup;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\PopupRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var PopupRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param PopupRepository $pages
     */
    public function __construct(PopupRepository $pages)
    {
        parent::__construct();

        $this->repository = $pages;

        $this->name = __('Popups');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    public function query(): array
    {
        $this->authorize(Popup::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.popups.create')
                ->canSee($this->currentUserCan(Popup::PERMISSION_TOUCH))
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
