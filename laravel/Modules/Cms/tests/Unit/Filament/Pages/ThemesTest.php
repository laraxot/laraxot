<?php

declare(strict_types=1);

use Modules\Cms\Filament\Pages\Themes;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('Themes page can be instantiated', function () {
    $page = new Themes();
});

test('Themes page has themes property', function () {
    $page = new Themes();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('themes');
    $property->setAccessible(true);

    Assert::assertIsArray($property->getValue($page));
});

it('Themes page has changePubTheme method')->todo();
it('Themes page has getViewData method')->todo();
