<?php

namespace App\Orchid\Screens\ServiceCategory;

use App\Model\ServiceCategory;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ServiceCategoryRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ServiceCategoryRepository
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param  ServiceCategoryRepository  $categories
     */
    public function __construct(ServiceCategoryRepository $categories)
    {
        parent::__construct();

        $this->categories = $categories;

        $this->name = __('Danh mục dịch vụ ');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->categories;
    }

    public function query(): array
    {
        $this->authorize(ServiceCategory::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Thêm mới'), 'platform.services_categories.create')
                ->canSee($this->currentUserCan(ServiceCategory::PERMISSION_TOUCH))
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
