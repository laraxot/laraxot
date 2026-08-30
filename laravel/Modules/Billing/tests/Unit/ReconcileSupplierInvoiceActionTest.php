<?php

declare(strict_types=1);

use Modules\Billing\Actions\ReconcileSupplierInvoiceAction;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Datas\SupplierLine;
use Modules\Billing\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Billing\Tests\TestCase::class);

it('finds no discrepancy when invoice matches delivery notes', function (): void {
    $action = new ReconcileSupplierInvoiceAction();

    $discrepancies = $action->execute(
        invoiceLines: [new SupplierLine(description: 'Cavo 3x2.5', quantity: 10, unitPrice: 2.0)],
        deliveryLines: [new SupplierLine(description: 'Cavo 3x2.5', quantity: 10, unitPrice: 2.0)],
    );

    Assert::assertEmpty($discrepancies);
});

it('flags material invoiced but never delivered', function (): void {
    $action = new ReconcileSupplierInvoiceAction();

    $discrepancies = $action->execute(
        invoiceLines: [new SupplierLine(description: 'Quadro elettrico', quantity: 1, unitPrice: 300.0)],
        deliveryLines: [],
    );

    Assert::assertCount(1, $discrepancies);
    Assert::assertSame(ReconciliationDiscrepancy::TYPE_NOT_DELIVERED, $discrepancies[0]->typeCode);
});

it('flags an invoiced quantity greater than delivered', function (): void {
    $action = new ReconcileSupplierInvoiceAction();

    $discrepancies = $action->execute(
        invoiceLines: [new SupplierLine(description: 'Tubo corrugato', quantity: 20, unitPrice: 1.5)],
        deliveryLines: [new SupplierLine(description: 'Tubo corrugato', quantity: 15, unitPrice: 1.5)],
    );

    Assert::assertCount(1, $discrepancies);
    Assert::assertSame(ReconciliationDiscrepancy::TYPE_QUANTITY_OVER, $discrepancies[0]->typeCode);
});
