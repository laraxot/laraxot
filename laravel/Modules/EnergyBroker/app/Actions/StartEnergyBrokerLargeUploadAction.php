<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Spatie\QueueableAction\QueueableAction;

final class StartEnergyBrokerLargeUploadAction
{
    use QueueableAction;

    public function execute(
        string $originalName,
        int $totalSize,
        int $chunkSize,
        int|string|null $uploadedByUserId = null,
        ?string $mimeType = null,
    ): EnergyBrokerLargeUpload {
        if (trim($originalName) === '') {
            throw new \RuntimeException('Nome file mancante.');
        }

        if ($totalSize <= 0 || $chunkSize <= 0) {
            throw new \RuntimeException('Dimensione file o chunk non valida.');
        }

        $totalChunks = (int) ceil($totalSize / $chunkSize);
        if ($totalChunks <= 0) {
            throw new \RuntimeException('Numero chunk non valido.');
        }

        /** @var EnergyBrokerLargeUpload $upload */
        $upload = EnergyBrokerLargeUpload::query()->create([
            'uploaded_by_user_id' => $uploadedByUserId,
            'original_name' => basename($originalName),
            'mime_type' => $mimeType,
            'total_size' => $totalSize,
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
            'received_chunks' => 0,
            'status' => 'uploading',
            'received_chunk_indexes' => [],
            'meta' => [
                'errors' => [],
            ],
        ]);

        return $upload;
    }
}
