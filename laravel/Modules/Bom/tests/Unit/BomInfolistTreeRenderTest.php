<?php

declare(strict_types=1);

use Modules\Bom\Database\Factories\BomFactory;
use Modules\Bom\Database\Factories\BomItemFactory;
use Modules\Bom\Filament\Resources\BomResource\Schemas\BomForm;
use Modules\Bom\Filament\Resources\BomResource\Schemas\BomInfolist;
use Modules\Bom\Models\Bom;
use Modules\Bom\Tests\TestCase;
use Modules\Catalog\Models\Product;

uses(TestCase::class);

/**
 * Invoca il metodo privato BomInfolist::renderBomTree() via reflection.
 *
 * Copre la logica di rendering dell'albero distinta base recuperata dopo
 * una regressione della bozza di estrazione Schemas/Tables (Epic 4,
 * story 4.14.11) che l'aveva eliminata silenziosamente: la vecchia
 * ViewBom::getInfolistSchema() renderizzava l'albero via renderBomTree(),
 * la nuova BomInfolist::getInfolistSchema() aveva solo campi piatti e
 * lasciava renderBomTree() come metodo morto (method.unused) in ViewBom.
 */
function renderBomTreeHtml(Bom $bom): string
{
    $infolist = app(BomInfolist::class);
    $method = new ReflectionMethod($infolist, 'renderBomTree');
    $method->setAccessible(true);

    /** @var string $html */
    $html = $method->invoke($infolist, $bom);

    return $html;
}

it('shows the empty-state message when the bom has no items', function (): void {
    $bom = BomFactory::new()->createOne(['name' => 'Empty Bom']);

    $html = renderBomTreeHtml($bom);

    expect($html)->toContain('Nessun componente');
});

it('renders nested components with their computed total quantity', function (): void {
    $rootBom = BomFactory::new()->createOne(['name' => 'Root Bom']);
    $childProduct = Product::query()->create(['name' => 'Componente Figlio']);
    $grandChildProduct = Product::query()->create(['name' => 'Componente Nipote']);

    BomItemFactory::new()->createOne([
        'bom_id' => $rootBom->id,
        'component_product_id' => $childProduct->id,
        'quantity' => 2,
    ]);

    $childBom = BomFactory::new()->createOne([
        'product_id' => $childProduct->id,
        'name' => 'Child Bom',
        'is_active' => true,
    ]);

    BomItemFactory::new()->createOne([
        'bom_id' => $childBom->id,
        'component_product_id' => $grandChildProduct->id,
        'quantity' => 3,
    ]);

    $html = renderBomTreeHtml($rootBom);

    expect($html)
        ->toContain('Componente Figlio')
        ->toContain('Componente Nipote')
        ->toContain('Qta totale: 6'); // 2 (root->figlio) * 3 (figlio->nipote)
});

it('breaks circular bom references instead of recursing infinitely', function (): void {
    $productA = Product::query()->create(['name' => 'Prodotto A']);
    $productB = Product::query()->create(['name' => 'Prodotto B']);

    $bomA = BomFactory::new()->createOne(['product_id' => $productA->id, 'name' => 'Bom A', 'is_active' => true]);
    $bomB = BomFactory::new()->createOne(['product_id' => $productB->id, 'name' => 'Bom B', 'is_active' => true]);

    BomItemFactory::new()->createOne([
        'bom_id' => $bomA->id,
        'component_product_id' => $productB->id,
        'quantity' => 1,
    ]);
    BomItemFactory::new()->createOne([
        'bom_id' => $bomB->id,
        'component_product_id' => $productA->id,
        'quantity' => 1,
    ]);

    $html = renderBomTreeHtml($bomA);

    expect($html)->toContain('Rilevato riferimento circolare');
});

it('exposes the infolist and form schemas keyed by string as required by XotBaseResourceForm/Infolist', function (): void {
    $infolistSchema = app(BomInfolist::class)->getInfolistSchema();
    $formSchema = app(BomForm::class)->getFormSchema();

    expect($infolistSchema)->toHaveKeys(['summary', 'tree']);
    expect($formSchema)->toHaveKey('main');
});
