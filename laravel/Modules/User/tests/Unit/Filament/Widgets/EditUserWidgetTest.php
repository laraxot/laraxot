<?php

declare(strict_types=1);

use Modules\User\Filament\Widgets\EditUserWidget;
use Modules\User\Tests\TestCase;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('EditUserWidget', function (): void {
    test('edit user widget can be instantiated', function (): void {
        $widget = new EditUserWidget;

        Assert::assertInstanceOf(EditUserWidget::class, $widget);
    });

    test('edit user widget extends xot base widget', function (): void {
        $widget = new EditUserWidget;

        Assert::assertInstanceOf(XotBaseSchemaWidget::class, $widget);
    });

    test('edit user widget has type property', function (): void {
        $widget = new EditUserWidget;
    });

    test('edit user widget has resource property', function (): void {
        $widget = new EditUserWidget;
    });

    test('edit user widget has model property', function (): void {
        $widget = new EditUserWidget;
    });
});
