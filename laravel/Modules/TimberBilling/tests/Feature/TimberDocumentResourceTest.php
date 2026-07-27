<?php

declare(strict_types=1);

use Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages\CreateTimberDocument;
use Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages\ListTimberDocuments;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;
use Modules\TimberBilling\Tests\TestCase;
use Spatie\Permission\Models\Role;

uses(TestCase::class);

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists documents as a timber_admin', function (): void {
    actingAsTimberAdmin();

    TimberDocument::factory()->createOne(['number' => 'DDT-2026-0100']);

    testTimberLivewire(ListTimberDocuments::class)
        ->assertOk()
        ->assertSee('DDT-2026-0100');
});

it('creates a document with lines, auto-numbers it and computes totals', function (): void {
    $admin = actingAsTimberAdmin();

    $customer = TimberCustomer::factory()->createOne();
    $product = TimberProduct::factory()->createOne();

    testTimberLivewire(CreateTimberDocument::class)
        ->fillForm([
            'document_type_code' => 'ddt',
            'number' => null,
            'document_date' => now()->toDateString(),
            'customer_id' => $customer->id,
            'status_code' => 'confirmed',
            'lines' => [
                [
                    'product_id' => $product->id,
                    'description' => 'Legna abete',
                    'quantity' => 10,
                    'unit_of_measure' => 'quintali',
                    'unit_price' => 9.0,
                    'discount_percent' => 10,
                    'lot_code' => 'LOT-A',
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $document = TimberDocument::query()->where('customer_id', $customer->id)->firstOrFail();

    expect($document->number)->toBe('DDT-'.now()->format('Y').'-0001')
        ->and($document->lines()->count())->toBe(1)
        ->and((float) $document->total_amount)->toBe(81.0)
        ->and($document->created_by_user_id)->toBe($admin->id);
});

it('posts a confirmed DDT to the warehouse via the row action', function (): void {
    actingAsTimberAdmin();

    $product = TimberProduct::factory()->createOne();
    TimberStockMovement::factory()->createOne([
        'product_id' => $product->id,
        'direction_code' => 'load',
        'quantity' => 50,
    ]);

    $document = TimberDocument::factory()->createOne([
        'document_type_code' => 'ddt',
        'number' => 'DDT-2026-0200',
        'status_code' => 'confirmed',
    ]);
    $document->lines()->create([
        'product_id' => $product->id,
        'description' => $product->description,
        'quantity' => 20,
        'unit_of_measure' => 'quintali',
        'unit_price' => 9,
        'discount_percent' => 0,
        'line_total' => 180,
    ]);

    testTimberLivewire(ListTimberDocuments::class)
        ->callTableAction('postToStock', $document);

    expect($document->refresh()->stock_posted_at)->not->toBeNull()
        ->and(TimberStockMovement::query()->where('document_reference', 'DDT-2026-0200')->where('direction_code', 'unload')->exists())->toBeTrue();
});
