<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\GenerateTimberCustomerLabelPdfAction;
use Modules\Timber\Actions\GenerateTimberCustomerSheetPdfAction;
use Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;
use Modules\Timber\Models\TimberCustomer;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberCustomerResource extends XotBaseResource
{
    protected static ?string $model = TimberCustomer::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Clienti';

    protected static ?int $navigationSort = 85;

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

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
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
        return 'Cliente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Clienti';
    }

    public static function printSheetAction(): Action
    {
        return Action::make('printSheet')
            ->icon('heroicon-o-document-text')
            ->action(function (TimberCustomer $record): StreamedResponse {
                $pdf = app(GenerateTimberCustomerSheetPdfAction::class)->execute($record);

                return response()->streamDownload(static function () use ($pdf): void {
                    echo $pdf;
                }, 'scheda-cliente-'.$record->id.'.pdf', ['Content-Type' => 'application/pdf']);
            });
    }

    public static function printLabelAction(): Action
    {
        return Action::make('printLabel')
            ->icon('heroicon-o-tag')
            ->action(function (TimberCustomer $record): StreamedResponse {
                $pdf = app(GenerateTimberCustomerLabelPdfAction::class)->execute($record);

                return response()->streamDownload(static function () use ($pdf): void {
                    echo $pdf;
                }, 'etichetta-cliente-'.$record->id.'.pdf', ['Content-Type' => 'application/pdf']);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberCustomers::route('/'),
            'create' => Pages\CreateTimberCustomer::route('/create'),
            'view' => Pages\ViewTimberCustomer::route('/{record}'),
            'edit' => Pages\EditTimberCustomer::route('/{record}/edit'),
        ];
    }
}
