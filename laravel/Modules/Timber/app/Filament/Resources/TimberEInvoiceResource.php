<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\TransmitTimberEInvoiceAction;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource\Pages;
use Modules\Timber\Models\TimberEInvoice;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberEInvoiceResource extends Resource
{
    protected static ?string $model = TimberEInvoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = 'Fatture elettroniche';

    protected static ?int $navigationSort = 24;

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
        return false;
    }

    public static function getNavigationGroup(): string
    {
        return 'Fatturazione';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'access',
            operatorFeature: 'access',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Fattura elettronica';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fatture elettroniche';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('invoice.number')
                    ->label('Fattura')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.customer.business_name')
                    ->label('Cliente')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('direction')
                    ->label('Flusso')
                    ->formatStateUsing(fn (?string $state): string => $state === 'in' ? 'Ricevuta' : 'Emessa')
                    ->badge(),
                Tables\Columns\TextColumn::make('sdi_status')
                    ->label('Stato SdI')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timber.sdi_statuses')[$state] ?? (string) $state)
                    ->color(fn (?string $state): string => match ($state) {
                        'consegnata' => 'success',
                        'inviata' => 'info',
                        'scartata', 'non_consegnata' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('File')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('transmitted_at')
                    ->label('Trasmessa il')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sdi_status')
                    ->label('Stato SdI')
                    ->options(self::configMap('timber.sdi_statuses')),
                Tables\Filters\SelectFilter::make('direction')
                    ->label('Flusso')
                    ->options(['out' => 'Emesse', 'in' => 'Ricevute']),
            ])
            ->actions([
                self::transmitAction(),
                self::downloadXmlAction(),
            ]);
    }

    public static function transmitAction(): Action
    {
        return Action::make('transmit')
            ->label('Segna inviata a SdI')
            ->icon('heroicon-o-paper-airplane')
            ->color('info')
            ->requiresConfirmation()
            ->visible(fn (TimberEInvoice $record): bool => $record->sdi_status === 'da_inviare')
            ->action(function (TimberEInvoice $record): void {
                try {
                    app(TransmitTimberEInvoiceAction::class)->execute($record);
                    Notification::make()->title('Fattura elettronica trasmessa a SdI')->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    public static function downloadXmlAction(): Action
    {
        return Action::make('downloadXml')
            ->label('Scarica XML')
            ->icon('heroicon-o-arrow-down-tray')
            ->visible(fn (TimberEInvoice $record): bool => is_string($record->xml_content) && $record->xml_content !== '')
            ->action(function (TimberEInvoice $record): StreamedResponse {
                $xml = (string) $record->xml_content;
                $name = is_string($record->file_name) && $record->file_name !== '' ? $record->file_name : 'fattura-elettronica.xml';

                return response()->streamDownload(static function () use ($xml): void {
                    echo $xml;
                }, $name, ['Content-Type' => 'application/xml']);
            });
    }

    /**
     * @return array<string, string>
     */
    private static function configMap(string $key): array
    {
        $value = config($key, []);

        if (! is_array($value)) {
            return [];
        }

        /** @var array<string, string> $map */
        $map = [];
        foreach ($value as $code => $label) {
            $map[(string) $code] = is_string($label) ? $label : (string) $code;
        }

        return $map;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberEInvoices::route('/'),
        ];
    }
}
