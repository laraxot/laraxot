<?php

declare(strict_types=1);
<<<<<<< HEAD
use Modules\Xot\Tests\TestCase;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

>>>>>>> 77b1e2748 (first)
test('XotBasePest assertArray normalizza chiavi int in stringhe', function (): void {
    $normalized = XotBasePest::assertArray([0 => 'a', 'b' => 'c']);

    Assert::assertSame(['0' => 'a', 'b' => 'c'], $normalized);
});

test('XotBasePest assertArray accetta array vuoto', function (): void {
    Assert::assertSame([], XotBasePest::assertArray([]));
});
