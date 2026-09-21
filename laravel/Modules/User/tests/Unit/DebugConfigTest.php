<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('verify database connections config', function () {
    // Verify that the database config is loaded and valid
    $config = config('database.connections');
    expect($config)->toBeArray()
        ->and($config)->not->toBeEmpty();
});
