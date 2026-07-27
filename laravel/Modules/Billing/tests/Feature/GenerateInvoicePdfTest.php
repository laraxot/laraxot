<?php

declare(strict_types=1);

use Modules\Billing\Tests\TestCase;

uses(TestCase::class);

it('generates an invoice PDF', function (): void {
    skip('needs barryvdh/laravel-dompdf - see gestionale-commesse-technical-debt.md (DomPDF vendor)');
    // GenerateInvoicePdfAction dipende da Barryvdh\DomPDF\Facade\Pdf.
});

it('generates an invoice PDF when linked to a work order', function (): void {
    skip('needs barryvdh/laravel-dompdf - see gestionale-commesse-technical-debt.md (DomPDF vendor)');
});
