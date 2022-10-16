<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator;

use PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider\ProviderCollection;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanProposal;

final class FeeCalculatorFacade
{
    public function __construct(
        private readonly LoanBreakpointsHelper $loanBreakpointsHelper,
        private readonly ProviderCollection $providerCollection
    ) {}

    public function calculateFee(LoanProposal $loanProposal, string $provider): int
    {
        $loanBreakpointsList = $this
            ->providerCollection
            ->get($provider)
            ->getLoanBreakpointsList();

        $feeCalculator = new FeeCalculator($loanBreakpointsList, $this->loanBreakpointsHelper);

        return $feeCalculator->calculate($loanProposal);
    }
}