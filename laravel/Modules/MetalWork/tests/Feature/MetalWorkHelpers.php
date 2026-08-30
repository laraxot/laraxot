<?php

declare(strict_types=1);

use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Models\MetalSupplierPrice;

/**
 * Helper condivisi dai test del modulo. Stanno qui e non dentro un file di test
 * perche' Pest carica tutta la suite nello stesso processo, e una funzione
 * dichiarata due volte e' un errore fatale.
 */
function metalWorksFixture(string $name): string
{
    return __DIR__.'/../Fixtures/'.$name;
}

/** A raw material with one invoiced price behind it, which is what makes it quotable. */
function metalWorksMaterial(
    string $unit = 'kg',
    float $price = 1.00,
    ?string $code = null,
    string $family = 'flat',
): MetalRawMaterial {
    $attributes = ['unit_code' => $unit, 'family_code' => $family];

    if ($code !== null) {
        $attributes['code'] = $code;
        $attributes['description'] = $code;
    }

    $material = MetalRawMaterial::factory()->create($attributes);

    MetalSupplierPrice::factory()->create([
        'raw_material_id' => $material->id,
        'unit_code' => $unit,
        'unit_price' => $price,
    ]);

    return $material->fresh();
}
