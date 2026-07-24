<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Spatie\QueueableAction\QueueableAction;

final class StoreEnergyBrokerLargeUploadChunkAction
{
    use QueueableAction;

    public function execute(EnergyBrokerLargeUpload $upload, int $chunkIndex, UploadedFile $chunk): EnergyBrokerLargeUpload
    {
        if ($upload->status !== 'uploading') {
            throw new \RuntimeException('Upload non modificabile.');
        }

        if ($chunkIndex < 0 || $chunkIndex >= $upload->total_chunks) {
            throw new \RuntimeException('Indice chunk non valido.');
        }

        $directory = storage_path('app/energy-broker/chunks/'.$upload->public_id);
        File::ensureDirectoryExists($directory);

        $chunk->move($directory, $this->chunkFilename($chunkIndex));

        $received = is_array($upload->received_chunk_indexes) ? $upload->received_chunk_indexes : [];
        $received[] = $chunkIndex;
        $received = array_values(array_unique(array_map('intval', $received)));
        sort($received);

        $upload->forceFill([
            'received_chunk_indexes' => $received,
            'received_chunks' => count($received),
        ])->save();

        return $upload->refresh();
    }

    public function chunkFilename(int $chunkIndex): string
    {
        return str_pad((string) $chunkIndex, 8, '0', STR_PAD_LEFT).'.part';
    }
}
