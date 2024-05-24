<?php

namespace App\Http\Controllers\Api;

use App\Model\Counselor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CounselorsController
{
    public function __invoke(Request $request)
    {
        $applyQuery = function (Builder $builder) use ($request) {
            return $builder->where('gender', $request->gender ?? 'men')
                ->where('province_id', $request->province ?? 0)
                ->orderByDesc('rate_value')
                ->latest('updated_at')
                ->inRandomOrder();
        };

        $counselors = Counselor::query()
            ->tap($applyQuery)
            ->where('district_id', $request->district ?? 0)
            ->where('avatar', '<>', '')
            ->take(4)
            ->get();

        /*if (blank($counselors) || $counselors->count() < 4) {
            $counselors1 = Counselor::query()
                ->tap($applyQuery)
                ->when(!blank($counselors), function (Builder $builder) use ($counselors) {
                    $builder->whereNotIn('id', $counselors->pluck('id'));
                })
                ->take(4 - $counselors->count())
                ->get();

            $counselors = $counselors
                ->merge($counselors1)
                ->unique('id');
        }*/

        return $counselors->sortByDesc('rate_value');
    }
}
