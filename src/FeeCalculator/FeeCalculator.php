<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator;

use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpointsList;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanProposal;
use PragmaGoTech\Interview\Math;
use ValueError;
use Error;

class FeeCalculator
{
    private const MIN_LOAN_AMOUNT = 1000;
    private const MAX_LOAN_AMOUNT = 20_000;

    public function __construct(
        private readonly LoanBreakpointsList   $loanBreakpointsList,
        private readonly LoanBreakpointsHelper $loanBreakpointsHelper
    ) {}

    /**
     * @throws ValueError|Error
     */
    public function calculate(LoanProposal $loanProposal): int
    {
        if ($loanProposal->amount() < self::MIN_LOAN_AMOUNT || $loanProposal->amount() > self::MAX_LOAN_AMOUNT) {
            throw new ValueError(
                sprintf('The loan amount must be between %d and %d PLN.', self::MIN_LOAN_AMOUNT, self::MAX_LOAN_AMOUNT)
            );
        }

        $breakpoints = $this->loanBreakpointsList->get($loanProposal->term());
        $nearestLoans = $this
            ->loanBreakpointsHelper
            ->findTwoNearestBreakpointsByLoanAmount($loanProposal->amount(), $breakpoints);

        $fee = Math::linearInterpolate(
            $loanProposal->amount(),
            $nearestLoans['bottomLoanAmount'],
            $nearestLoans['upperLoanAmount'],
            $nearestLoans['bottomFee'],
            $nearestLoans['upperFee']
        );

        return Math::roundUp($fee);
    }
}