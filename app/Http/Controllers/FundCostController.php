<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\FundService;
use App\Model\Fund;
use App\Model\FundImport;

class FundCostController extends Controller
{
    public function search(Request $request)
    {
        $input = $request->all();
        $funds = Fund::with(['fundCosts' => function($q) use ($input) {
            $q = $q->join('import_funds', 'import_funds.id', 'fund_costs.imported_id')
                ->where('import_funds.status', FundImport::APPROBED);
            if (!empty($input['from_date'])) {
                $q = $q->where('fund_costs.date', '>=', $input['from_date']);
            }
            if (!empty($input['to_date'])) {
                $q = $q->where('fund_costs.date', '<=', $input['to_date']);
            }
            $q = $q->orderByDesc('fund_costs.date');
        }]);
        $funds = $funds->get();
        // Get fund code group funds
        $fundIds = $funds->pluck('id');
        $fundCosts = FundService::getFundCost($input, ['fund_ids' => $fundIds]);
        return response()->json([
            'funds' => $funds,
            'fund_costs' => $fundCosts,
        ]);
    }
}
