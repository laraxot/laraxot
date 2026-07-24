<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

// Adattato da gestionale_commesse tests/Feature/WorkOrders/GenerateWorkOrderReportActionTest.php
// GenerateWorkOrderReportAction dipende da PhpOffice\PhpWord\IOFactory e
// Barryvdh\DomPDF\Facade\Pdf, nessuno dei due installato — vedi
// laravel/docs/gestionale-commesse-debito-tecnico.md #5. I dieci test originali
// (PDF/DOCX happy path, edge case periodo vuoto, filename, varianti outcome,
// materiali, impostazione inventory.allow_link_interventions) coprono comportamenti
// genuinamente utili: da riportare non appena i pacchetti sono installati.

it('generates a PDF report for a work order with interventions', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates a DOCX report for a work order with interventions', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates a PDF report with no interventions in the period', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates a DOCX report with no interventions in the period', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('report filename contains work order code and date range', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('covers site section and outcome variants in DOCX report', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('generates reports also when interventions include material lines', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});

it('keeps reporting available when intervention materials are disabled in settings', function (): void {
    $this->markTestSkipped('needs phpoffice/phpword and barryvdh/laravel-dompdf - see debito-tecnico.md #5');
});
