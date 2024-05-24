<?php

namespace App\Orchid\Filters;

use App\Model\Category;
use Core\Elements\Filter;
use Orchid\Screen\Fields\Select;
use Illuminate\Database\Eloquent\Builder;

class NewsCategoryFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = ['cate'];

    /**
     * @var Category
     */
    protected $model;

    /**
     * @var array
     */
    protected $categories;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->model = new Category;

        parent::__construct();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Danh mục');
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $cate = array_filter(
            array_map('intval', (array) $this->request->cate)
        );

        $builder->whereHas('categories', function ($query) use ($cate) {
            $query->whereIn('id', $cate);
        });

        return $builder;
    }

    /**
     * @return array|\Orchid\Screen\Field[]
     */
    public function display(): array
    {
        $this->categories = $this->model->newQuery()->pluck('name', 'id')->all();

        return [
            Select::make('cate')
                ->empty()
                ->multiple()
                ->value($this->request->input('cate'))
                ->options($this->categories),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValues(array $parameters)
    {
        $cate = (array) ($parameters['cate'] ?? []);

        return [
            'cate' => collect($this->categories)->filter(function ($value, $key) use ($cate) {
                return in_array($key, array_values($cate));
            })->all(),
        ];
    }
}
