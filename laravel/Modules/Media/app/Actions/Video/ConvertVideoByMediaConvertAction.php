<?php

/**
 * @see https://github.com/protonemedia/laravel-ffmpeg
 * Azione per convertire un video utilizzando il modello MediaConvert.
 */

declare(strict_types=1);

namespace Modules\Media\Actions\Video;

use Exception;
use Modules\Media\Datas\ConvertData;
use Modules\Media\Models\MediaConvert;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Spatie\QueueableAction\QueueableAction;

/**
 * Classe per convertire video utilizzando MediaConvert e tenere traccia del progresso.
 */
class ConvertVideoByMediaConvertAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(ConvertData $data, MediaConvert $record): string
    {
        if (! $data->exists()) {
            throw new Exception('Il file non esiste');
        }

        $format = $data->getFFMpegFormat();
        $file_new = $record->converted_file;

        if (! $file_new) {
            throw new Exception('Il nome del file convertito non è stato specificato');
        }

        // Instanziamo il formato prima di usarlo
        $formatInstance = new $format();

        $export = FFMpeg::fromDisk($data->disk)
            ->open($data->file)
            ->export()
            ->onProgress(function (float $percentage, float $remaining, float $rate) use ($record): void {
                $record->update([
                    'percentage' => $percentage,
                    'remaining' => $remaining,
                    'rate' => $rate,
                ]);
            })
            // Utilizziamo il formato istanziato come parametro
            ->inFormat($formatInstance);

        // addFilter() e' inoltrato al driver PHPFFMpeg via __call/@mixin: la sua
        // firma dichiarata restituisce il tipo del driver, non del MediaExporter.
        // Non lo si concatena per non perdere il tipo corretto di $export.
        $export->addFilter('-preset', 'ultrafast');

        $export->save($file_new);

        $record->update([
            'status' => 'completed',
        ]);

        return $file_new;
    }
}
