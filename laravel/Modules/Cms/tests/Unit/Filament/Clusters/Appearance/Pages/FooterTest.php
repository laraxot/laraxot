<?php

declare(strict_types=1);

use Modules\Cms\Filament\Clusters\Appearance\Pages\Footer;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('Footer page can be instantiated', function () {
    $page = new Footer();
});

test('Footer page has data property', function () {
    $page = new Footer();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('data');
    $property->setAccessible(true);

    Assert::assertIsArray($property->getValue($page));
});

test('Footer page has footerData property', function () {
    $page = new Footer();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('footerData');
    $property->setAccessible(true);

    // Property exists but is null by default
    Assert::assertSame('footerData', $property->getName());
});

it('Footer page has mount method')->todo();
it('Footer page has schema method')->todo();
it('Footer page has updateData method')->todo();
it('Footer page has fillForms method')->todo();
it('Footer page has getUpdateFormActions method')->todo();
