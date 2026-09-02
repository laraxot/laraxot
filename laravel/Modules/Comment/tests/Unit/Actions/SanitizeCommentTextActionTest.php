<?php

declare(strict_types=1);

use Modules\Comment\Actions\SanitizeCommentTextAction;
use Modules\Comment\Tests\UnitTestCase;

uses(UnitTestCase::class);

it('returns a sanitized string', function (): void {
    $action = app(SanitizeCommentTextAction::class);

    $result = $action->execute('<p>Hello <script>alert("x")</script> world</p>');

expect($result)->and($result)->not->toContain('<script>')->not->toBeEmpty();
});

it('returns empty string for empty input', function (): void {
    $action = app(SanitizeCommentTextAction::class);

    expect($action->execute(''))->toBe('');
});
