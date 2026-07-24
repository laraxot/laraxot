<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\Artisan;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\Quotation;

it('runs the backfill confirmed_at command', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => true,
    ]);

    $issuedAt = now()->subDays(2);

    $quotation = Quotation::query()->create([
        'customer_id' => $customer->id,
        'work_order_id' => null,
        'code' => 'Q-BACKFILL-001',
        'status_code' => 'confirmed',
        'title' => 'Backfill test',
        'notes' => null,
        'subtotal' => 0,
        'tax_amount' => 0,
        'total' => 0,
        'issued_at' => $issuedAt,
        'confirmed_at' => null,
    ]);

    expect($quotation->confirmed_at)->toBeNull();

    $exitCode = Artisan::call('quotations:backfill-confirmed-at');

    expect($exitCode)->toBe(0);

    $quotation->refresh();
    expect($quotation->confirmed_at)->not->toBeNull();
    expect($quotation->confirmed_at?->toDateTimeString())->toBe($issuedAt->toDateTimeString());
});

