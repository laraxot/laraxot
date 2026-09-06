<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Catalog\Actions\FetchMetelCatalogViaApiAction;
use Modules\Catalog\Actions\ImportMetelPriceListFileAction;
use Modules\Catalog\Filament\Resources\MetelCatalogResource;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use RuntimeException;

final class ListMetelCatalogs extends XotBaseListRecords
{
    protected static string $resource = MetelCatalogResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => Tables\Columns\TextColumn::make('name')
                ->label(__('metel.fields.name'))
                ->searchable()
                ->sortable(),
            'brand_code' => Tables\Columns\TextColumn::make('brand_code')
                ->label(__('metel.fields.brand_code'))
                ->badge()
                ->toggleable(),
            'source_type' => Tables\Columns\TextColumn::make('source_type')
                ->label(__('metel.fields.source_type'))
                ->badge()
                ->formatStateUsing(fn (string $state): string => __("metel.sources.{$state}"))
                ->toggleable(),
            'items_count' => Tables\Columns\TextColumn::make('items_count')
                ->label(__('metel.fields.items_count'))
                ->counts('items')
                ->sortable(),
            'imported_at' => Tables\Columns\TextColumn::make('imported_at')
                ->label(__('metel.fields.imported_at'))
                ->dateTime()
                ->sortable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label(__('metel.fields.is_active'))
                ->boolean(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'importFile' => Action::make('importFile')
                ->label(__('metel.actions.import_file'))
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Select::make('format')
                        ->label(__('metel.import.format'))
                        ->options([
                            'auto' => __('metel.import.format_auto'),
                            'csv' => __('metel.import.format_csv'),
                            'fixed' => __('metel.import.format_fixed'),
                        ])
                        ->default('auto')
                        ->required(),
                    FileUpload::make('file')
                        ->label(__('metel.import.file'))
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/octet-stream'])
                        ->storeFiles(false)
                        ->required(),
                ])
                ->action(function (array $data, MetelCatalog $record): void {
                    $file = $data['file'];
                    if (is_array($file)) {
                        $file = reset($file);
                    }

                    if (! $file instanceof TemporaryUploadedFile) {
                        Notification::make()->danger()->title(__('metel.import.failed'))->send();

                        return;
                    }

                    $format = match (is_string($data['format'] ?? null) ? $data['format'] : 'auto') {
                        'csv' => 'csv',
                        'fixed' => 'fixed',
                        default => 'auto',
                    };

                    $result = app(ImportMetelPriceListFileAction::class)->execute(
                        catalog: $record,
                        content: (string) $file->get(),
                        format: $format,
                    );

                    Notification::make()
                        ->success()
                        ->title(__('metel.import.done'))
                        ->body(__('metel.import.summary', $result))
                        ->send();
                }),
            'syncApi' => Action::make('syncApi')
                ->label(__('metel.actions.sync_api'))
                ->icon('heroicon-o-cloud-arrow-down')
                ->requiresConfirmation()
                ->action(function (MetelCatalog $record): void {
                    try {
                        $result = app(FetchMetelCatalogViaApiAction::class)->execute($record);
                    } catch (RuntimeException $e) {
                        Notification::make()->danger()->title(__('metel.import.failed'))->body($e->getMessage())->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title(__('metel.import.done'))
                        ->body(__('metel.import.summary', $result))
                        ->send();
                }),
            'delete' => DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
        ];
    }
}
