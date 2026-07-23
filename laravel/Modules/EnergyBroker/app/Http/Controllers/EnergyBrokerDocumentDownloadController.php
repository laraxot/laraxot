<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class EnergyBrokerDocumentDownloadController extends Controller
{
    public function __invoke(EnergyBrokerDocument $document): StreamedResponse
    {
        Gate::authorize('view', $document);

        $media = $document->media;
        if ($media === null || $media->path === null) {
            abort(404);
        }

        $disk = (string) ($media->disk ?: 'public');
        if (! Storage::disk($disk)->exists($media->path)) {
            abort(404);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);

        return $filesystem->download(
            $media->path,
            self::downloadFilename((string) ($media->title ?: $document->source_file_name), $document),
        );
    }

    private static function downloadFilename(string $filename, EnergyBrokerDocument $document): string
    {
        $basename = basename(str_replace('\\', '/', trim($filename)));

        if ($basename === '' || $basename === '.' || $basename === DIRECTORY_SEPARATOR) {
            return "energy-broker-document-{$document->id}.pdf";
        }

        return str_replace(['/', '\\'], '-', $basename);
    }
}
