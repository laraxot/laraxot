<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Catalog\Actions\FetchMetelCatalogViaApiAction;
use Modules\Catalog\Actions\ImportMetelPriceListFileAction;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\RelationManagers\ItemsRelationManager;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use RuntimeException;

final class MetelCatalogResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = MetelCatalog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected const ACCOUNT_FEATURE_ADMIN = 'admin.metel_catalogs';

    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.metel_catalogs';

    public static function getNavigationGroup(): string
    {
        return __('metel.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metel.catalog');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metel.catalogs');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label(__('metel.fields.code'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    TextInput::make('brand_code')
                        ->label(__('metel.fields.brand_code'))
                        ->maxLength(3)
                        ->helperText(__('metel.fields.brand_code_hint')),
                    TextInput::make('name')
                        ->label(__('metel.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('manufacturer')
                        ->label(__('metel.fields.manufacturer'))
                        ->maxLength(255),
                    Select::make('source_type')
                        ->label(__('metel.fields.source_type'))
                        ->options([
                            'file' => __('metel.sources.file'),
                            'api' => __('metel.sources.api'),
                        ])
                        ->default('file')
                        ->required(),
                    TextInput::make('version')
                        ->label(__('metel.fields.version'))
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label(__('metel.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('metel.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand_code')
                    ->label(__('metel.fields.brand_code'))
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('source_type')
                    ->label(__('metel.fields.source_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("metel.sources.{$state}"))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label(__('metel.fields.items_count'))
                    ->counts('items')
                    ->sortable(),
                Tables\Columns\TextColumn::make('imported_at')
                    ->label(__('metel.fields.imported_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('metel.fields.is_active'))
                    ->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('importFile')
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

                        $formatValue = (string) ($data['format'] ?? 'auto');
                        $format = match ($formatValue) {
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
                Action::make('syncApi')
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
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetelCatalogs::route('/'),
            'create' => Pages\CreateMetelCatalog::route('/create'),
            'view' => Pages\ViewMetelCatalog::route('/{record}'),
            'edit' => Pages\EditMetelCatalog::route('/{record}/edit'),
        ];
    }
}
