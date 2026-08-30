<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Modules\WorkOrder\Tests\TestCase;

uses(\Modules\WorkOrder\Tests\TestCase::class);

// Adattato da gestionale_commesse tests/Feature/WorkOrders/GenerateCustomerReportActionTest.php
// GenerateCustomerReportAction dipende da PhpOffice\PhpWord\IOFactory e
// Barryvdh\DomPDF\Facade\Pdf, nessuno dei due installato — vedi
// laravel/docs/gestionale-commesse-debito-tecnico.md #5. Il sorgente copre anche una
// regressione specifica ("manual material line senza inventory movements" — crash
// "getKey() on string" su una Collection Eloquent svuotata): da riportare con gli
// stessi assert non appena i pacchetti sono installati, e' comunque un caso di
// regressione utile da non perdere.

it('generates the customer PDF report when an intervention has manual materials but no inventory movements', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates the customer DOCX report when an intervention has manual materials but no inventory movements', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('includes interventions linked directly to the customer (no work order)', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates the report combining inventory and manual materials', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('renders DOCX with outcome variants, callout fee, total amount and a deleted product', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('renders DOCX using assigned users as operator names', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates the customer report with no interventions in the period', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});
