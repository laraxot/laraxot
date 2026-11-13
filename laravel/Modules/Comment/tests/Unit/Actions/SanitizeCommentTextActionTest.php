<?php

declare(strict_types=1);

use Modules\Comment\Actions\SanitizeCommentTextAction;
use Modules\Comment\Tests\UnitTestCase;

uses(UnitTestCase::class);

beforeEach(function (): void {
    config(['comments.mentions.enabled' => false]);
});

it('returns a sanitized string', function (): void {
    $action = app(SanitizeCommentTextAction::class);

    /** @var string $result */
    $result = $action->execute('<p>Hello <script>alert("x")</script> world</p>');

    $this->assertNotNull($result);
    expect($result)->not()->toContain('<script>');
    expect($result)->not()->toBeEmpty();
});

it('returns empty string for empty input', function (): void {
    $action = app(SanitizeCommentTextAction::class);

    /** @var string $result */
    $result = $action->execute('');
    expect($result)->toBe('');
});
