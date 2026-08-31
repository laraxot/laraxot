<?php

declare(strict_types=1);

use Modules\Xot\Actions\Arrays\DiffAssocRecursiveAction;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Xot\Tests\TestCase::class);

test('diff assoc recursive action works correctly', function () {
    $arr1 = [
        'a' => ['id' => 1, 'name' => 'Test'],
        'b' => ['id' => 2, 'name' => 'Test 2'],
    ];
    $arr2 = [
        'a' => ['id' => 1, 'name' => 'Test'],
    ];

    $action = app(DiffAssocRecursiveAction::class);
    $result = $action->execute($arr1, $arr2);

    Assert::assertSame(['id' => 2, 'name' => 'Test 2'], $result);

    Assert::assertArrayHasKey('b', $result);
});

test('diff assoc recursive action handles numeric strings', function () {
    $arr1 = [
        'a' => ['id' => '1', 'name' => 'Test'],
    ];
    $arr2 = [
        'a' => ['id' => 1, 'name' => 'Test'],
    ];

    $action = app(DiffAssocRecursiveAction::class);
    $result = $action->execute($arr1, $arr2);

    // fixType converts '1' to 1, so they should be equal and diff should be empty
    Assert::assertEmpty($result);
});
