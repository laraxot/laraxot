<?php

declare(strict_types=1);

use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('CommentEngineServiceProvider', function (): void {
    it('boots without errors', function (): void {
        Assert::assertTrue(class_exists(\Modules\Comment\Providers\CommentEngineServiceProvider::class));
    });
});
