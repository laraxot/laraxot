<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Timber\Actions\ExportTimberPriceListCsvAction;
use Modules\Timber\Actions\ImportTimberPriceListCsvAction;
use Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;
use Modules\Timber\Models\TimberPriceList;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberPriceListResource extends XotBaseResource
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

    /** @return array<int|string, Component> */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->components([
                    Grid::make(3)->components([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
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
                                ->options(fn (): array => TimberProduct::query()
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('net_price')
                                ->numeric()
                                ->minValue(0)
                                ->required(),
                        ])
                        ->columns(3)
                        ->addActionLabel('Aggiungi prezzo')
                        ->defaultItems(0),
                ]),
        ];
    }

    public static function exportCsvAction(): Action
    {
        return Action::make('exportCsv')
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
            ->icon('heroicon-o-arrow-up-tray')
            ->schema([
                FileUpload::make('file')
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
                    Auth::id(),
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
