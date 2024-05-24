<?php

namespace App\Dashboard\Controllers;

use App\Model\Ward;
use App\Model\District;
use App\Model\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Orchid\Platform\Http\Controllers\Controller;

class SubdivisionController extends Controller
{
    public function provinces(): JsonResponse
    {
        return new JsonResponse(Province::all()->values());
    }

    public function districts(string $province): JsonResponse
    {
        $province = sprintf('%02s', $province);

        return new JsonResponse(District::province($province)->values());
    }

    public function wards(string $province, string $district): JsonResponse
    {
        $districtInstance = District::getByCode($district, $province);

        return new JsonResponse(Ward::district($districtInstance, $province)->values());
    }
}
