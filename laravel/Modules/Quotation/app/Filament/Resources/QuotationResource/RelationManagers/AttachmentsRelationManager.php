<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Actions\QueueQuotationAttachmentRewriteAction;
use Modules\Quotation\Models\Quotation;

final class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    /**
     * Single-file document types requested by trade (plumbing) quotations:
     * Office, OpenOffice/LibreOffice, CSV/TXT, PDF and images.
     */
    private const ACCEPTED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.presentation',
        'text/csv',
        'text/plain',
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('quotations.attachments.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('path')
                ->label(__('quotations.attachments.file'))
                ->disk('local')
                ->directory(fn (): string => 'quotation-attachments/'.$this->getOwnerRecord()->getKey())
                ->visibility('private')
                ->acceptedFileTypes(self::ACCEPTED_MIME_TYPES)
                ->maxSize(20480)
                ->storeFileNamesIn('original_name')
                ->required()
                ->columnSpanFull(),
            Hidden::make('original_name'),
            TextInput::make('title')
                ->label(__('quotations.attachments.file_title'))
                ->helperText(__('quotations.attachments.file_title_hint'))
                ->maxLength(255)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('kind', 'document'))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('quotations.attachments.file_title'))
                    ->formatStateUsing(fn (Media $record): string => (string) ($record->title ?: basename((string) $record->path)))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mime_type')
                    ->label(__('quotations.attachments.type'))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('size_bytes')
                    ->label(__('quotations.attachments.size'))
                    ->formatStateUsing(fn (?int $state): string => $state !== null ? number_format($state / 1024, 0).' KB' : '—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('quotations.attachments.uploaded_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('reprepareAttachment')
                    ->label(__('quotations.actions.reprepare_attachment'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (): bool => (bool) $this->getOwnerRecord()->source_quotation_id)
                    ->action(function (): void {
                        /** @var Quotation $quotation */
                        $quotation = $this->getOwnerRecord();
                        $userId = Auth::id();
                        $queued = app(QueueQuotationAttachmentRewriteAction::class)->execute(
                            $quotation,
                            '',
                            is_numeric($userId) ? (int) $userId : null,
                        );

                        Notification::make()
                            ->title(__($queued ? 'quotations.actions.reprepare_queued' : 'quotations.actions.reprepare_nothing'))
                            ->{$queued ? 'success' : 'warning'}()
                            ->send();
                    }),
                CreateAction::make()
                    ->label(__('quotations.attachments.add'))
                    ->using(function (array $data): Media {
                        /** @var Quotation $quotation */
                        $quotation = $this->getOwnerRecord();

                        $path = is_array($data['path']) ? (string) reset($data['path']) : (string) $data['path'];

                        $original = $data['original_name'] ?? null;
                        if (is_array($original)) {
                            $original = reset($original);
                        }

                        $title = trim((string) ($data['title'] ?? ''));
                        if ($title === '') {
                            $title = is_string($original) && $original !== '' ? $original : basename($path);
                        }

                        $userId = Auth::id();

                        return app(AddQuotationAttachmentAction::class)->execute(
                            quotation: $quotation,
                            path: $path,
                            disk: 'local',
                            uploadedByUserId: is_numeric($userId) ? (int) $userId : null,
                            title: $title,
                        );
                    }),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('quotations.attachments.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Media $record) {
                        $name = $record->title ?: basename((string) $record->path);

                        return Storage::disk((string) $record->disk)->download((string) $record->path, $name);
                    }),
                DeleteAction::make()
                    ->before(function (Media $record): void {
                        Storage::disk((string) $record->disk)->delete((string) $record->path);
                    }),
            ]);
    }
}
