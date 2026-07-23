<?php

declare(strict_types=1);

use Livewire\Wireable;
use Modules\Cms\Datas\FooterData;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;

uses(TestCase::class);
test('FooterData can be instantiated', function (): void {
    $footerData = new FooterData();

    Assert::assertInstanceOf(FooterData::class, $footerData);
});

test('FooterData extends Spatie Data', function (): void {
    $footerData = new FooterData();

    Assert::assertInstanceOf(Data::class, $footerData);
});

test('FooterData implements Wireable interface', function (): void {
    $footerData = new FooterData();

    Assert::assertInstanceOf(Wireable::class, $footerData);
});

test('FooterData has default view path', function (): void {
    $footerData = new FooterData();

    Assert::assertSame('cms::components.footer', $footerData->view);
});

test('FooterData has nullable background_color property', function (): void {
    $footerData = new FooterData();

    Assert::assertNull($footerData->background_color);
});

test('FooterData has nullable background property', function (): void {
    $footerData = new FooterData();

    Assert::assertNull($footerData->background);
});

test('FooterData has nullable overlay_color property', function (): void {
    $footerData = new FooterData();

    Assert::assertNull($footerData->overlay_color);
});

test('FooterData rules method returns validation rules', function (): void {
    $rules = FooterData::rules();
    /* @var array<string, mixed> $rules */
    Assert::assertArrayHasKey('background_color', $rules);

    Assert::assertArrayHasKey('background', $rules);

    Assert::assertArrayHasKey('overlay_color', $rules);

    Assert::assertArrayHasKey('view', $rules);
});

test('FooterData can be created from array using from method', function (): void {
    $data = [
        'background_color' => '#ffffff',
        'background' => 'image.jpg',
        'overlay_color' => 'rgba(0,0,0,0.5)',
    ];

    $footerData = FooterData::from($data);

    Assert::assertInstanceOf(FooterData::class, $footerData);

    Assert::assertSame('#ffffff', $footerData->background_color);

    Assert::assertSame('image.jpg', $footerData->background);

    Assert::assertSame('rgba(0,0,0,0.5)', $footerData->overlay_color);
});

test('FooterData can be converted to array', function (): void {
    $footerData = FooterData::from([
        'background_color' => '#000000',
    ]);

    $array = $footerData->toArray();
    /* @var array<string, mixed> $array */
    Assert::assertArrayHasKey('background_color', $array);
});
