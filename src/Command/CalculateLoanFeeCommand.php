<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Command;

use PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider\CsvProvider;
use PragmaGoTech\Interview\FeeCalculator\Enum\Term;
use PragmaGoTech\Interview\FeeCalculator\FeeCalculatorFacade;
use PragmaGoTech\Interview\FeeCalculator\Model\LoanProposal;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CalculateLoanFeeCommand extends Command
{
    private readonly FeeCalculatorFacade $feeCalculatorFacade;

    public function __construct(FeeCalculatorFacade $feeCalculatorFacade)
    {
        parent::__construct('app:calculate:fee');

        $this->feeCalculatorFacade = $feeCalculatorFacade;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('loanAmount', InputArgument::REQUIRED, 'What loan value are you interested in?')
            ->addArgument('term', InputArgument::REQUIRED, 'For how many months would you like to take out a loan? [12, 24]')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $loanAmount = (float)$input->getArgument('loanAmount');
            $term = (int)$input->getArgument('term');

            $fee = $this->feeCalculatorFacade->calculateFee(
                new LoanProposal(Term::from($term), $loanAmount),
                CsvProvider::class
            );
            $output->writeln("<info>Your loan fee is {$fee} PLN</info>");

        } catch (Throwable $ex) {
            $output->writeln(sprintf('<error>%s</error>', $ex->getMessage()));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}