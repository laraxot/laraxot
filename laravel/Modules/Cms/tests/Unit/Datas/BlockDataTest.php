<?php

declare(strict_types=1);

use Livewire\Wireable;
use Modules\Cms\Datas\BlockData;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

uses(TestCase::class);
test('BlockData can be instantiated with type and data', function (): void {
    $blockData = new BlockData('hero', ['view' => 'ui::empty', 'title' => 'Test']);

    Assert::assertInstanceOf(BlockData::class, $blockData);

    Assert::assertSame('hero', $blockData->type);

    Assert::assertSame('Test', $blockData->data['title']);
});

test('BlockData uses WireableData trait', function (): void {
    $traits = class_uses_recursive(BlockData::class);

    Assert::assertContains(WireableData::class, array_values($traits));
});

test('BlockData extends Spatie Data', function (): void {
    $blockData = new BlockData('text', ['view' => 'ui::empty']);

    Assert::assertInstanceOf(Data::class, $blockData);
});

test('BlockData implements Wireable interface', function (): void {
    $blockData = new BlockData('card', ['view' => 'ui::empty']);

    Assert::assertInstanceOf(Wireable::class, $blockData);
});

test('BlockData collection method returns DataCollection', function (): void {
    $data = [
        ['type' => 'hero', 'data' => ['view' => 'ui::empty', 'title' => 'Hero']],
        ['type' => 'text', 'data' => ['view' => 'ui::empty', 'content' => 'Text']],
    ];

    $collection = BlockData::collection($data);

    Assert::assertInstanceOf(DataCollection::class, $collection);
});

test('BlockData sets default view when not provided', function (): void {
    $blockData = new BlockData('simple', []);

    Assert::assertSame('ui::empty', $blockData->view);
});

test('BlockData stores type correctly', function (): void {
    $blockData = new BlockData('testimonial', ['view' => 'ui::empty']);

    Assert::assertSame('testimonial', $blockData->type);
});

test('BlockData stores data array correctly', function (): void {
    $testData = [
        'view' => 'ui::empty',
        'title' => 'Test Title',
        'content' => 'Test Content',
        'image' => 'test.jpg',
    ];

    $blockData = new BlockData('feature', $testData);

    Assert::assertEquals($testData, $blockData->data);
});
