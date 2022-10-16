<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PragmaGoTech\Interview\FeeCalculator\Model\LoanAmountBreakpointsList;

interface ProviderInterface
{
    public function getLoanBreakpointsList(): LoanAmountBreakpointsList;
}