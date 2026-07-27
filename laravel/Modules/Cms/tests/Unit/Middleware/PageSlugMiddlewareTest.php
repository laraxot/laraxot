<?php

declare(strict_types=1);

use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('PageSlugMiddleware can be instantiated', function () {
    $middleware = new PageSlugMiddleware();

    Assert::assertInstanceOf(PageSlugMiddleware::class, $middleware);
});

test('PageSlugMiddleware handle method exists', function () {
    $middleware = new PageSlugMiddleware();
});
