<?php

declare(strict_types=1);

use Modules\EnergyBroker\Actions\DeletePoorlyAnalyzedEnergyBrokerDocumentsAction;
use Modules\EnergyBroker\Actions\ExtractPdfFilesFromZipAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Tests\TestCase;

use function Safe\file_put_contents;
use function Safe\unlink;

/*
|--------------------------------------------------------------------------
| Migrato da gestionale_commesse (tests/Feature/EnergyBroker) — 2026-07-24
|--------------------------------------------------------------------------
|
| Smoke-level: la copertura originale (import ZIP + Google Document AI +
| customer matching) richiede mock estesi non proporzionati a un modulo
| dormiente (modules_statuses.json = false). Portate solo le parti
| autosufficienti (estrazione ZIP, pulizia documenti mal analizzati),
| adattate ai namespace correnti (Models\/Actions\ invece di
| Infrastructure\Models\/Application\Actions\).
*/

uses(\Modules\EnergyBroker\Tests\TestCase::class);

it('extracts pdf files from a zip archive for batch imports', function (): void {
    $zipPath = storage_path('app/test-energy-broker-import.zip');
    $firstPdf = storage_path('app/test-energy-broker-1.pdf');
    $ignoredFile = storage_path('app/test-energy-broker.txt');

    file_put_contents($firstPdf, '%PDF-1.4 first');
    file_put_contents($ignoredFile, 'ignore');

    $archive = new ZipArchive();
    expect($archive->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE))->toBeTrue();
    $archive->addFile($firstPdf, 'first.pdf');
    $archive->addFile($ignoredFile, 'notes.txt');
    $archive->close();

    $entries = app(ExtractPdfFilesFromZipAction::class)->execute($zipPath);

    expect($entries)->toHaveCount(1)
        ->and($entries[0]['original_name'] ?? null)->toBe('first.pdf');

    unlink($zipPath);
    unlink($firstPdf);
    unlink($ignoredFile);
});

it('deletes only poorly analyzed energy broker documents', function (): void {
    $failed = EnergyBrokerDocument::query()->create([
        'source_file_name' => 'failed.pdf',
        'document_type' => 'unknown',
        'service_type' => 'unknown',
        'analysis_status' => 'failed',
        'analysis_payload' => ['error' => 'Documento non leggibile'],
        'normalized_payload' => [],
        'processed_at' => now(),
    ]);

    $goodDraft = EnergyBrokerDocument::query()->create([
        'source_file_name' => 'good-draft.pdf',
        'document_type' => 'utility_bill_electricity',
        'service_type' => 'electricity',
        'analysis_status' => 'draft',
        'confidence_score' => 0.90,
        'raw_text' => 'testo completo',
        'normalized_payload' => ['customer' => ['legal_name' => 'Cliente']],
        'processed_at' => now(),
    ]);

    $result = app(DeletePoorlyAnalyzedEnergyBrokerDocumentsAction::class)->execute([$failed, $goodDraft]);

    expect($result)->toBe(['deleted' => 1, 'skipped' => 1])
        ->and(EnergyBrokerDocument::query()->whereKey($failed->id)->exists())->toBeFalse()
        ->and(EnergyBrokerDocument::query()->whereKey($goodDraft->id)->exists())->toBeTrue();
});
