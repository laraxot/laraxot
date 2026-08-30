<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Modules\Compliance\Actions\AnalyzeComplianceDocumentWithOpenAiAction;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Pages;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Document\Models\Media;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\WorkOrder\Models\WorkOrder;

final class ComplianceDocumentResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                Grid::make(3)->components([
                    Select::make('type')->label(__('compliance.fields.type'))->required()->options(self::documentTypes()),
                    TextInput::make('title')->label(__('compliance.fields.title'))->required()->maxLength(255),
                    Select::make('status')->label(__('compliance.fields.status'))->required()->default('missing')->options(self::statuses()),
                ]),
                Grid::make(3)->components([
                    Toggle::make('is_required')->label(__('compliance.fields.is_required'))->default(true),
                    DatePicker::make('issued_on')->label(__('compliance.fields.issued_on')),
                    DatePicker::make('expires_on')->label(__('compliance.fields.expires_on')),
                ]),
                Select::make('work_order_id')
                    ->label(__('compliance.fields.work_order'))
                    ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                    ->searchable(),
                FileUpload::make('file_path')
                    ->label(__('compliance.fields.file'))
                    ->disk('public')
                    ->directory('media/compliance')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->previewable()
                    ->columnSpanFull(),
                Placeholder::make('file_preview')
                    ->label(__('compliance.fields.file_preview'))
                    ->content(fn (?ComplianceDocument $record): HtmlString => self::filePreview($record))
                    ->columnSpanFull()
                    ->visible(fn (?ComplianceDocument $record): bool => $record?->media instanceof Media),
                Textarea::make('notes')->label(__('compliance.fields.notes'))->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label(__('compliance.fields.type'))->badge()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title')->label(__('compliance.fields.title'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('compliance.fields.status'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('workOrder.title')->label(__('compliance.fields.work_order'))->limit(40)->toggleable(),
                Tables\Columns\TextColumn::make('expires_on')->label(__('compliance.fields.expires_on'))->date()->sortable(),
                Tables\Columns\IconColumn::make('is_required')->label(__('compliance.fields.is_required'))->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')->label(__('compliance.fields.type'))->options(self::documentTypes()),
                SelectFilter::make('status')->label(__('compliance.fields.status'))->options(self::statuses()),
            ])
            ->actions([
                Action::make('analyzeWithAi')
                    ->label(__('compliance.actions.analyze_with_ai'))
                    ->icon('heroicon-o-sparkles')
                    ->color('info')
                    ->action(function (ComplianceDocument $record): void {
                        try {
                            app(AnalyzeComplianceDocumentWithOpenAiAction::class)->execute($record);
                            Notification::make()
                                ->title(__('compliance.messages.ai_analysis_completed'))
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title(__('compliance.messages.ai_analysis_failed'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
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

    /** @return array<string, string> */
    private static function documentTypes(): array
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
    private static function statuses(): array
    {
        return [
            'missing' => 'Mancante',
            'valid' => 'Valido',
            'expiring' => 'In scadenza',
            'expired' => 'Scaduto',
            'waived' => 'Non necessario',
        ];
    }
}
