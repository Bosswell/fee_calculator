<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\Model;

use PragmaGoTech\Interview\FeeCalculator\Enum\Term;

final class LoanProposal
{
    private Term $term;
    private float $amount;

    public function __construct(Term $term, float $amount)
    {
        $this->term = $term;
        $this->amount = $amount;
    }

    public function term(): Term
    {
        return $this->term;
    }

    public function amount(): float
    {
        return $this->amount;
    }
}
