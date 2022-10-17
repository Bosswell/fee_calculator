<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator;

use JetBrains\PhpStorm\ArrayShape;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpoints;
use ValueError;

final class LoanBreakpointsHelper
{
    #[ArrayShape([
        'bottomLoanAmount' => 'int',
        'upperLoanAmount' => 'int',
        'bottomFee' => 'int',
        'upperFee' => 'int',
    ])]
    public function findTwoNearestBreakpointsByLoanAmount(float $loanAmount, LoanBreakpoints $loanBreakpoints): array
    {
        $breakpoints = $loanBreakpoints->all();

        if (count($breakpoints) < 2) {
            throw new ValueError('Specify at least two loan breakpoints.');
        }

        $distances = [];
        foreach (array_keys($breakpoints) as $loanBreakpoint) {
            $distances[$loanBreakpoint] = abs($loanBreakpoint - $loanAmount);
        }
        asort($distances);

        $nearestBreakpoints = array_intersect_key(
            $breakpoints,
            array_slice($distances, 0, 2, true)
        );

        return array_combine(
            ['bottomLoanAmount', 'upperLoanAmount', 'bottomFee', 'upperFee'],
            [...array_keys($nearestBreakpoints), ...$nearestBreakpoints]
        );
    }
}