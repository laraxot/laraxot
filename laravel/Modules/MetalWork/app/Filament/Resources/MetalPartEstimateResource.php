<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Saved estimates, with the rows they were computed from. This is what makes a
 * price defensible months later, once the material has moved: the rows hold the
 * cost of the day the quotation went out.
 */
final class MetalPartEstimateResource extends XotBaseResource
{
    protected static ?string $model = MetalPartEstimate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.part_estimate');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.part_estimates');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [
            MetalPartEstimateResource\RelationManagers\LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalPartEstimateResource\Pages\ListMetalPartEstimates::route('/'),
        ];
    }
}
