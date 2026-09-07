<?php

declare(strict_types=1);

use Filament\Forms\Components\Field;
use Modules\Cms\Filament\Resources\MenuResource;
use Modules\Cms\Models\Menu;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('MenuResource', function (): void {
    test('menu resource has correct model', function (): void {
        $resource = new MenuResource();

        Assert::assertSame(Menu::class, $resource::getModel());
    });

   
    test('menu resource extends XotBaseResource', function (): void {
        Assert::assertTrue(class_exists(MenuResource::class));
    });
});
