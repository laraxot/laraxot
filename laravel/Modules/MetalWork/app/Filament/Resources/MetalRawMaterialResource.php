<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * The raw-material list, built by importing the purchase invoices rather than
 * typed by hand. The current price column is the last invoiced one — the same
 * number the estimate uses — so a wrong quotation can be traced to the invoice
 * behind it in one click.
 */
final class MetalRawMaterialResource extends XotBaseResource
{
    protected static ?string $model = MetalRawMaterial::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.raw_material');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.raw_materials');
    }

    /**
     * @return array<string, string>
     */
    public static function familyOptions(): array
    {
        $families = ['sheet', 'tube', 'beam', 'flat', 'angle', 'round', 'square', 'oxy_cut', 'mesh', 'other'];

        return array_combine(
            $families,
            array_map(static fn (string $family): string => (string) __('metalwork::metalwork.families.'.$family), $families),
        );
    }

    public static function getRelations(): array
    {
        return [
            MetalRawMaterialResource\RelationManagers\SupplierPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalRawMaterialResource\Pages\ListMetalRawMaterials::route('/'),
            'edit' => MetalRawMaterialResource\Pages\EditMetalRawMaterial::route('/{record}/edit'),
        ];
    }
}
