<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpoints;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanBreakpointsList;
use PragmaGoTech\Interview\FeeCalculator\Enum\Term;
use SplFileObject;
use Error;

final class CsvProvider implements ProviderInterface
{
    private LoanBreakpointsList $loanBreakpointsList;

    public function __construct(private readonly string $csvFileName)
    {}

    /**
     * @throws Error
     */
    public function getLoanBreakpointsList(): LoanBreakpointsList
    {
        if (!isset($this->loanBreakpointsList)) {
            $this->createLoanBreakpointsList();
        }

        return $this->loanBreakpointsList;
    }

    /**
     * @throws Error
     */
    private function createLoanBreakpointsList(): void
    {
        $loanBreakpointsList = new LoanBreakpointsList();

        foreach ($this->getCsvFileObject() as [$loan, $fee, $term]) {
            if (!is_numeric($loan)) {
                continue;
            }

            $term = Term::from((int)$term);
            if (!$loanBreakpointsList->has($term)) {
                $loanBreakpointsList->add(new LoanBreakpoints(), $term);
            }

            $loanBreakpointsList
                ->get($term)
                ->addBreakpoint((int)$loan, (int)$fee);
        }

        $this->loanBreakpointsList = $loanBreakpointsList;
    }

    /**
     * @throws Error
     */
    private function getCsvFileObject(): SplFileObject
    {
        if (!file_exists($this->csvFileName)) {
            throw new Error(sprintf('The file "%s" does not exist. Provide a valid path to the loan breakpoints file.', $this->csvFileName));
        }

        $csvFileObject = new SplFileObject($this->csvFileName);
        $csvFileObject->setFlags(SplFileObject::READ_CSV);

        $normalizedHeader = preg_replace('/\s+/', '', $csvFileObject->fgets());
        if ($normalizedHeader !== 'loan,fee,term') {
            throw new Error('The CSV file with loan breakpoints does not match the pattern. Provide the CSV file with the headers "loan, fee, term".');
        }

        return $csvFileObject;
    }
}