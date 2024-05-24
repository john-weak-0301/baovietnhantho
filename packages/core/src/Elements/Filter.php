<?php

namespace Core\Elements;

use Illuminate\Support\Arr;
use Orchid\Filters\Filter as BaseFilter;

abstract class Filter extends BaseFilter
{
    use AuthorizedToSee;

    /**
     * The value delimiter.
     *
     * @var string
     */
    protected static $delimiter = ', ';

    /**
     * @return string
     */
    public function value(): string
    {
        $params = $this->request->only($this->parameters, []);

        foreach ($this->resolveValues($params) as $key => $value) {
            if (Arr::has($params, $key)) {
                Arr::set($params, $key, $value);
            }
        }

        $values = collect($params)->flatten()->implode(static::$delimiter);

        return $this->name().': '.$values;
    }

    /**
     * Resolve parameters values.
     *
     * @param  array  $parameters
     * @return array
     */
    public function resolveValues(array $parameters)
    {
        return [];
    }
}
