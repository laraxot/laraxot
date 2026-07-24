<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;

/**
 * @extends Factory<EnergyBrokerLargeUpload>
 */
class EnergyBrokerLargeUploadFactory extends Factory
{
    /** @var class-string<EnergyBrokerLargeUpload> */
    protected $model = EnergyBrokerLargeUpload::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $chunkSize = 1024 * 1024;
        $totalChunks = $this->faker->numberBetween(1, 20);
        $receivedChunks = $this->faker->numberBetween(0, $totalChunks);

        return [
            'uploaded_by_user_id' => null,
            'energy_broker_import_batch_id' => null,
            'original_name' => $this->faker->slug(3).'.zip',
            'mime_type' => 'application/zip',
            'total_size' => $chunkSize * $totalChunks,
            'chunk_size' => $chunkSize,
            'total_chunks' => $totalChunks,
            'received_chunks' => $receivedChunks,
            'status' => $receivedChunks >= $totalChunks ? 'completed' : 'uploading',
            'stored_path' => $this->faker->optional()->filePath(),
            'file_hash' => $this->faker->optional()->sha256(),
            'received_chunk_indexes' => range(0, max(0, $receivedChunks - 1)),
            'meta' => null,
            'completed_at' => $receivedChunks >= $totalChunks ? $this->faker->dateTimeThisYear() : null,
        ];
    }
}
