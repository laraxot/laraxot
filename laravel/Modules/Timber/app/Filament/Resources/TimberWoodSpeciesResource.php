<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;
use Modules\Timber\Models\TimberWoodSpecies;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberWoodSpeciesResource extends XotBaseResource
{
    protected static ?string $model = TimberWoodSpecies::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Specie legnose';

    protected static ?int $navigationSort = 95;

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
        return 'Specie legnosa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Specie legnose';
    }

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                            $code = $get('code');
                            if (($code === null || $code === '') && is_string($state)) {
                                $set('code', Str::slug($state, '_'));
                            }
                        }),
                    TextInput::make('code')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Codice stabile usato internamente (es. "abete"). Non cambiarlo dopo l\'uso.'),
                    Grid::make(2)->components([
                        TextInput::make('sort_order')
                            ->integer()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true),
                    ]),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberWoodSpecies::route('/'),
            'create' => Pages\CreateTimberWoodSpecies::route('/create'),
            'edit' => Pages\EditTimberWoodSpecies::route('/{record}/edit'),
        ];
    }
}
