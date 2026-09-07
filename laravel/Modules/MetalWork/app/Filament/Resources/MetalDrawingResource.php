<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * The archive of parsed drawings. Read-only by design: everything here was
 * measured out of the CAD file, and editing it by hand would detach the number
 * from the drawing it came from. Corrections belong on the estimate, where they
 * are recorded as the operator's own inputs.
 */
final class MetalDrawingResource extends XotBaseResource
{
    protected static ?string $model = MetalDrawing::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.drawing');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.drawings');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalDrawingResource\Pages\ListMetalDrawings::route('/'),
        ];
    }
}
