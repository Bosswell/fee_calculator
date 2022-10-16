<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\Model;

use PragmaGoTech\Interview\FeeCalculator\Enum\Term;
use Error;

final class LoanAmountBreakpointsList
{
    /** @var LoanAmountBreakpoints[] */
    private array $loanBreakpoints;

    public function add(LoanAmountBreakpoints $loanBreakpoints, Term $term): void
    {
        $this->loanBreakpoints[$term->value] = $loanBreakpoints;
    }

    public function get(Term $term): LoanAmountBreakpoints
    {
        if (!isset($this->loanBreakpoints[$term->value])) {
            throw new Error(sprintf('Loan breakpoints for term [ %s ] does not exists.', $term->name));
        }

        return $this->loanBreakpoints[$term->value];
    }

    public function has(Term $term): bool
    {
        return isset($this->loanBreakpoints[$term->value]);
    }
}