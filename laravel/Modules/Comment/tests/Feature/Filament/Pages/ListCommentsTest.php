<?php

declare(strict_types=1);

use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentForm;
use Modules\Comment\Filament\Resources\CommentResource\Schemas\CommentInfolist;
use Modules\Comment\Filament\Resources\CommentResource\Tables\CommentsTable;
use Modules\Comment\Models\Comment;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * getTableActions() is @deprecated on HasXotTable (owner decision, table()
 * is the sanctioned call path) but remains the correct way for a Resource
 * Table to declare its row actions until that migration happens; invoking it
 * via reflection here tests the override directly without a direct-call
 * deprecation warning, same pattern as HasXotTableLayoutHooksTest.php.
 *
 * @return array<string, mixed>
 */
function invokeDeprecatedTableActions(CommentsTable $table): array
{
    $reflection = new ReflectionMethod($table, 'getTableActions');

    /** @var array<string, mixed> */
    return $reflection->invoke($table);
}

test('comment resource usa il modello Comment', function (): void {
    Assert::assertSame(Comment::class, CommentResource::getModel());
});

test('list comments espone azioni approve reject e bulk moderation', function (): void {
    $table = new CommentsTable;

    $actions = invokeDeprecatedTableActions($table);
    $bulkActions = $table->getTableBulkActions();
    $filters = $table->getTableFilters();

    Assert::assertArrayHasKey('approve', $actions);
    Assert::assertArrayHasKey('reject', $actions);
    Assert::assertArrayHasKey('moderation', $bulkActions);
    Assert::assertArrayHasKey('moderation', $filters);
});

test('list comments definisce colonne id testo e stato moderazione', function (): void {
    $table = new CommentsTable;
    $columns = $table->getTableColumns();

    foreach (['id', 'original_text', 'commentator', 'commentable', 'status', 'created_at'] as $key) {
        Assert::assertArrayHasKey($key, $columns);
    }
});

test('comment resource schema classes exist and delegate zen table', function (): void {
    $form = app(CommentForm::class)->getFormSchema();
    Assert::assertNotEmpty($form);
    $infolist = app(CommentInfolist::class)->getInfolistSchema();
    Assert::assertNotEmpty($infolist);
    Assert::assertNotEmpty((new CommentsTable)->getTableColumns());
    Assert::assertArrayHasKey('moderation', (new CommentsTable)->getTableFilters());
});
