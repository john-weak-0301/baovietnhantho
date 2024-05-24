<?php

namespace App\Orchid\Screens\Service;

use App\Model\Service;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ServiceRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ServiceRepository
     */
    protected $services;

    /**
     * Constructor.
     *
     * @param  ServiceRepository  $services
     */
    public function __construct(ServiceRepository $services)
    {
        parent::__construct();

        $this->services = $services;

        $this->name = __('Dịch vụ khách hàng');
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
        $this->authorize(Service::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.services.create')
                ->canSee($this->currentUserCan(Service::PERMISSION_TOUCH))
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
