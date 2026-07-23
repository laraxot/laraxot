<?php

declare(strict_types=1);

use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Comment\Filament\Resources\CommentResource\Pages\ListComments;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentForm;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentInfolist;
use Modules\Comment\Filament\Resources\CommentResource\Tables\CommentsTable;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('comment resource usa il modello Comment', function (): void {
    Assert::assertSame(Comment::class, CommentResource::getModel());
});

test('list comments espone azioni approve reject e bulk moderation', function (): void {
    $page = new ListComments;

    $actions = $page->getTableActions();
    $bulkActions = $page->getTableBulkActions();
    $filters = $page->getTableFilters();

    Assert::assertArrayHasKey('approve', $actions);
    Assert::assertArrayHasKey('reject', $actions);
    Assert::assertArrayHasKey('moderation', $bulkActions);
    Assert::assertArrayHasKey('moderation', $filters);
});

test('list comments definisce colonne id testo e stato moderazione', function (): void {
    $page = new ListComments;
    $columns = $page->getTableColumns();

    foreach (['id', 'original_text', 'commentator', 'commentable', 'status', 'created_at'] as $key) {
        Assert::assertArrayHasKey($key, $columns);
    }
});

test('comment resource schema classes exist and delegate zen table', function (): void {
    Assert::assertNotEmpty(CommentForm::getFormSchema());
    Assert::assertNotEmpty(CommentInfolist::getInfolistSchema());
    Assert::assertNotEmpty((new CommentsTable)->getTableColumns());
    Assert::assertArrayHasKey('moderation', (new CommentsTable)->getTableFilters());
});
