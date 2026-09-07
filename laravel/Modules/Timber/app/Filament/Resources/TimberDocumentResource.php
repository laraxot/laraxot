<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\PostTimberDocumentStockAction;
use Modules\Timber\Filament\Resources\TimberDocumentResource\Pages;
use Modules\Timber\Models\TimberDocument;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Queries\GetTimberProductStockByLotQuery;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberDocumentResource extends XotBaseResource
{
    protected static ?string $model = TimberDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documenti';

    protected static ?int $navigationSort = 30;

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

    public static function getNavigationGroup(): string
    {
        return 'Magazzino';
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
        return 'Documento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Documenti';
    }

    public static function postToStockAction(): Action
    {
        return Action::make('postToStock')
            ->icon('heroicon-o-arrow-down-on-square')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (TimberDocument $record): bool => $record->hasStockImpact() && $record->stock_posted_at === null)
            ->action(function (TimberDocument $record): void {
                try {
                    app(PostTimberDocumentStockAction::class)->execute($record, Auth::id());
                    Notification::make()->title('Documento registrato a magazzino')->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    private static function formatQuantity(float $quantity): string
    {
        return rtrim(rtrim(number_format($quantity, 3, ',', '.'), '0'), ',');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberDocuments::route('/'),
            'create' => Pages\CreateTimberDocument::route('/create'),
            'view' => Pages\ViewTimberDocument::route('/{record}'),
            'edit' => Pages\EditTimberDocument::route('/{record}/edit'),
        ];
    }
}
