<?php

namespace Core\Screens;

use Core\Metrics\Metric;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

trait InteractsWithMetrics
{
    /**
     * Get the specified metric's value.
     *
     * @param  ScreenRequest  $request
     * @return JsonResponse
     */
    public function handleMetric(ScreenRequest $request): JsonResponse
    {
        $metric = $this
            ->availableMetrics($request)
            ->first(function (Metric $metric) use ($request) {
                return $request->input('metric') === $metric->uriKey();
            }) ?: abort(404);

        /* @var Metric $metric */
        return response()->json([
            'value' => $metric->resolve($request),
        ]);
    }

    /**
     * Get all of the possible metrics for the request.
     *
     * @param  Request  $request
     * @return Collection
     */
    protected function availableMetrics(Request $request = null): Collection
    {
        return $this->availableCards($request)->whereInstanceOf(Metric::class);
    }
}
