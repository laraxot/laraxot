<?php

declare(strict_types=1);

namespace Modules\Media\Actions\Video;

/**
 * @see https://github.com/protonemedia/laravel-ffmpeg
 */
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Support\Ffmpeg\MediaExporterResolver;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Spatie\QueueableAction\QueueableAction;

class ConvertVideoAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(string $disk_mp4, string $file_mp4, string $file_new): string
    {
        $media = FFMpeg::fromDisk($disk_mp4);

        $openedMedia = $media->open($file_mp4);

        $exportedMedia = $openedMedia->export();

        $format = new X264();
        $format->setKiloBitrate(1000);

        $exportedMedia->toDisk($disk_mp4);
        $exportedMedia->inFormat($format);
        $exportedMedia->save($file_new);
        $format = new X264();
        $formattedMedia = MediaExporterResolver::from(
            $exportedMedia->toDisk($disk_mp4)
        )->inFormat($format);
        $formattedMedia->save($file_new);

        return Storage::disk($disk_mp4)->url($file_new);
    }
}
