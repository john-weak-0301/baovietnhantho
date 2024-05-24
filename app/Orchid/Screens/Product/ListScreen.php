<?php

namespace App\Orchid\Screens\Product;

use App\Model\Product;
use App\Orchid\Screens\AbstractScreen;
use App\Repositories\ProductRepository;
use Core\Database\Repository;
use Core\Screens\HasRepository;
use Core\Screens\InteractsWithActions;
use Illuminate\Support\Arr;

class ListScreen extends AbstractScreen implements HasRepository
{
    use InteractsWithActions;

    /**
     * @var ProductRepository
     */
    protected $products;

    /**
     * @var string
     */
    protected $productType;

    /**
     * Constructor.
     *
     * @param  ProductRepository  $products
     */
    public function __construct(ProductRepository $products)
    {
        $this->products = $products;

        $this->name = __('Sản phẩm');

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function query(): array
    {
        $this->authorize(Product::PERMISSION_VIEW);

        return [];
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
        $addLink = route('platform.products.create');

        if ($this->productType) {
            $addLink = $addLink.'?'.Arr::query(['type' => $this->productType]);
        }

        return [
            $this->addLink(__('Thêm mới'))
                ->link($addLink)
                ->canSee($this->currentUserCan(Product::PERMISSION_TOUCH))
                ->icon('icon-plus'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            (new Layouts\ListTable)->queryFrom($this->getRepository()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository(): Repository
    {
        return $this->products;
    }
}
