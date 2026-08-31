<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Document\Actions\CreateMediaFromStoredFileAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;
use Spatie\QueueableAction\QueueableAction;

use function Safe\fclose;
use function Safe\fopen;

final class ImportEnergyBrokerDocumentsFromZipAction
{
    use QueueableAction;

    /**
     * @return array{batch: EnergyBrokerImportBatch, total: int, imported: int, failed: int, skipped: int}
     */
    public function execute(string $zipPath, int|string|null $uploadedByUserId = null, ?callable $onBatchCreated = null): array
    {
        $entries = app(ExtractPdfFilesFromZipAction::class)->execute($zipPath);

        /** @var EnergyBrokerImportBatch $batch */
        $batch = EnergyBrokerImportBatch::query()->create([
            'uploaded_by_user_id' => $uploadedByUserId,
            'source_name' => basename($zipPath),
            'source_type' => 'zip_upload',
            'status' => 'processing',
            'total_files' => count($entries),
            'processed_files' => 0,
            'meta' => [
                'errors' => [],
            ],
        ]);

        if ($onBatchCreated !== null) {
            $onBatchCreated($batch);
        }

        $imported = 0;
        $failed = 0;
        $skipped = 0;
        $errors = [];

        foreach ($entries as $entry) {
            $documentToMarkFailed = null;

            try {
                /** @var EnergyBrokerDocument|null $existingDocument */
                $existingDocument = EnergyBrokerDocument::query()
                    ->where('file_hash', $entry['file_hash'])
                    ->orderByDesc('id')
                    ->first();

                if ($existingDocument instanceof EnergyBrokerDocument && $existingDocument->analysis_status === 'failed') {
                    $documentToMarkFailed = $existingDocument;
                    if ($existingDocument->media_id === null) {
                        $this->attachFailedPdf(
                            document: $existingDocument,
                            extractedPath: $entry['extracted_path'],
                            originalName: $entry['original_name'],
                            uploadedByUserId: $uploadedByUserId,
                            fileHash: $entry['file_hash'],
                        );
                    }

                    app(ReprocessEnergyBrokerDocumentPdfAction::class)->executeFromPath(
                        document: $existingDocument,
                        path: $entry['extracted_path'],
                        filename: $entry['original_name'],
                    );

                    $imported++;
                } elseif ($existingDocument instanceof EnergyBrokerDocument) {
                    $skipped++;
                } else {
                    app(ImportEnergyBrokerDocumentPdfAction::class)->executeFromPath(
                        filename: $entry['original_name'],
                        path: $entry['extracted_path'],
                        batchId: $batch->id,
                        uploadedByUserId: $uploadedByUserId,
                        mediaMeta: [
                            'source' => 'zip_upload',
                            'file_hash' => $entry['file_hash'],
                        ],
                    );

                    $imported++;
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'file' => $entry['original_name'],
                    'error' => $e->getMessage(),
                ];

                if ($documentToMarkFailed instanceof EnergyBrokerDocument) {
                    $failedDocument = $documentToMarkFailed;
                    $failedDocument->forceFill([
                        'analysis_status' => 'failed',
                        'analysis_payload' => [
                            'error' => $e->getMessage(),
                        ],
                        'processed_at' => now(),
                    ])->save();
                } else {
                    /** @var EnergyBrokerDocument $failedDocument */
                    $failedDocument = EnergyBrokerDocument::query()->create([
                        'energy_broker_import_batch_id' => $batch->id,
                        'source_file_name' => $entry['original_name'],
                        'file_hash' => $entry['file_hash'],
                        'document_type' => 'unknown',
                        'service_type' => 'unknown',
                        'analysis_status' => 'failed',
                        'raw_text' => null,
                        'analysis_payload' => [
                            'error' => $e->getMessage(),
                        ],
                        'normalized_payload' => [],
                        'processed_at' => now(),
                    ]);
                }

                if ($failedDocument->media_id === null) {
                    $this->attachFailedPdf(
                        document: $failedDocument,
                        extractedPath: $entry['extracted_path'],
                        originalName: $entry['original_name'],
                        uploadedByUserId: $uploadedByUserId,
                        fileHash: $entry['file_hash'],
                    );
                }
            }

            $batch->forceFill([
                'processed_files' => $imported + $failed + $skipped,
            ])->save();
        }

        $batch->forceFill([
            'status' => $failed > 0 ? 'needs_review' : 'completed',
            'meta' => [
                'errors' => $errors,
            ],
        ])->save();

        return [
            'batch' => $batch->refresh(),
            'total' => count($entries),
            'imported' => $imported,
            'failed' => $failed,
            'skipped' => $skipped,
        ];
    }

    private function attachFailedPdf(
        EnergyBrokerDocument $document,
        string $extractedPath,
        string $originalName,
        int|string|null $uploadedByUserId,
        string $fileHash,
    ): void {
        if (! is_file($extractedPath)) {
            return;
        }

        $storedPath = 'media/energy-broker/documents/'.Str::uuid().'-'.basename($originalName);

        try {
            $source = fopen($extractedPath, 'rb');
        } catch (\Throwable) {
            return;
        }

        Storage::disk('public')->put($storedPath, $source);
        if (is_resource($source)) {
            fclose($source);
        }

        $media = app(CreateMediaFromStoredFileAction::class)->execute(
            mediable: $document,
            kind: 'energy_broker_document',
            disk: 'public',
            path: $storedPath,
            uploadedByUserId: $uploadedByUserId,
            title: $originalName,
            meta: [
                'source' => 'zip_upload_failed',
                'file_hash' => $fileHash,
            ],
        );

        $document->forceFill([
            'media_id' => $media->id,
        ])->save();
    }
}
