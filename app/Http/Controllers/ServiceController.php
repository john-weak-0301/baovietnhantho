<?php

namespace App\Http\Controllers;

use App\Model\Service;
use App\Model\Fund;
use App\Model\FundCost;
use App\Model\FundImport;
use App\Model\ServiceCategory;
use App\Services\FundService;
use App\Http\Resources\ServiceResources;
use App\SEO\SEOConfigure;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $withServices = function ($query) {
            $query->orderByDesc('order');
        };

        $categories = ServiceCategory::query()
            ->with(['service' => $withServices])
            ->orderByDesc('order')
            ->get();

        return view('services', [
            'categories' => $categories,
            'title'      => 'Dịch vụ khách hàng',
        ]);
    }

    public function show($slug)
    {
        $service = Service::whereSlug($slug)->firstOrFail();

        SEOConfigure::config($service);

        return view('service', compact('service'));
    }

    public function json(Request $request)
    {
        $title = $request->get('title');

        $services = Service::query()
            ->where('title', 'like', '%'.$title.'%')
            ->get();

        return ServiceResources::collection($services);
    }
}
