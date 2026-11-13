<?php

declare(strict_types=1);

use Modules\Comment\Actions\Comment\ProcessCommentAction;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('copia original_text quando non ci sono transformer', function (): void {
    config()->set('comments.comment_transformers', []);

    $comment = new Comment([
        'original_text' => 'Testo semplice',
    ]);

    app(ProcessCommentAction::class)->handle($comment);

    Assert::assertSame('Testo semplice', $comment->text);
});

it('trasforma markdown in html', function (): void {
    $comment = new Comment([
        'original_text' => '**grassetto**',
    ]);

    app(ProcessCommentAction::class)->handle($comment);

    Assert::assertStringContainsString('<strong>grassetto</strong>', (string) $comment->text);
});
