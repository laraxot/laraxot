<?php

declare(strict_types=1);

use PHPUnit\Framework\Assert;

function sum(int|float $a, int|float $b): int|float
{
    return $a + $b;
}

it('sum', function (): void {
    $result = sum(1, 2);

    Assert::assertSame(3, $result);
});
