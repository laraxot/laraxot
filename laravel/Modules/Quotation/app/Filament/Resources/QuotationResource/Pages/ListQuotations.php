<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Actions\ImportQuotationFromFileAction;
use Modules\Quotation\Filament\Pages\RecordQuotationVoicePage;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use RuntimeException;

final class ListQuotations extends XotBaseListRecords
{
    protected static string $resource = QuotationResource::class;

    /** Office, OpenOffice/LibreOffice, PDF and CSV — the same set accepted as attachments. */
    /** @var list<string> */
    private const IMPORT_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.spreadsheet',
        'text/csv',
        'text/plain',
    ];

    protected function getHeaderActions(): array
    {
        return [
            'voiceNew' => Actions\Action::make('voiceNew')

                ->icon('heroicon-o-microphone')
                ->visible(fn (): bool => RecordQuotationVoicePage::canAccess())
                ->url(fn (): string => RecordQuotationVoicePage::getUrl()),

            'importFromFile' => Actions\Action::make('importFromFile')

                ->icon('heroicon-o-document-arrow-up')
                ->schema([
                    FileUpload::make('file')

                        ->disk('local')
                        ->directory('quotation-imports')
                        ->visibility('private')
                        ->acceptedFileTypes(self::IMPORT_MIME_TYPES)
                        ->maxSize(20480)
                        ->storeFileNamesIn('original_name')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $fileValue = $data['file'];
                    if (is_array($fileValue)) {
                        $fileValue = reset($fileValue);
                    }
                    $path = is_string($fileValue) ? $fileValue : '';
                    $original = $data['original_name'] ?? null;
                    if (is_array($original)) {
                        $original = reset($original);
                    }
                    $originalName = is_string($original) && $original !== '' ? $original : basename($path);

                    try {
                        $quotation = app(ImportQuotationFromFileAction::class)->execute(
                            Storage::disk('local')->path($path),
                            $originalName,
                        );
                    } catch (RuntimeException $e) {
                        Notification::make()->danger()->title(__('quotations.import.failed'))->body($e->getMessage())->send();

                        return;
                    }

                    // Keep the original document attached to the created quotation.
                    $userId = Auth::id();
                    app(AddQuotationAttachmentAction::class)->execute(
                        quotation: $quotation,
                        path: $path,
                        disk: 'local',
                        uploadedByUserId: is_numeric($userId) ? (int) $userId : null,
                        title: $originalName,
                    );

                    Notification::make()->success()->title(__('quotations.import.done'))->send();

                    $this->redirect(QuotationResource::getUrl('view', ['record' => $quotation->id]));
                }),

            'create' => Actions\CreateAction::make(),
        ];
    }
}
