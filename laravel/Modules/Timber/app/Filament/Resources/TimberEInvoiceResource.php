<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\TransmitTimberEInvoiceAction;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource\Pages;
use Modules\Timber\Models\TimberEInvoice;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberEInvoiceResource extends XotBaseResource
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

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [];
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberEInvoices::route('/'),
        ];
    }
}
