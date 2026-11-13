<?php

declare(strict_types=1);

use Modules\Comment\Providers\CommentEngineServiceProvider;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('CommentEngineServiceProvider', function (): void {
    it('boots without errors', function (): void {
        Assert::assertTrue(class_exists(CommentEngineServiceProvider::class));
    });
});
