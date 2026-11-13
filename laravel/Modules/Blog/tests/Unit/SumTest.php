<?php

declare(strict_types=1);

function sum(int|float $a, int|float $b): int|float
{
    return $a + $b;
}

it('sum', function (): void {
    $result = sum(1, 2);

    expect($result)->toBe(3);
});
