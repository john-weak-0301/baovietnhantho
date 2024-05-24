<?php

namespace App\Orchid\Screens\ProductCategory;

use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use App\Model\ProductCategory;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ProductCategoryRepository
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param  ProductCategoryRepository  $categories
     */
    public function __construct(ProductCategoryRepository $categories)
    {
        parent::__construct();

        $this->name = __('Danh mục sản phẩm');

        $this->categories = $categories;
    }

    /**
     * {@inheritdoc}
     */
    public function query(): array
    {
        $this->authorize(ProductCategory::PERMISSION_VIEW);

        return [
            'categories' => $this->queryForCategories($this->request),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function commandBar(): array
    {
        return [
            $this->addLink(__('Lưu thứ tự'))
                ->canSee($this->currentUserCan(ProductCategory::PERMISSION_TOUCH))
                ->icon('icon-sort-numeric-desc')
                ->method('saveOrder'),

            $this->addLink(__('Thêm mới'), 'platform.products.categories.create')
                ->canSee($this->currentUserCan(ProductCategory::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    /**
     * {@inheritdoc}
     */
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
        $categories = ProductCategory::whereIn('id', $ids)->get();

        foreach ($categories as $category) {
            $order = $orders[$category->id] ?? null;

            if ($order !== null) {
                $category->order = $order;
                $category->save();
            }
        }

        return redirect()->back(302, [], '/dashboard/product/categories');
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->categories;
    }

    protected function queryForCategories(Request $request)
    {
        /* @var LengthAwarePaginator $categories */
        $categories = ProductCategory::whereIsRoot()
            ->orderByDesc('order')
            ->paginate(25);

        // Load the children of the root to prevent n+1 queries.
        $categories->load([
            'children' => function ($query) {
                $query->orderBy('parent_id')->orderByDesc('order');
            },
        ]);

        // Flatten children to the paginate.
        $items = collect();

        $categories->each(function ($item) use (&$items) {
            $items->push($item);

            foreach ($item->children as $children) {
                $items->push($children);
            }
        });

        $categories->setCollection($items);

        return $categories;
    }
}
