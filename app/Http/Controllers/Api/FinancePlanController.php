<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Model\Product;
use App\Utils\FinancialPlanCalculator;
use App\Utils\PensionSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Throwable;

class FinancePlanController
{
    public function __invoke(Request $request)
    {
        $attributes = $this->validate($request);

        $objectives = config('press.objectives');
        abort_if(!array_key_exists($attributes['targetName'], $objectives), 404);

        try {
            $amount = $this->calculate($attributes);

            if ((int) $amount <= 0) {
                $amount  = 0;
                $message = 'Khoản tích lũy hiện tại đã đủ để đáp ứng mục tiêu tích lũy trong tương lai';
            } else {
                $message = 'OK';
            }
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => config('app.debug') ? $e->getMessage() : 'Vui lòng nhập số liệu hợp lệ!',
            ], 500);
        } catch (Throwable $e) {
            report($e);
            abort(500, __('Hệ thống gặp sự cô trong tính toán, vui lòng thử lại hoặc nhập số liệu hợp lệ.'));
        }

        $products = $this->getSuggestedProducts(
            $objectives[$attributes['targetName']]['related'] ?? []
        );

        return response()->json([
            'message' => trim($message ?? ''),
            'data'    => [
                'amount'   => number_format($amount, 0, ',', '.'),
                'products' => ProductResource::collection($products),
            ],
        ]);
    }

    /**
     * Perform validate request.
     *
     * @param  Request  $request
     * @return array
     */
    protected function validate(Request $request)
    {
        return $request->validate([
            'targetName'                => 'required|string',
            'targetAmount'              => 'required|int|min:1000000|max:100000000000',
            'currentAccumulationAmount' => 'nullable|int|min:0|max:100000000000',
            'currentDebtAmount'         => 'nullable|int|min:0|max:100000000000',
            'inflationRate'             => 'required|int|min:5|max:20',
            'estimatedYear'             => 'required_unless:targetName,huu-tri|int|min:1|max:50',
            'currentAge'                => 'required_if:targetName,huu-tri|int|min:25|max:60',
            'pensionsAgeFrom'           => 'required_if:targetName,huu-tri|int|min:35|max:75',
            'pensionsAgeTo'             => 'required_if:targetName,huu-tri|int|min:55|max:99',
            'subscription'              => [
                'required_if:targetName,huu-tri',
                Rule::in(array_map('strtolower', PensionSubscription::keys())),
            ],
        ]);
    }

    /**
     * Perform calculate.
     *
     * @param  array  $attributes
     * @return string
     */
    protected function calculate(array $attributes)
    {
        if ($attributes['targetName'] === 'huu-tri') {
            $subscription = strtoupper($attributes['subscription']);

            return FinancialPlanCalculator::calculatePensions(
                $attributes['targetAmount'],
                $attributes['currentAge'],
                PensionSubscription::$subscription(),
                $attributes['pensionsAgeFrom'],
                $attributes['pensionsAgeTo'],
                $attributes['currentAccumulationAmount'] ?? 0,
                $attributes['currentDebtAmount'] ?? 0,
                $attributes['inflationRate']
            );
        }

        return FinancialPlanCalculator::calculate(
            $attributes['targetAmount'],
            $attributes['estimatedYear'],
            $attributes['currentAccumulationAmount'] ?? 0,
            $attributes['currentDebtAmount'] ?? 0,
            $attributes['inflationRate']
        );
    }

    /**
     * Query related products by name.
     *
     * @param  array  $names
     * @return array|null
     */
    protected function getSuggestedProducts(array $names)
    {
        return tap(Product::query(), function (Builder $query) use ($names) {
            $query->where('status', 'publish');

            $callback = function ($name) {
                return function (Builder $subQuery) use ($name) {
                    if (is_numeric($name)) {
                        $subQuery->where('id', $name);
                    } else {
                        $subQuery
                            ->where('slug', 'like', "%$name%")
                            ->orWhere('title', 'like', "%$name%");
                    }
                };
            };

            foreach ($names as $i => $name) {
                if ($i === 0) {
                    $query->where($callback($name));
                } else {
                    $query->orWhere($callback($name));
                }
            }
        })->inRandomOrder()->take(2)->get();
    }
}
