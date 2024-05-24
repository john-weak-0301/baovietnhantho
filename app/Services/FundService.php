<?php
namespace App\Services;
use App\Model\Fund;
use App\Model\FundCost;
use App\Model\FundImport;

class FundService
{
    public static function getFundCost($input = [], $options = []) {
        $fundCost = FundCost::select('*');
        $fundCost = $fundCost->join('import_funds', 'import_funds.id', 'fund_costs.imported_id')
            ->where('import_funds.status', FundImport::APPROBED)->orderBy('fund_costs.date');
        if (!empty($input['from_date'])) {
            $fundCost = $fundCost->where('fund_costs.date', '>=', $input['from_date']);
        }
        if (!empty($input['to_date'])) {
            $fundCost = $fundCost->where('fund_costs.date', '<=', $input['to_date']);
        }
        $fundCost = $fundCost->groupBy('fund_costs.id')->groupBy('fund_costs.date')->paginate(10);
        if (!empty($options['fund_ids'])) {
            foreach($fundCost->items() as &$item) {
                foreach($options['fund_ids'] as $fundId) {
                    $item["fund{$fundId}"] = Fund::where('funds.id', $fundId)->select([
                        'funds.name', 'fund_costs.value'
                    ])
                    ->join('fund_costs', 'fund_costs.quy_lkdv_id', 'funds.id')
                    ->join('import_funds', 'import_funds.id', 'fund_costs.imported_id')
                    ->where('import_funds.status', FundImport::APPROBED)
                    ->where('fund_costs.date', $item->date)->first();
                }
            }
        }
        return $fundCost;
    }
}
