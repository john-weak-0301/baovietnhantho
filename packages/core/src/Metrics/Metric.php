<?php

namespace Core\Metrics;

use DateInterval;
use Core\Elements\Card;
use Core\Support\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

abstract class Metric extends Card
{
    /**
     * The displayable name of the metric.
     *
     * @var string
     */
    public $name;

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    abstract public function calculate(Request $request);

    /**
     * Calculate the metric's value.
     *
     * @param  Request  $request
     * @return mixed
     *
     * @throws mixed
     */
    public function resolve(Request $request)
    {
        $resolver = function () use ($request) {
            return $this->calculate($request);
        };

        if ($cacheFor = $this->cacheFor()) {
            $cacheFor = is_numeric($cacheFor)
                ? new DateInterval(sprintf('PT%dS', $cacheFor * 60))
                : $cacheFor;

            return Cache::remember(
                $this->getCacheKey($request),
                $cacheFor,
                $resolver
            );
        }

        return $resolver();
    }


    /**
     * Get the appropriate cache key for the metric.
     *
     * @param  Request  $request
     * @return string
     */
    protected function getCacheKey(Request $request)
    {
        return sprintf(
            'app.core.metric.%s.%s.%s.%s',
            $this->uriKey(),
            $request->input('range', 'no-range'),
            $request->input('timezone', 'no-timezone'),
            $request->input('twelveHourTime', 'no-12-hour-time')
        );
    }

    /**
     * Get the displayable name of the metric.
     *
     * @return string
     */
    public function name()
    {
        return $this->name ?: Util::humanize($this);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return null;
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return Str::slug($this->name());
    }

    /**
     * Prepare the metric for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'class'  => get_class($this),
            'name'   => $this->name(),
            'uriKey' => $this->uriKey(),
        ]);
    }
}
