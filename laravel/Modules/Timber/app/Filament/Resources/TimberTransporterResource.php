<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberTransporterResource\Pages;
use Modules\Timber\Models\TimberTransporter;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberTransporterResource extends XotBaseResource
{
    protected static ?string $model = TimberTransporter::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Trasportatori';

    protected static ?int $navigationSort = 91;

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
        return 'Trasportatore';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Trasportatori';
    }

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('legal_name')
                            ->label('Ragione sociale')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('vat_number')
                            ->label('Partita IVA')
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefono')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('vehicle_plate')
                            ->label('Targa mezzo')
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Attivo')
                            ->default(true),
                    ]),

                    Textarea::make('notes')
                        ->label('Note')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberTransporters::route('/'),
            'create' => Pages\CreateTimberTransporter::route('/create'),
            'view' => Pages\ViewTimberTransporter::route('/{record}'),
            'edit' => Pages\EditTimberTransporter::route('/{record}/edit'),
        ];
    }
}
