<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\BackfillConfirmedAtForConfirmedQuotationsAction;

it('backfills confirmed_at from issued_at for confirmed quotations', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $issuedAt = now()->subDay();

    $quotationId = DB::table('quotations')->insertGetId([
        'customer_id' => $customerId,
        'work_order_id' => null,
        'code' => 'Q-BACKFILL-1',
        'status_code' => 'confirmed',
        'title' => 'Q',
        'notes' => null,
        'subtotal' => 0,
        'tax_amount' => 0,
        'total' => 0,
        'tax_percent' => 22,
        'discount_amount' => 0,
        'taxable_amount' => 0,
        'issued_at' => $issuedAt,
        'confirmed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
    ]);

    // Act
    $updated = app(BackfillConfirmedAtForConfirmedQuotationsAction::class)->execute();

    // Assert
    expect($updated)->toBe(1);
    $confirmedAt = DB::table('quotations')->where('id', $quotationId)->value('confirmed_at');
    expect($confirmedAt)->not->toBeNull();
});

