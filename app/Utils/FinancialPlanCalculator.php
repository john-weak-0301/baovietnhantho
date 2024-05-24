<?php

declare(strict_types=1);

namespace App\Utils;

use Webmozart\Assert\Assert;

class FinancialPlanCalculator
{
    /**
     * //
     *
     * @param  int  $targetAmount
     * @param  int  $estimatedYear
     * @param  int  $currentAccumulationAmount
     * @param  int  $currentDebtAmount
     * @param  int  $inflationRate
     * @return string
     */
    public static function calculate(
        $targetAmount,
        int $estimatedYear = 15,
        $currentAccumulationAmount = 0,
        $currentDebtAmount = 0,
        int $inflationRate = 7
    ): string {
        Assert::numeric($targetAmount);
        Assert::numeric($currentAccumulationAmount);
        Assert::numeric($currentDebtAmount);

        Assert::range($estimatedYear, 1, 50);
        Assert::range($inflationRate, 5, 15);

        $assumedInterestRate = $inflationRate + 2;

        $f2 = (1 + $assumedInterestRate / 100) ** (1 / 12);
        $f3 = ($f2 ** ($estimatedYear * 12) - 1) / (($f2 - 1) / $f2);

        return (string) round(
            ($targetAmount - (($currentAccumulationAmount - $currentDebtAmount) * ($f2 ** ($estimatedYear * 12)))) / $f3,
            -2
        );
    }

    /**
     * //
     *
     * @param  int  $targetAmount
     * @param  int  $currentAge
     * @param  PensionSubscription  $subscription
     * @param  int  $pensionsAgeFrom
     * @param  int  $pensionsAgeTo
     * @param  int  $currentAccumulationAmount
     * @param  int  $currentDebtAmount
     * @param  int  $inflationRate
     * @return string
     */
    public static function calculatePensions(
        $targetAmount,
        int $currentAge,
        PensionSubscription $subscription,
        int $pensionsAgeFrom = 55,
        int $pensionsAgeTo = 80,
        $currentAccumulationAmount = 0,
        $currentDebtAmount = 0,
        int $inflationRate = 7
    ): string {
        Assert::range($inflationRate, 5, 15);

        $subscriptionValue   = $subscription->getValue();
        $assumedInterestRate = $inflationRate + 2;

        $h5 = $pensionsAgeFrom - $currentAge;
        Assert::greaterThan($h5, 0);

        $h6 = $pensionsAgeTo - $pensionsAgeFrom;
        Assert::greaterThan($h6, 0);

        $k1 = (1 + $assumedInterestRate / 100) ** (1 / 12);
        $m1 = (1 + $assumedInterestRate / 100) ** (1 / $subscriptionValue);

        $k2 = ($k1 ** ($h5 * 12) - 1) / (($k1 - 1) / $k1);
        $m2 = (1 - (1 / $m1 ** ($h6 * $subscriptionValue))) / (($m1 - 1) / $m1);

        $n2 = $m2 * $targetAmount;

        return (string) round(
            ($n2 - (($currentAccumulationAmount - $currentDebtAmount) * ($k1 ** ($h5 * 12)))) / $k2,
            -2
        );
    }
}
