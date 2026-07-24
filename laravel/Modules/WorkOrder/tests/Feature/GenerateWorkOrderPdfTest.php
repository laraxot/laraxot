<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Modules\WorkOrder\Tests\TestCase;

uses(TestCase::class);

// Adattato da gestionale_commesse tests/Feature/WorkOrders/GenerateWorkOrderPdfTest.php
// GenerateWorkOrderPdfAction dipende da Barryvdh\DomPDF\Facade\Pdf, non installato nel
// monorepo — vedi laravel/docs/gestionale-commesse-debito-tecnico.md #5.

it('generates a work order PDF', function (): void {
    expect(true)->toBeTrue();
})->skip('needs barryvdh/laravel-dompdf - see debito-tecnico.md #5');
