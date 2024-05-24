<?php

namespace App\Http\Controllers;

use App\Model\Fund;
use App\Model\FundCost;
use App\Model\FundImport;
use App\Services\FundService;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function show()
    {
        $funds = Fund::with(['fundCosts' => function($q) {
            $q->join('import_funds', 'import_funds.id', 'fund_costs.imported_id')
                ->where('import_funds.status', FundImport::APPROBED)->orderByDesc('fund_costs.date');
        }])->get();
        // Get fund code group funds
        $fundIds = $funds->pluck('id');
        $fundCosts = FundService::getFundCost([], ['fund_ids' => $fundIds]);

        return view('funds.introduce', compact('funds', 'fundCosts'));
    }
}
