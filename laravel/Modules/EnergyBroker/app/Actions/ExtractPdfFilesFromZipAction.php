<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Support\Facades\File;
use ZipArchive;

final class ExtractPdfFilesFromZipAction
{
    /**
     * @return array<int, array{original_name: string, extracted_path: string, file_hash: string}>
     */
    public function execute(string $zipPath): array
    {
        if (! is_file($zipPath)) {
            throw new \RuntimeException('Archivio ZIP non trovato.');
        }

        $archive = new ZipArchive();
        if ($archive->open($zipPath) !== true) {
            throw new \RuntimeException('Impossibile aprire l\'archivio ZIP.');
        }

        $targetDirectory = storage_path('app/tmp/energy-broker/' . str()->uuid());
        File::ensureDirectoryExists($targetDirectory);

        $entries = [];

        for ($index = 0; $index < $archive->numFiles; $index++) {
            $stat = $archive->statIndex($index);
            if (! is_array($stat)) {
                continue;
            }

            $originalName = (string) ($stat['name'] ?? '');
            if ($originalName === '' || str_ends_with($originalName, '/')) {
                continue;
            }

            if (str_contains($originalName, '..') || str_starts_with($originalName, '/') || str_contains($originalName, '\\')) {
                continue;
            }

            if (! preg_match('/\.pdf$/iu', $originalName)) {
                continue;
            }

            $filename = str_pad((string) $index, 5, '0', STR_PAD_LEFT) . '-' . basename($originalName);
            $extractedPath = $targetDirectory . '/' . $filename;

            if (! $archive->extractTo($targetDirectory, [$originalName])) {
                continue;
            }

            $temporaryPath = $targetDirectory . '/' . $originalName;
            if (! is_file($temporaryPath) || filesize($temporaryPath) === 0) {
                continue;
            }

            File::ensureDirectoryExists(dirname($extractedPath));
            rename($temporaryPath, $extractedPath);

            $entries[] = [
                'original_name' => $originalName,
                'extracted_path' => $extractedPath,
                'file_hash' => (string) hash_file('sha256', $extractedPath),
            ];
        }

        $archive->close();

        return $entries;
    }
}
