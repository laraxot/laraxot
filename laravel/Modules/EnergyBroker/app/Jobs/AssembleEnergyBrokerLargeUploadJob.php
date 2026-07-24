<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Modules\EnergyBroker\Actions\StoreEnergyBrokerLargeUploadChunkAction;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;
use Throwable;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\hash_file;
use function Safe\stream_copy_to_stream;

final class AssembleEnergyBrokerLargeUploadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $timeout = 0;

    public function __construct(private readonly int $largeUploadId) {}

    public function handle(StoreEnergyBrokerLargeUploadChunkAction $chunks): void
    {
        /** @var EnergyBrokerLargeUpload $upload */
        $upload = EnergyBrokerLargeUpload::query()->findOrFail($this->largeUploadId);

        try {
            $chunkDirectory = storage_path('app/energy-broker/chunks/'.$upload->public_id);
            $targetDirectory = storage_path('app/energy-broker/incoming');
            File::ensureDirectoryExists($targetDirectory);

            $targetPath = $targetDirectory.'/'.$upload->public_id.'-'.basename($upload->original_name);

            try {
                $target = fopen($targetPath, 'wb');
            } catch (Throwable) {
                throw new \RuntimeException('Impossibile creare il file finale.');
            }

            for ($index = 0; $index < $upload->total_chunks; $index++) {
                $chunkPath = $chunkDirectory.'/'.$chunks->chunkFilename($index);

                try {
                    $source = fopen($chunkPath, 'rb');
                } catch (Throwable) {
                    fclose($target);

                    throw new \RuntimeException("Chunk {$index} mancante o non leggibile.");
                }

                stream_copy_to_stream($source, $target);
                fclose($source);
            }

            fclose($target);

            $upload->forceFill([
                'status' => 'queued',
                'stored_path' => $targetPath,
                'file_hash' => (string) hash_file('sha256', $targetPath),
            ])->save();

            File::deleteDirectory($chunkDirectory);

            ImportEnergyBrokerLargeUploadJob::dispatch($upload->id);
        } catch (Throwable $e) {
            $upload->forceFill([
                'status' => 'failed',
                'meta' => [
                    'errors' => [
                        ['stage' => 'assemble', 'error' => $e->getMessage()],
                    ],
                ],
            ])->save();

            throw $e;
        }
    }
}
