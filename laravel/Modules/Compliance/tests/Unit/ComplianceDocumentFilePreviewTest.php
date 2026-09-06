<?php

declare(strict_types=1);

namespace Modules\Compliance\Tests\Unit;

use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Tests\TestCase;
use Modules\Document\Models\Media;

uses(TestCase::class);

/*
|--------------------------------------------------------------------------
| ComplianceDocumentResource::filePreview()
|--------------------------------------------------------------------------
|
| filePreview() feeds the form's file_preview TextEntry::state() closure
| (Filament v5 replacement for the deprecated Placeholder::content()).
| These tests pin the returned HtmlString content down at the pure-PHP
| level, without needing a booted Filament panel or a persisted record.
*/

test('file preview shows a placeholder message when no media is attached', function (): void {
    $document = new ComplianceDocument();

    $html = ComplianceDocumentResource::filePreview($document)->toHtml();

    expect($html)->toContain('Nessun file allegato');
});

test('file preview shows a placeholder message for a null record', function (): void {
    $html = ComplianceDocumentResource::filePreview(null)->toHtml();

    expect($html)->toContain('Nessun file allegato');
});

test('file preview renders an inline image for image media', function (): void {
    $media = new Media();
    $media->path = 'compliance/durc.png';
    $media->disk = 'public';
    $media->title = 'DURC';
    $media->mime_type = 'image/png';

    $document = new ComplianceDocument();
    $document->setRelation('media', $media);

    $html = ComplianceDocumentResource::filePreview($document)->toHtml();

    expect($html)->toContain('<img')
        ->and($html)->toContain('Scarica file');
});

test('file preview renders a generic download link for non-image media', function (): void {
    $media = new Media();
    $media->path = 'compliance/durc.pdf';
    $media->disk = 'public';
    $media->title = 'DURC';
    $media->mime_type = 'application/pdf';

    $document = new ComplianceDocument();
    $document->setRelation('media', $media);

    $html = ComplianceDocumentResource::filePreview($document)->toHtml();

    expect($html)->toContain('Anteprima non disponibile')
        ->and($html)->toContain('Scarica file')
        ->and($html)->not->toContain('<img');
});
