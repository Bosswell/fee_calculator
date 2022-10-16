<?php

declare(strict_types=1);

namespace Tests\PragmaGoTech\Interview;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Math;

class MathTest extends TestCase
{
    public const TERM_12 = [
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

    public const TERM_24 = [
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

    public function testA()
    {
//        $loanBreakpoints = new LoanAmountBreakpoints();
//        foreach (self::TERM_12 as $loan => $fee) {
//            $loanBreakpoints->addBreakpoint($loan, $fee);
//        }

        $value = 2750;

        $diff = [];
        foreach (self::TERM_24 as $loan => $fee) {
            $diff[$loan] = abs($value - $loan);
        }

//        foreach (array_keys(self::TERM_24) as $loan) {
//            $diff[$loan] = abs($value - $loan);
//        }

        asort($diff);
        $diff = array_slice($diff, 0, 2, true);

        $x = array_intersect_key(self::TERM_24, $diff);

        $x = Math::linearInterpolate($value, ...array_keys($x), ...$x);

        print_r(number_format($x, 2));
    }
}