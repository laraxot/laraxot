<?php

declare(strict_types=1);

use Modules\Comment\Support\CommentSanitizer;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('CommentSanitizer strips script tags and keeps safe elements', function (): void {
    $sanitizer = new CommentSanitizer;

    $result = $sanitizer->sanitize('<p>Hello</p><script>alert(1)</script><strong>world</strong>');

    Assert::assertStringContainsString('<p>Hello</p>', $result);
    Assert::assertStringContainsString('<strong>world</strong>', $result);
    Assert::assertStringNotContainsString('<script>', $result);
});

test('CommentSanitizer allows anchor href attributes from config', function (): void {
    $sanitizer = new CommentSanitizer;

    $result = $sanitizer->sanitize('<a href="https://example.com" target="_blank" rel="noopener">link</a>');

    Assert::assertStringContainsString('href="https://example.com"', $result);
});
