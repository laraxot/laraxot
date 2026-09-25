<?php

declare(strict_types=1);

use Modules\Comment\Filament\Widgets\Comment\CommentWidget;
use Modules\Comment\Filament\Widgets\Commentable\CommentsWidget;
use Modules\Comment\Filament\Widgets\Mention\MentionSearchWidget;
use Modules\Comment\Providers\CommentEngineServiceProvider;
use Modules\Comment\Tests\TestCase;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use PHPUnit\Framework\Assert;
use function Safe\file_get_contents;

uses(TestCase::class);

test('Comment FO widgets exist and provider does not register raw Livewire aliases', function (): void {
    $source = file_get_contents(
        dirname(__DIR__, 2).'/app/Providers/CommentEngineServiceProvider.php',
    );

    Assert::assertStringNotContainsString('Livewire::component', $source);
    Assert::assertTrue(class_exists(CommentsWidget::class));
    Assert::assertTrue(class_exists(CommentWidget::class));
    Assert::assertTrue(class_exists(MentionSearchWidget::class));
    Assert::assertTrue(class_exists(CommentEngineServiceProvider::class));
});

test('Comment FO widgets extend XotBase hierarchy not Filament vendor directly', function (): void {
    $widgets = [CommentsWidget::class, CommentWidget::class, MentionSearchWidget::class];

    foreach ($widgets as $widgetClass) {
        $reflection = new ReflectionClass($widgetClass);
        $parent = $reflection->getParentClass();
        Assert::assertInstanceOf(ReflectionClass::class, $parent);

        Assert::assertSame(XotBaseSchemaWidget::class, $parent->getName());
    }
});
