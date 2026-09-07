<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Document\Models\Media;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ComplianceDocumentResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = ComplianceDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function getNavigationGroup(): string
    {
        return __('compliance.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('compliance.document');
    }

    public static function getPluralModelLabel(): string
    {
        return __('compliance.documents');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplianceDocuments::route('/'),
            'create' => Pages\CreateComplianceDocument::route('/create'),
            'view' => Pages\ViewComplianceDocument::route('/{record}'),
            'edit' => Pages\EditComplianceDocument::route('/{record}/edit'),
        ];
    }

    public static function filePreview(?ComplianceDocument $record): HtmlString
    {
        $media = $record?->media;
        if (! $media instanceof Media || ! is_string($media->path) || trim($media->path) === '') {
            return new HtmlString('<span class="text-sm text-gray-500">Nessun file allegato.</span>');
        }

        $disk = (string) ($media->disk ?: 'public');
        /** @var FilesystemAdapter $filesystem */
        $filesystem = Storage::disk($disk);
        $url = $filesystem->url($media->path);
        $title = e((string) ($media->title ?: $record?->title ?: 'Documento'));
        $mimeType = (string) ($media->mime_type ?? '');
        $isImage = str_starts_with($mimeType, 'image/');

        $preview = $isImage
            ? sprintf(
                '<a href="%s" target="_blank" rel="noopener"><img src="%s" alt="%s" class="max-h-[32rem] w-full rounded-xl border border-gray-200 object-contain shadow-sm dark:border-gray-700" /></a>',
                e($url),
                e($url),
                $title,
            )
            : '<div class="rounded-xl border border-dashed border-gray-300 p-6 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-300">Anteprima non disponibile per questo tipo di file.</div>';

        return new HtmlString(sprintf(
            '<div class="space-y-3">%s<div class="flex flex-wrap gap-2"><a class="fi-btn fi-color-primary fi-size-sm" href="%s" target="_blank" rel="noopener">Apri file</a><a class="fi-btn fi-color-gray fi-size-sm" href="%s" download>Scarica file</a></div></div>',
            $preview,
            e($url),
            e($url),
        ));
    }
}
