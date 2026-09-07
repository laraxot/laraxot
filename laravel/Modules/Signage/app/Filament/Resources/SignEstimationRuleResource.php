<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Signage\Filament\Resources\SignEstimationRuleResource\Pages;
use Modules\Signage\Models\SignEstimationRule;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class SignEstimationRuleResource extends XotBaseResource
{
    protected static ?string $model = SignEstimationRule::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?int $navigationSort = 90;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.estimation_rule');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.estimation_rules');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignEstimationRules::route('/'),
        ];
    }
}
