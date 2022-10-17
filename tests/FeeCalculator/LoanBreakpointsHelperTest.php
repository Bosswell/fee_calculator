<?php

declare(strict_types=1);

namespace Tests\PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\FeeCalculator\LoanBreakpointsHelper;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpoints;

class LoanBreakpointsHelperTest extends TestCase
{
    public function testFindTwoNearestBreakpointsByLoanAmountShouldThrowValueErrorWhenNotEnoughBreakpointsProvided(): void
    {
        $loanBreakpoints = new LoanBreakpoints();
        $helper = new LoanBreakpointsHelper();
        $this->expectException(\ValueError::class);
        $helper->findTwoNearestBreakpointsByLoanAmount(1001, $loanBreakpoints);

        $loanBreakpoints->addBreakpoint(1000, 50);
        $this->expectException(\ValueError::class);
        $helper->findTwoNearestBreakpointsByLoanAmount(1001, $loanBreakpoints);
    }

    public function testFindTwoNearestBreakpointsByLoanAmountShouldReturnProperValues(): void
    {
        $loanBreakpoints = new LoanBreakpoints();
        $loanBreakpoints->addBreakpoint(1000, 50);
        $loanBreakpoints->addBreakpoint(2000, 100);
        $loanBreakpoints->addBreakpoint(3000, 150);
        $loanBreakpoints->addBreakpoint(4000, 200);

        $helper = new LoanBreakpointsHelper();
        $nearestBreakpoints = $helper
            ->findTwoNearestBreakpointsByLoanAmount(1500, $loanBreakpoints);

        $this->assertEquals(1000, $nearestBreakpoints['bottomLoanAmount']);
        $this->assertEquals(2000, $nearestBreakpoints['upperLoanAmount']);
        $this->assertEquals(50, $nearestBreakpoints['bottomFee']);
        $this->assertEquals(100, $nearestBreakpoints['upperFee']);

        $nearestBreakpoints = $helper
            ->findTwoNearestBreakpointsByLoanAmount(2000, $loanBreakpoints);

        $this->assertEquals(1000, $nearestBreakpoints['bottomLoanAmount']);
        $this->assertEquals(2000, $nearestBreakpoints['upperLoanAmount']);
        $this->assertEquals(50, $nearestBreakpoints['bottomFee']);
        $this->assertEquals(100, $nearestBreakpoints['upperFee']);
    }
}