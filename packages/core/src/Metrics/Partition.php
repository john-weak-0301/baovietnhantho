<?php

namespace Core\Metrics;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

abstract class Partition extends Metric
{
    /**
     * The element's component.
     *
     * @var string
     */
    public $component = 'partition-metric';

    /**
     * Return a partition result showing the segments of a count aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $groupBy
     * @param  string|null  $column
     * @return PartitionResult
     */
    public function count($request, $model, $groupBy, $column = null)
    {
        return $this->aggregate($request, $model, 'count', $column, $groupBy);
    }

    /**
     * Return a partition result showing the segments of an average aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @param  string  $groupBy
     * @return PartitionResult
     */
    public function average($request, $model, $column, $groupBy)
    {
        return $this->aggregate($request, $model, 'avg', $column, $groupBy);
    }

    /**
     * Return a partition result showing the segments of a sum aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @param  string  $groupBy
     * @return PartitionResult
     */
    public function sum($request, $model, $column, $groupBy)
    {
        return $this->aggregate($request, $model, 'sum', $column, $groupBy);
    }

    /**
     * Return a partition result showing the segments of a max aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @param  string  $groupBy
     * @return PartitionResult
     */
    public function max($request, $model, $column, $groupBy)
    {
        return $this->aggregate($request, $model, 'max', $column, $groupBy);
    }

    /**
     * Return a partition result showing the segments of a min aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null  $column
     * @param  string  $groupBy
     * @return PartitionResult
     */
    public function min($request, $model, $column, $groupBy)
    {
        return $this->aggregate($request, $model, 'min', $column, $groupBy);
    }

    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  string  $column
     * @param  string  $groupBy
     * @return PartitionResult
     */
    protected function aggregate($request, $model, $function, $column, $groupBy)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
            $column = $column ?? $query->getModel()->getQualifiedKeyName()
        );

        $results = $query->select(
            $groupBy,
            DB::raw("{$function}({$wrappedColumn}) as aggregate")
        )->groupBy($groupBy)->get();

        return $this->result($results->mapWithKeys(function ($result) use ($groupBy) {
            return $this->formatAggregateResult($result, $groupBy);
        })->all());
    }

    /**
     * Format the aggregate result for the partition.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $result
     * @param  string  $groupBy
     * @return array
     */
    protected function formatAggregateResult($result, $groupBy)
    {
        $key = $result->{last(explode('.', $groupBy))};

        return [$key => round($result->aggregate, 0)];
    }

    /**
     * Create a new partition metric result.
     *
     * @param  array  $value
     * @return PartitionResult
     */
    public function result(array $value)
    {
        return new PartitionResult($value);
    }
}
