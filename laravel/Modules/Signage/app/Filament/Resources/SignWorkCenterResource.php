<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Filament\Resources\SignWorkCenterResource\Pages;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Work centres and the markup each one sells at. Editable because the list is
 * the customer's, not ours: they add the ones we did not guess.
 */
final class SignWorkCenterResource extends XotBaseResource
{
    protected static ?string $model = WorkCenter::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 50;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.work_center');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.work_centers');
    }

    /** @return Builder<WorkCenter> */
    public static function getEloquentQuery(): Builder
    {
        /** @var Builder<WorkCenter> $query */
        $query = parent::getEloquentQuery()->where('context_code', SignageCosting::CONTEXT);

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignWorkCenters::route('/'),
        ];
    }
}
