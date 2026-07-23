<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Modules\EnergyBroker\Jobs\AssembleEnergyBrokerLargeUploadJob;
use Modules\EnergyBroker\Models\EnergyBrokerLargeUpload;

final class CompleteEnergyBrokerLargeUploadAction
{
    public function execute(EnergyBrokerLargeUpload $upload): EnergyBrokerLargeUpload
    {
        if ($upload->status !== 'uploading') {
            throw new \RuntimeException('Upload non completabile.');
        }

        if ($upload->received_chunks !== $upload->total_chunks) {
            throw new \RuntimeException('Upload incompleto: mancano uno o piu chunk.');
        }

        $upload->forceFill([
            'status' => 'assembling',
            'completed_at' => now(),
        ])->save();

        AssembleEnergyBrokerLargeUploadJob::dispatch($upload->id);

        return $upload->refresh();
    }
}
