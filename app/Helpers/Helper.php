<?php
if (! function_exists('getNewFundCost')) {
    function getNewFundCost($fund)
    {
        if (empty($fund)) {
            return 0;
        }
        if (empty($fund->fundCosts)) {
            return 0;
        }
        $fundCosts = $fund->fundCosts->toArray();
        if (empty($fundCosts)) {
            return 0;
        }
        return $fund->fundCosts[0]['value'];
    }
}
