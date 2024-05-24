<?php

namespace App\Orchid\Screens\AdditionProduct;

use App\Repositories\AdditionProductRepository;
use App\Orchid\Screens\Product\Layouts\ListTable;
use App\Orchid\Screens\Product\ListScreen as ProductListScreen;

class ListScreen extends ProductListScreen
{
    /**
     * @var string
     */
    protected $productType = 'addition';

    /**
     * Constructor.
     *
     * @param  AdditionProductRepository  $products
     */
    public function __construct(AdditionProductRepository $products)
    {
        parent::__construct($products);

        $this->products = $products;

        $this->name = __('Sản phẩm bổ trợ');
    }

    /**
     * {@inheritdoc}
     */
    public function layout(): array
    {
        return [
            (new ListTable)->queryFrom($this->getRepository()),
        ];
    }
}
