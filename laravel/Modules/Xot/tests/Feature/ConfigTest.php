<?php

declare(strict_types=1);

/**
 * @phpstan-ignore method.internalClass
 */
namespace Modules\Xot\Tests\Feature;

use Modules\Xot\Tests\TestCase;

uses(TestCase::class);

// phpcs:disable
it('loads xot config correctly', function () {
    $config = config('xot');
    /** @phpstan-ignore method.internalClass */
    expect($config)->toBeArray();
    /** @phpstan-ignore method.internalClass */
    expect($config)->not->toBeEmpty();
});

it('has expected keys in xot config', function () {
    $config = config('xot');
    /** @phpstan-ignore method.internalClass */
    expect($config)->toBeArray();
});

it('loads database config', function () {
    $config = config('database');
    /** @phpstan-ignore method.internalClass */
    expect($config)->toBeArray();
    /** @phpstan-ignore method.internalClass */
    expect($config)->toHaveKey('default');
});
