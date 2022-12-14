<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview;

use InvalidArgumentException;

class Math
{
    public static function linearInterpolate(float $value, int $x1, int $x2, int $y1, int $y2): float
    {
        if ($x1 === $x2) {
            throw new InvalidArgumentException('The arguments x1 and x2 cannot be the same.');
        }

        return $y1 + ($value - $x1) * (($y2 - $y1) / ($x2 - $x1));
    }

    public static function roundUp(float $value, int $base = 5): int
    {
        if ($base < 1) {
            throw new InvalidArgumentException('The base of the rounding must be greater than 0.');
        }

        return $base * (int)ceil($value / $base);
    }
}