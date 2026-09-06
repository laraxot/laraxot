<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Spatie\QueueableAction\QueueableAction;

use function Safe\file_get_contents;

final class ImportEnergyBrokerDocumentPdfAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $mediaMeta
     */
    public function execute(
        string $filename,
        string $binary,
        ?int $batchId = null,
        int|string|null $uploadedByUserId = null,
        string $fallbackContent = '',
        array $mediaMeta = [],
    ): EnergyBrokerDocument {
        if (trim($filename) === '') {
            throw new \RuntimeException('Nome file documento mancante.');
        }

        if ($binary === '') {
            throw new \RuntimeException('PDF non leggibile o vuoto.');
        }

        $storedPath = 'media/energy-broker/documents/'.Str::uuid().'-'.basename($filename);
        Storage::disk('public')->put($storedPath, $binary);

        $analysis = app(AnalyzeEnergyBrokerDocumentWithGoogleDocumentAiAction::class)->execute(
            filename: $filename,
            mimeType: 'application/pdf',
            fileData: base64_encode($binary),
        );

        $text = trim((string) ($analysis['text'] ?? ''));
        if ($text === '') {
            $text = $fallbackContent;
        }

        $draft = app(BuildEnergyBrokerDocumentDraftAction::class)->execute($filename, $text);

        /** @var EnergyBrokerDocument $document */
        $document = EnergyBrokerDocument::query()->create([
            'energy_broker_import_batch_id' => $batchId,
            'source_file_name' => $filename,
            'file_hash' => hash('sha256', $binary),
            'document_type' => is_string($draft['document_type'] ?? null) ? (string) $draft['document_type'] : 'unknown',
            'service_type' => is_string($draft['service_type'] ?? null) ? (string) $draft['service_type'] : 'unknown',
            'supplier_name' => data_get($draft, 'contract.current_supplier') ?? data_get($draft, 'contract.proposed_supplier'),
            'analysis_status' => 'draft',
            'confidence_score' => 0.95,
            'raw_text' => $text,
            'analysis_payload' => $analysis['raw_response'] ?? [],
            'normalized_payload' => $draft,
            'processed_at' => now(),
        ]);

        $media = app(CreateMediaFromStoredFileAction::class)->execute(
            mediable: $document,
            kind: 'energy_broker_document',
            disk: 'public',
            path: $storedPath,
            uploadedByUserId: $uploadedByUserId,
            title: $filename,
            meta: $mediaMeta,
        );

        $document->forceFill([
            'media_id' => $media->id,
        ])->save();

        return $document->refresh();
    }

    /**
     * @param  array<string, mixed>  $mediaMeta
     */
    public function executeFromPath(
        string $filename,
        string $path,
        ?int $batchId = null,
        int|string|null $uploadedByUserId = null,
        string $fallbackContent = '',
        array $mediaMeta = [],
    ): EnergyBrokerDocument {
        if (! is_file($path)) {
            throw new \RuntimeException('PDF non trovato.');
        }

        $binary = (string) file_get_contents($path);

        return $this->execute(
            filename: $filename,
            binary: $binary,
            batchId: $batchId,
            uploadedByUserId: $uploadedByUserId,
            fallbackContent: $fallbackContent,
            mediaMeta: $mediaMeta,
        );
    }
}
