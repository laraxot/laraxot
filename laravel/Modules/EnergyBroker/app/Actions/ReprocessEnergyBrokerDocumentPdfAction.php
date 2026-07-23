<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;

final class ReprocessEnergyBrokerDocumentPdfAction
{
    public function execute(EnergyBrokerDocument $document, string $binary, string $filename): EnergyBrokerDocument
    {
        if ($binary === '') {
            throw new \RuntimeException('PDF non leggibile o vuoto.');
        }

        $analysis = app(AnalyzeEnergyBrokerDocumentWithGoogleDocumentAiAction::class)->execute(
            filename: $filename,
            mimeType: 'application/pdf',
            fileData: base64_encode($binary),
        );

        $text = trim((string) ($analysis['text'] ?? ''));
        $draft = app(BuildEnergyBrokerDocumentDraftAction::class)->execute($filename, $text);

        $document->forceFill([
            'source_file_name' => $filename,
            'file_hash' => hash('sha256', $binary),
            'document_type' => (string) ($draft['document_type'] ?? 'unknown'),
            'service_type' => (string) ($draft['service_type'] ?? 'unknown'),
            'supplier_name' => data_get($draft, 'contract.current_supplier') ?? data_get($draft, 'contract.proposed_supplier'),
            'analysis_status' => 'draft',
            'confidence_score' => 0.95,
            'raw_text' => $text,
            'analysis_payload' => $analysis['raw_response'] ?? [],
            'normalized_payload' => $draft,
            'reviewed_payload' => null,
            'processed_at' => now(),
        ])->save();

        return $document->refresh();
    }

    public function executeFromPath(EnergyBrokerDocument $document, string $path, string $filename): EnergyBrokerDocument
    {
        if (! is_file($path)) {
            throw new \RuntimeException('PDF non trovato.');
        }

        return $this->execute($document, (string) file_get_contents($path), $filename);
    }

    public function executeFromMedia(EnergyBrokerDocument $document): EnergyBrokerDocument
    {
        $media = $document->media;
        if ($media === null || $media->path === null) {
            throw new \RuntimeException('PDF originale non disponibile per la rielaborazione.');
        }

        $disk = (string) ($media->disk ?: 'public');
        if (! Storage::disk($disk)->exists($media->path)) {
            throw new \RuntimeException('PDF originale non trovato nello storage.');
        }

        return $this->execute(
            document: $document,
            binary: (string) Storage::disk($disk)->get($media->path),
            filename: (string) ($media->title ?: $document->source_file_name),
        );
    }
}
