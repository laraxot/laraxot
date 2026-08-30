<?php

declare(strict_types=1);

use Modules\Fiscal\Actions\CalculateInvoiceTotalsAction;
use Modules\Fiscal\Actions\GenerateFatturaElettronicaXmlAction;
use Modules\Fiscal\Datas\FiscalBuyer;
use Modules\Fiscal\Datas\FiscalInvoiceData;
use Modules\Fiscal\Datas\FiscalInvoiceLine;
use Modules\Fiscal\Datas\FiscalSeller;
use Modules\Fiscal\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Fiscal\Tests\TestCase::class);

it('generates a valid FatturaPA xml document', function (): void {
    $action = new GenerateFatturaElettronicaXmlAction(new CalculateInvoiceTotalsAction());

    $xml = $action->execute(new FiscalInvoiceData(
        number: '1',
        date: '2026-01-01',
        seller: new FiscalSeller(
            country: 'IT',
            vatNumber: '01234567890',
            name: 'Acme Srl',
            taxRegime: 'RF01',
            address: 'Via Roma 1',
            postalCode: '00100',
            city: 'Roma',
            province: 'RM',
        ),
        buyer: new FiscalBuyer(name: 'Cliente Spa', vatNumber: 'IT98765432100'),
        lines: [
            new FiscalInvoiceLine(
                description: 'Servizio',
                quantity: 1,
                unitPrice: 100.0,
                discountPercent: 0,
                vatRatePercent: 22,
            ),
        ],
    ));

    Assert::assertStringContainsString('p:FatturaElettronica', $xml);
    Assert::assertStringContainsString('ImportoTotaleDocumento', $xml);
});
