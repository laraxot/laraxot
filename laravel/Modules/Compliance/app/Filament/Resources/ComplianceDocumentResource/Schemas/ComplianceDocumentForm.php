<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Document\Models\Media;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class ComplianceDocumentForm extends XotBaseResourceForm
{
    protected static string $resource = ComplianceDocumentResource::class;

    public function getFormColumns(): int
    {
        return 3;
    }

    /**
     * @return array<int|string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(3)->components([
                    'type' => Select::make('type')->required()->options(self::documentTypes()),
                    'title' => TextInput::make('title')->required()->maxLength(255),
                    'status' => Select::make('status')->required()->default('missing')->options(self::statuses()),
                ]),
                'grid2' => Grid::make(3)->components([
                    'is_required' => Toggle::make('is_required')->default(true),
                    'issued_on' => DatePicker::make('issued_on'),
                    'expires_on' => DatePicker::make('expires_on'),
                ]),
                'work_order_id' => Select::make('work_order_id')

                    ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                    ->searchable(),
                'file_path' => FileUpload::make('file_path')

                    ->disk('public')
                    ->directory('media/compliance')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->previewable()
                    ->columnSpanFull(),
                'file_preview' => TextEntry::make('file_preview')

                    ->state(fn (?ComplianceDocument $record): HtmlString => self::filePreview($record))
                    ->columnSpanFull()
                    ->visible(fn (?ComplianceDocument $record): bool => $record?->media instanceof Media),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    /** @return array<string, string> */
    public static function documentTypes(): array
    {
        return [
            'durc' => 'DURC',
            'pos' => 'POS',
            'psc' => 'PSC',
            'dvr' => 'DVR',
            'dico' => 'DICO',
            'dico_attachment' => 'Allegato DICO',
            'building_permit' => 'Titolo edilizio',
            'training' => 'Attestato',
            'medical_fitness' => 'Idoneita sanitaria',
            'dpi_delivery' => 'Consegna DPI',
            'insurance' => 'Polizza',
            'equipment_manual' => 'Manuale attrezzatura',
            'equipment_check' => 'Verifica attrezzatura',
            'other' => 'Altro',
        ];
    }

    /** @return array<string, string> */
    public static function statuses(): array
    {
        return [
            'missing' => 'Mancante',
            'valid' => 'Valido',
            'expiring' => 'In scadenza',
            'expired' => 'Scaduto',
            'waived' => 'Non necessario',
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
