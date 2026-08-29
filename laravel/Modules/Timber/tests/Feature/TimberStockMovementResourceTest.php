<?php

declare(strict_types=1);

use Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages\CreateTimberStockMovement;
use Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages\ListTimberStockMovements;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Tests\TestCase;
use Spatie\Permission\Models\Role;

uses(TestCase::class);

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists stock movements as a timber_admin', function (): void {
    actingAsTimberAdmin();

    $product = TimberProduct::factory()->createOne(['description' => 'Rete legna pizzeria']);
    TimberStockMovement::factory()->createOne([
        'product_id' => $product->id,
        'document_reference' => 'DDT 246 del 6/7',
    ]);

    testTimberLivewire(ListTimberStockMovements::class)
        ->assertOk()
        ->assertSee('Rete legna pizzeria')
        ->assertSee('DDT 246 del 6/7');
});

it('registers a load movement through the resource form', function (): void {
    $admin = actingAsTimberAdmin();

    $product = TimberProduct::factory()->createOne(['category' => 'Sboli legnami']);

    testTimberLivewire(CreateTimberStockMovement::class)
        ->fillForm([
            'movement_date' => now()->toDateString(),
            'direction_code' => 'load',
            'product_id' => $product->id,
            'quantity' => 120,
            'unit_of_measure' => 'quintali',
            'document_reference' => 'DDT 250 del 7/7',
            'lot_code' => 'LOT-2026-0009',
            'unit_cost' => 9.5,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $movement = TimberStockMovement::query()->where('lot_code', 'LOT-2026-0009')->firstOrFail();

    expect($movement->direction_code)->toBe('load')
        ->and((float) $movement->quantity)->toBe(120.0)
        ->and($movement->category)->toBe('Sboli legnami')
        ->and($movement->created_by_user_id)->toBe($admin->id);

    expect((float) $product->refresh()->last_cost)->toBe(9.5);
});

it('rejects an invalid load quantity through the resource form', function (): void {
    actingAsTimberAdmin();

    $product = TimberProduct::factory()->createOne();

    testTimberLivewire(CreateTimberStockMovement::class)
        ->fillForm([
            'movement_date' => now()->toDateString(),
            'direction_code' => 'load',
            'product_id' => $product->id,
            'quantity' => 0,
            'unit_of_measure' => 'quintali',
        ])
        ->call('create')
        ->assertHasFormErrors(['quantity']);
});
