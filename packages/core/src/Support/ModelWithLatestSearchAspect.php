<?php

namespace Core\Support;

use Illuminate\Support\Collection;
use Spatie\Searchable\ModelSearchAspect as BaseModelSearchAspect;

class ModelWithLatestSearchAspect extends BaseModelSearchAspect
{
    public function getResults(string $term): Collection
    {
        $query = ($this->model)::query();

        foreach ($this->callsToForward as $callToForward) {
            $this->forwardCallTo($query, $callToForward['method'], $callToForward['parameters']);
        }

        if ($term) {
            $this->addSearchConditions($query, $term);
        }

        if ($this->limit) {
            $query->limit($this->limit);
        }

        return $query->get();
    }
}
