<?php

declare(strict_types=1);

namespace Tests\PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\FeeCalculator\BreakpointsProvider\CsvProvider;
use PragmaGoTech\Interview\FeeCalculator\Enum\Term;

class CsvProviderTest extends TestCase
{
    private const TERM_12 = [
        1000   => 50,
        2000   => 90,
        3000   => 90,
        4000   => 115,
        5000   => 100,
        6000   => 120,
        7000   => 140,
        8000   => 160,
        9000   => 180,
        10000  => 200,
        11000  => 220,
        12000  => 240,
        13000  => 260,
        14000  => 280,
        15000  => 300,
        16000  => 320,
        17000  => 340,
        18000  => 360,
        19000  => 380,
        20000  => 400,
    ];

    private const TERM_24 = [
        1000   => 70,
        2000   => 100,
        3000   => 120,
        4000   => 160,
        5000   => 200,
        6000   => 240,
        7000   => 280,
        8000   => 320,
        9000   => 360,
        10000  => 400,
        11000  => 440,
        12000  => 480,
        13000  => 520,
        14000  => 560,
        15000  => 600,
        16000  => 640,
        17000  => 680,
        18000  => 720,
        19000  => 760,
        20000  => 800,
    ];

    private const CSV_BREAKPOINTS = 'loan,fee,term
                                     1000,50,12
                                     2000,90,12
                                     3000,90,12
                                     4000,115,12
                                     5000,100,12
                                     6000,120,12
                                     7000,140,12
                                     8000,160,12
                                     9000,180,12
                                     10000,200,12
                                     11000,220,12
                                     12000,240,12
                                     13000,260,12
                                     14000,280,12
                                     15000,300,12
                                     16000,320,12
                                     17000,340,12
                                     18000,360,12
                                     19000,380,12
                                     20000,400,12
                                     1000,70,24
                                     2000,100,24
                                     3000,120,24
                                     4000,160,24
                                     5000,200,24
                                     6000,240,24
                                     7000,280,24
                                     8000,320,24
                                     9000,360,24
                                     10000,400,24
                                     11000,440,24
                                     12000,480,24
                                     13000,520,24
                                     14000,560,24
                                     15000,600,24
                                     16000,640,24
                                     17000,680,24
                                     18000,720,24
                                     19000,760,24
                                     20000,800,24';

    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('data');
    }

    public function testGetLoanBreakpointsListShouldThrowErrorIfCsvFileWithBreakpointsCannotBeFound(): void
    {
        $csvProvider = new CsvProvider('hello.csv');

        $this->expectError();
        $this->expectErrorMessage('File "hello.csv" does not exist. Provide valid path to loan amount breakpoints file.');
        $csvProvider->getLoanBreakpointsList();
    }

    public function testGetLoanBreakpointsListShouldReturnListOfBreakpointsWhenCsvFileContainValidData(): void
    {
        $file = vfsStream::newFile('breakpoints.csv')
            ->withContent(self::CSV_BREAKPOINTS)
            ->at($this->root);

        $csvProvider = new CsvProvider($file->url());
        $breakpoints = $csvProvider->getLoanBreakpointsList();

        $this->assertTrue($breakpoints->has(Term::TERM_12));
        $this->assertTrue($breakpoints->has(Term::TERM_24));

        $term12 = $breakpoints->get(Term::TERM_12);
        $term24 = $breakpoints->get(Term::TERM_24);

        $this->assertEqualsCanonicalizing(self::TERM_12, $term12->all());
        $this->assertEqualsCanonicalizing(self::TERM_24, $term24->all());
    }

    public function testGetLoanBreakpointsListShouldThrowErrorWhenCsvFileDoesNotFollowFormulaInHeader(): void
    {
        $file1 = vfsStream::newFile('breakpoints1.csv')
            ->withContent('loan, wrong, fee')
            ->at($this->root);

        $file2 = vfsStream::newFile('breakpoints2.csv')
            ->withContent('loan, fee, term, hello')
            ->at($this->root);

        $file3 = vfsStream::newFile('breakpoints3.csv')
            ->withContent('loan, term, fee')
            ->at($this->root);

        $csvProvider = new CsvProvider($file1->url());
        $this->expectError();
        $this->expectErrorMessage('Csv file with loan amount breakpoints does not follow formula. Use "loan, fee, term" headers.');
        $csvProvider->getLoanBreakpointsList();

        $csvProvider = new CsvProvider($file2->url());
        $this->expectError();
        $this->expectErrorMessage('Csv file with loan amount breakpoints does not follow formula. Use "loan, fee, term" headers.');
        $csvProvider->getLoanBreakpointsList();

        $csvProvider = new CsvProvider($file3->url());
        $this->expectError();
        $this->expectErrorMessage('Csv file with loan amount breakpoints does not follow formula. Use "loan, fee, term" headers.');
        $csvProvider->getLoanBreakpointsList();
    }
}