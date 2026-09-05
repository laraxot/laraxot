<?php

declare(strict_types=1);

use Modules\Cms\Filament\Front\Pages\Home;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('Home page can be instantiated', function () {
    $page = new Home();
});

test('Home page has view_type property', function () {
    $page = new Home();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('view_type');
    $property->setAccessible(true);

    Assert::assertSame('view_type', $property->getName());
});

test('Home page has containers property', function () {
    $page = new Home();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('containers');
    $property->setAccessible(true);

    Assert::assertIsArray($property->getValue($page));
});

test('Home page has items property', function () {
    $page = new Home();
    $reflection = new ReflectionClass($page);
    $property = $reflection->getProperty('items');
    $property->setAccessible(true);

    Assert::assertIsArray($property->getValue($page));
});

it('Home page has mount method')->todo();
it('Home page has getViewData method')->todo();
it('Home page has initView method')->todo();
it('Home page has url method')->todo();
