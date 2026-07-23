<?php

declare(strict_types=1);

use Modules\Comment\Datas\CommentConfigData;
use Modules\Comment\Filament\Widgets\Commentable\CommentsWidget;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use Modules\Comment\Transformers\MentionsTransformer;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

afterEach(function (): void {
    config(['comments' => require base_path('Modules/Comment/config/comments.php')]);
});

it('mentions sono disabilitate di default', function (): void {
    config(['comments.mentions.enabled' => false]);

    Assert::assertFalse(CommentConfigData::make()->mentions['enabled'] ?? false);
});

it('converte la sintassi mention in span con data-mention id', function (): void {
    config(['comments.mentions.enabled' => true]);

    $comment = new Comment([
        'original_text' => 'Ciao @{42|Mario Rossi}',
        'text' => '<p>Ciao @{42|Mario Rossi}</p>',
    ]);

    app(MentionsTransformer::class)->handle($comment);

    Assert::assertStringContainsString('data-mention="42"', (string) $comment->original_text);
    Assert::assertStringContainsString('data-mention="42"', (string) $comment->text);
});

it('insertMention sostituisce la query @ con token mention', function (): void {
    config(['comments.mentions.enabled' => true]);

    $component = app(CommentsWidget::class);
    $component->mount(uiConfig: new \Modules\Comment\Datas\CommentsWidgetUiData);
    $component->uiConfig->text = 'Salve @Mar';
    $component->insertMention(99, 'Mario Rossi');

    Assert::assertSame('Salve @{99|Mario Rossi} ', $component->uiConfig->text);
});
