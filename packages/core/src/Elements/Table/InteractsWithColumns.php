<?php

namespace Core\Elements\Table;

use Illuminate\Support\Collection;

trait InteractsWithColumns
{
    /**
     * Define the table columns.
     *
     * @return Column[]
     */
    abstract public function columns(): array;

    /**
     * Get the columns that are available for the given request.
     *
     * @return Collection|Column[]
     */
    public function availableColumns(): Collection
    {
        return once(function () {
            return $this->resolveColumns()->filter(function (Column $column) {
                return $column->authorize($this->request);
            })->values();
        });
    }

    /**
     * Get the columns for the given request.
     *
     * @return Collection|Column[]
     */
    public function resolveColumns(): Collection
    {
        return collect(array_values($this->columns()));
    }
}
