<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpointsList;

interface ProviderInterface
{
    public function getLoanBreakpointsList(): LoanBreakpointsList;
}