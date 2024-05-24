<?php

namespace Core\Elements\Table;

use Core\Elements\Filter;
use Illuminate\Support\Collection;

trait InteractsWithFilters
{
    /**
     * Gets the filters.
     *
     * @return array|Filter[]
     */
    public function filters()
    {
        if ($this->screen && method_exists($this->screen, 'filters')) {
            return $this->screen->filters();
        }

        return [];
    }

    /**
     * Get the filters that are available for the given request.
     *
     * @return Collection|Filter[]
     */
    public function availableFilters(): Collection
    {
        return once(function () {
            return $this->resolveFilters()->filter(function (Filter $filter) {
                return $filter->authorizedToSee($this->request);
            })->values();
        });
    }

    /**
     * Get the filters.
     *
     * @return Collection|Filter[]
     */
    public function resolveFilters()
    {
        return collect(array_values($this->filters()));
    }
}
