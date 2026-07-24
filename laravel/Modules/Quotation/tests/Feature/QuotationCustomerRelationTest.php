<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Modules\Customer\Models\Customer;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Models\Quotation;

/**
 * La ricerca globale cerca i preventivi anche per nome cliente e mostra il
 * cliente fra i dettagli del risultato: senza la relazione il pannello va in
 * BadMethodCallException appena si digita qualcosa.
 */
it('resolves the customer of a quotation', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Condominio Le Terrazze',
        'is_active' => true,
    ]);

    $quotation = Quotation::query()->create([
        'customer_id' => $customer->id,
        'code' => 'PRV-TEST-001',
        'status_code' => 'draft',
    ]);

    expect($quotation->customer)->not->toBeNull()
        ->and($quotation->customer?->legal_name)->toBe('Condominio Le Terrazze');
});

it('shows the customer in the global search details of a quotation', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Cliente Ricerca Preventivo',
        'is_active' => true,
    ]);

    $quotation = Quotation::query()->create([
        'customer_id' => $customer->id,
        'code' => 'PRV-TEST-002',
        'status_code' => 'confirmed',
    ]);

    expect(QuotationResource::getGlobalSearchResultDetails($quotation))
        ->toContain('Cliente Ricerca Preventivo');
});

it('searches quotations by customer name without blowing up', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Idraulica Cercabile Srl',
        'is_active' => true,
    ]);

    Quotation::query()->create([
        'customer_id' => $customer->id,
        'code' => 'PRV-TEST-003',
        'status_code' => 'draft',
    ]);

    // E' la query che esegue davvero Filament sugli attributi ricercabili.
    $found = Quotation::query()
        ->whereHas('customer', fn ($q) => $q->where('legal_name', 'like', '%Cercabile%'))
        ->get();

    expect($found)->toHaveCount(1)
        ->and($found->first()?->code)->toBe('PRV-TEST-003');
});
