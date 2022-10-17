<?php

declare(strict_types=1);

namespace Tests\PragmaGoTech\Interview;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Math;

class MathTest extends TestCase
{
    public function testRoundUpShouldThrowExceptionWhenBaseIsLowerThenOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Math::roundUp(2.1, 0);
    }

    public function testRoundUpShouldReturnProperValues(): void
    {
        $this->assertEquals(5, Math::roundUp(3.3));
        $this->assertEquals(10, Math::roundUp(5.9));
        $this->assertEquals(10, Math::roundUp(5.1));
    }

    public function testLinearInterpolateShouldThrowExceptionWhenX1AndX2ParametersAreTheSame(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Math::linearInterpolate(10, 1, 1, 2, 3);
    }

    public function testLinearInterpolateShouldShouldReturnProperValues(): void
    {
        $this->assertEquals(6, Math::linearInterpolate(4, 3, 5, 4, 8));
        $this->assertEquals(5.5, Math::linearInterpolate(4, 3, 5, 5, 6));
    }
}