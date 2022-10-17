<?php

declare(strict_types=1);

namespace Tests\PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\FeeCalculator\Enum\Term;
use PragmaGoTech\Interview\FeeCalculator\FeeCalculator;
use PragmaGoTech\Interview\FeeCalculator\LoanBreakpointsHelper;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpoints;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpointsList;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanProposal;

class FeeCalculatorTest extends TestCase
{
    public function testCalculateShouldThrowValueErrorIfProvidedLoanProposalAmountIsOutOfRange(): void
    {
        $breakpoints = new LoanBreakpoints();
        $breakpoints->addBreakpoint(1000, 10);
        $breakpoints->addBreakpoint(2000, 20);
        $breakpoints->addBreakpoint(3000, 50);

        $list = new LoanBreakpointsList();
        $list->add($breakpoints, Term::TERM_12);

        $feeCalculator = new FeeCalculator($list, new LoanBreakpointsHelper());
        $this->expectException(\ValueError::class);
        $feeCalculator->calculate(new LoanProposal(Term::TERM_12, 999));

        $this->expectException(\ValueError::class);
        $feeCalculator->calculate(new LoanProposal(Term::TERM_12, 20001));
    }
}