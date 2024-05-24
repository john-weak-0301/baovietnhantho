<?php

namespace Core\Elements;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class FiltersLayout extends Selection
{
    /**
     * The filters instance.
     *
     * @var array|Filter[]
     */
    protected $filters;

    /**
     * @var string
     */
    public $template = 'core::layouts.selection';

    /**
     * Constructor.
     *
     * @param  array|Filter[]  $filters
     */
    public function __construct(array $filters = [])
    {
        parent::__construct();

        $this->filters = $filters;
    }

    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return $this->filters;
    }
}
