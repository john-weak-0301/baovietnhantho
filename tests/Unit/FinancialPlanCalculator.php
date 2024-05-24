<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Utils\PensionSubscription;
use App\Utils\FinancialPlanCalculator as Calculator;

class FinancialPlanCalculator extends TestCase
{
    public function testCalculate()
    {
        $amount = Calculator::calculate(500000000, 2, 1000000, 0, 5);
        $this->assertEquals($amount, 19356400);

        $amount = Calculator::calculate(500000000, 15, 20000000, 0, 7);
        $this->assertEquals($amount, 1156700);

        $amount = Calculator::calculate(0, 15, 0, 0, 7);
        $this->assertEquals('0', $amount);
    }

    /**
     * @dataProvider getCalc2TestProvider
     */
    public function testCalculate2($type, $assertValue)
    {
        $amount = Calculator::calculatePensions(8000000, 35, $type, 55, 90, 1000000, 0, 6);
        $this->assertEquals($amount, $assertValue);
    }

    public function getCalc2TestProvider()
    {
        return [
            [PensionSubscription::MONTHLY(), '2029300'],
            [PensionSubscription::QUARTERLY(), '675400'],
            [PensionSubscription::HALF_YEARLY(), '336900'],
            [PensionSubscription::YEARLY(), '167700'],
        ];
    }

    /**
     * @dataProvider getCalc3TestProvider
     */
    public function testCalculate3($type, $assertValue)
    {
        $amount = Calculator::calculatePensions(8000000, 35, $type, 55, 80, 20000000, 0, 6);
        $this->assertEquals($amount, $assertValue);
    }

    public function getCalc3TestProvider()
    {
        return [
            [PensionSubscription::MONTHLY(), '1703400'],
            [PensionSubscription::QUARTERLY(), '463300'],
            [PensionSubscription::HALF_YEARLY(), '153200'],
            [PensionSubscription::YEARLY(), '-1700'],
        ];
    }
}
