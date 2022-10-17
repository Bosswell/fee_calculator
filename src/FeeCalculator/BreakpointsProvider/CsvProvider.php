<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PragmaGoTech\Interview\FeeCalculator\Model\LoanAmountBreakpoints;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanAmountBreakpointsList;
use PragmaGoTech\Interview\FeeCalculator\Enum\Term;
use SplFileObject;
use Error;

final class CsvProvider implements ProviderInterface
{
    private LoanAmountBreakpointsList $loanBreakpointsList;

    public function __construct(private readonly string $csvFileName)
    {}

    public function getLoanBreakpointsList(): LoanAmountBreakpointsList
    {
        if (!isset($this->loanBreakpointsList)) {
            $this->createLoanBreakpointsList();
        }

        return $this->loanBreakpointsList;
    }

    private function createLoanBreakpointsList(): void
    {
        if (!file_exists($this->csvFileName)) {
            throw new Error(sprintf('File "%s" does not exist. Provide valid path to loan amount breakpoints file.', $this->csvFileName));
        }

        $csvFileObject = new SplFileObject($this->csvFileName);
        $csvFileObject->setFlags(SplFileObject::READ_CSV);

        $normalizedHeader = preg_replace('/\s+/', '', $csvFileObject->fgets());

        if ($normalizedHeader !== 'loan,fee,term') {
            throw new Error('Csv file with loan amount breakpoints does not follow formula. Use "loan, fee, term" headers.');
        }

        $loanBreakpointsList = new LoanAmountBreakpointsList();

        foreach ($csvFileObject as [$loan, $fee, $term]) {
            if (!is_numeric($loan)) {
                continue;
            }

            $term = Term::from((int)$term);

            if (!$loanBreakpointsList->has($term)) {
                $loanBreakpointsList->add(new LoanAmountBreakpoints(), $term);
            }

            $loanBreakpointsList
                ->get($term)
                ->addBreakpoint((int)$loan, (int)$fee);
        }

        $this->loanBreakpointsList = $loanBreakpointsList;
    }
}