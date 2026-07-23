<?php

declare(strict_types=1);

use Modules\Comment\Actions\Comment\SanitizeCommentTextAction;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('SanitizeCommentTextAction strips script tags and keeps safe elements', function (): void {
    $sanitizer = new SanitizeCommentTextAction;

    $result = $sanitizer->execute('<p>Hello</p><script>alert(1)</script><strong>world</strong>');

    Assert::assertStringContainsString('<p>Hello</p>', $result);
    Assert::assertStringContainsString('<strong>world</strong>', $result);
    Assert::assertStringNotContainsString('<script>', $result);
});

test('SanitizeCommentTextAction allows anchor href attributes from config', function (): void {
    $sanitizer = new SanitizeCommentTextAction;

    $result = $sanitizer->execute('<a href="https://example.com" target="_blank" rel="noopener">link</a>');

    Assert::assertStringContainsString('href="https://example.com"', $result);
});
