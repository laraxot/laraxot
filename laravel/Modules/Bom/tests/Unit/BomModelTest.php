<?php

declare(strict_types=1);

use Modules\Bom\Database\Factories\BomFactory;
use Modules\Bom\Database\Factories\BomItemFactory;
use Modules\Bom\Models\Bom;
use Modules\Bom\Models\BomItem;
use Modules\Bom\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Bom\Tests\TestCase::class);

it('creates a bom', function (): void {
    $bom = BomFactory::new()->createOne();

    Assert::assertInstanceOf(Bom::class, $bom);
    Assert::assertTrue($bom->is_active);
});

it('links a bom item to a bom', function (): void {
    $bom = BomFactory::new()->createOne();
    $item = BomItemFactory::new()->createOne(['bom_id' => $bom->id]);

    Assert::assertInstanceOf(BomItem::class, $item);
    Assert::assertSame(1, $bom->items()->count());
});
