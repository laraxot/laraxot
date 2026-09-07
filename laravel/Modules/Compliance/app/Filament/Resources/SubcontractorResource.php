<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Compliance\Filament\Resources\SubcontractorResource\Pages;
use Modules\Compliance\Models\Subcontractor;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class SubcontractorResource extends XotBaseResource
{
    use GatesResourceNavigation;

    protected static ?string $model = Subcontractor::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function getNavigationGroup(): string
    {
        return __('compliance.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('compliance.subcontractors');
    }

    public static function getModelLabel(): string
    {
        return __('compliance.subcontractor');
    }

    public static function getPluralModelLabel(): string
    {
        return __('compliance.subcontractors');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubcontractors::route('/'),
            'create' => Pages\CreateSubcontractor::route('/create'),
            'view' => Pages\ViewSubcontractor::route('/{record}'),
            'edit' => Pages\EditSubcontractor::route('/{record}/edit'),
        ];
    }
}
