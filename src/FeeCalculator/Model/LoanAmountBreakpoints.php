<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\Model;

final class LoanAmountBreakpoints
{
    private array $map = [];

    public function addBreakpoint(int $loan, int $fee): void
    {
        $this->map[$loan] = $fee;
    }

    public function all(): array
    {
        return $this->map;
    }
}