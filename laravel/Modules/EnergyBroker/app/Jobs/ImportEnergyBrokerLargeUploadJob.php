<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentPdfAction;
use Modules\EnergyBroker\Actions\ImportEnergyBrokerDocumentsFromZipAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerImportBatch;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Throwable;

use function Safe\preg_match;

final class ImportEnergyBrokerLargeUploadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 0;

    public function __construct(private readonly int $largeUploadId) {}

    public function handle(): void
    {
        /** @var EnergyBrokerLargeUpload $upload */
        $upload = EnergyBrokerLargeUpload::query()->findOrFail($this->largeUploadId);

        try {
            $path = (string) $upload->stored_path;
            if ($path === '' || ! is_file($path)) {
                throw new \RuntimeException('File caricato non trovato.');
            }

            $upload->forceFill(['status' => 'processing'])->save();

            if (preg_match('/\.zip$/iu', $upload->original_name)) {
                $result = app(ImportEnergyBrokerDocumentsFromZipAction::class)->execute(
                    zipPath: $path,
                    uploadedByUserId: $upload->uploaded_by_user_id,
                    onBatchCreated: function (EnergyBrokerImportBatch $batch) use ($upload): void {
                        $upload->forceFill([
                            'energy_broker_import_batch_id' => $batch->id,
                            'status' => 'processing',
                        ])->save();
                    },
                );

                $upload->forceFill([
                    'energy_broker_import_batch_id' => $result['batch']->id,
                    'status' => $result['failed'] > 0 ? 'needs_review' : 'completed',
                    'meta' => [
                        'total' => $result['total'],
                        'imported' => $result['imported'],
                        'failed' => $result['failed'],
                        'skipped' => $result['skipped'],
                    ],
                ])->save();

                return;
            }

            if (! preg_match('/\.pdf$/iu', $upload->original_name)) {
                throw new \RuntimeException('Sono supportati solo PDF e ZIP.');
            }

            if (EnergyBrokerDocument::query()->where('file_hash', $upload->file_hash)->exists()) {
                $upload->forceFill([
                    'status' => 'completed',
                    'meta' => ['skipped' => 1, 'reason' => 'Documento gia importato.'],
                ])->save();

                return;
            }

            $document = app(ImportEnergyBrokerDocumentPdfAction::class)->executeFromPath(
                filename: $upload->original_name,
                path: $path,
                uploadedByUserId: $upload->uploaded_by_user_id,
                mediaMeta: [
                    'source' => 'large_upload',
                    'large_upload_public_id' => $upload->public_id,
                ],
            );

            $upload->forceFill([
                'status' => 'completed',
                'meta' => [
                    'document_id' => $document->id,
                    'imported' => 1,
                    'failed' => 0,
                ],
            ])->save();
        } catch (Throwable $e) {
            $upload->forceFill([
                'status' => 'failed',
                'meta' => [
                    'errors' => [
                        ['stage' => 'import', 'error' => $e->getMessage()],
                    ],
                ],
            ])->save();

            throw $e;
        }
    }
}
