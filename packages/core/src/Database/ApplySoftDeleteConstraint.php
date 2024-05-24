<?php

namespace Core\Database;

use Illuminate\Database\Eloquent\Builder;

class ApplySoftDeleteConstraint
{
    /**
     * Apply the trashed state constraint to the query.
     *
     * @param  Builder  $query
     * @param  string  $withTrashed
     * @return Builder
     */
    public function __invoke(Builder $query, $withTrashed)
    {
        if ($withTrashed === TrashedStatus::WITH) {
            $query = $query->withTrashed();
        } elseif ($withTrashed === TrashedStatus::ONLY) {
            $query = $query->onlyTrashed();
        }

        return $query;
    }
}
