<?php

namespace App\Orchid\Screens\Category;

use App\Model\Category;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\CategoryRepository;
use App\Repositories\PopupRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Illuminate\Http\Request;
use Orchid\Screen\Layout;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var PopupRepository
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param  PopupRepository  $pages
     */
    public function __construct(CategoryRepository $categories)
    {
        parent::__construct();

        $this->categories = $categories;

        $this->name = __('Danh mục');
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
        $this->authorize(Category::PERMISSION_VIEW);

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
                ->canSee($this->currentUserCan(Category::PERMISSION_TOUCH))
                ->icon('icon-sort-numeric-desc')
                ->method('saveOrder'),

            $this->addLink(__('Thêm mới'), 'platform.categories.create')
                ->canSee($this->currentUserCan(Category::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    public function layout(): array
    {
        return [
            new Layouts\ListTable,
        ];
    }

    public function saveOrder(Request $request)
    {
        $orders = $request->input('orders') ?: [];

        if (empty($orders)) {
            return redirect()->back();
        }

        $ids        = array_keys($orders);
        $categories = Category::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            $order = $orders[$category->id] ?? null;

            if ($order !== null) {
                $category->order = $order;
                $category->save();
            }
        }

        return redirect()->back(302, [], '/dashboard/categories');
    }
}
