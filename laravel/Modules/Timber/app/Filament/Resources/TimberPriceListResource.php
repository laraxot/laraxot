<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Timber\Actions\ExportTimberPriceListCsvAction;
use Modules\Timber\Actions\ImportTimberPriceListCsvAction;
use Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberProduct;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberPriceListResource extends Resource
{
    protected static ?string $model = TimberPriceList::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Listini';

    protected static ?int $navigationSort = 87;

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function getNavigationGroup(): string
    {
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Listino';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Listini';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->components([
                    Grid::make(3)->components([
                        TextInput::make('code')
                            ->label('Codice')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ]),
                ]),

            Section::make('Prezzi prodotti')
                ->components([
                    Repeater::make('items')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('product_id')
                                ->label('Prodotto')
                                ->options(fn (): array => TimberProduct::query()
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('net_price')
                                ->label('Prezzo netto')
                                ->numeric()
                                ->minValue(0)
                                ->required(),
                        ])
                        ->columns(3)
                        ->addActionLabel('Aggiungi prezzo')
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Prezzi')
                    ->counts('items'),
                Tables\Columns\TextColumn::make('versions_count')
                    ->label('Versioni')
                    ->counts('versions')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
            ])
            ->actions([
                self::exportCsvAction(),
                self::importCsvAction(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function exportCsvAction(): Action
    {
        return Action::make('exportCsv')
            ->label('Esporta CSV')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function (TimberPriceList $record): StreamedResponse {
                $csv = app(ExportTimberPriceListCsvAction::class)->execute($record);

                return response()->streamDownload(
                    static function () use ($csv): void {
                        echo $csv;
                    },
                    sprintf('listino-%s.csv', $record->code),
                    ['Content-Type' => 'text/csv'],
                );
            });
    }

    public static function importCsvAction(): Action
    {
        return Action::make('importCsv')
            ->label('Importa CSV')
            ->icon('heroicon-o-arrow-up-tray')
            ->schema([
                FileUpload::make('file')
                    ->label('File CSV (codice prodotto; prezzo)')
                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                    ->storeFiles(false)
                    ->required(),
            ])
            ->action(function (TimberPriceList $record, array $data): void {
                $file = $data['file'] ?? null;
                $content = $file instanceof TemporaryUploadedFile ? (string) $file->get() : '';

                if ($content === '') {
                    Notification::make()->title('File CSV vuoto o non leggibile')->warning()->send();

                    return;
                }

                $result = app(ImportTimberPriceListCsvAction::class)->execute(
                    $record,
                    $content,
                    Auth::id() !== null ? (int) Auth::id() : null,
                );

                Notification::make()
                    ->title('Import completato')
                    ->body($result['imported'].' prezzi aggiornati, '.$result['skipped'].' righe saltate.')
                    ->success()
                    ->send();
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberPriceLists::route('/'),
            'create' => Pages\CreateTimberPriceList::route('/create'),
            'edit' => Pages\EditTimberPriceList::route('/{record}/edit'),
        ];
    }
}
