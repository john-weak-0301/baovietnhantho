<?php

namespace App\Orchid\Screens\ExperienceCategory;

use App\Model\ExperienceCategory;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ExpCategoryRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Illuminate\Http\Request;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ExpCategoryRepository
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param  ExpCategoryRepository  $categories
     */
    public function __construct(ExpCategoryRepository $categories)
    {
        parent::__construct();

        $this->categories = $categories;

        $this->name = __('Danh mục chuyên gia');
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
        $this->authorize(ExperienceCategory::PERMISSION_VIEW);

        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function commandBar(): array
    {
        return [
            $this->addLink(__('Lưu thứ tự'))
                ->canSee($this->currentUserCan(ExperienceCategory::PERMISSION_TOUCH))
                ->icon('icon-sort-numeric-desc')
                ->method('saveOrder'),

            $this->addLink(__('Thêm mới'), 'platform.exps_categories.create')
                ->canSee($this->currentUserCan(ExperienceCategory::PERMISSION_TOUCH))
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

    public function saveOrder(Request $request)
    {
        $orders = $request->input('orders') ?: [];

        if (empty($orders)) {
            return redirect()->back();
        }

        $ids        = array_keys($orders);
        $categories = ExperienceCategory::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            $order = $orders[$category->id] ?? null;

            if ($order !== null) {
                $category->order = $order;
                $category->save();
            }
        }

        return redirect()->back(302, [], '/dashboard/exps_categories');
    }
}
