<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberSupplierResource\Pages;
use Modules\Timber\Models\TimberSupplier;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberSupplierResource extends XotBaseResource
{
    protected static ?string $model = TimberSupplier::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Fornitori';

    protected static ?int $navigationSort = 90;

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
        return 'Fornitore';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Fornitori';
    }

    /** @return array<int|string, Component> */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('legal_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('vat_number')
                            ->maxLength(255),

                        TextInput::make('tax_code')
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->maxLength(255),

                        TextInput::make('certifications')
                            ->placeholder('FSC, PEFC')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(255),

                        TextInput::make('province')
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->default(true),
                    ]),

                    Textarea::make('notes')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Section::make('Foglio appunti')
                ->description('Annotazioni datate sul fornitore')
                ->columnSpanFull()
                ->components([
                    Repeater::make('contactNotes')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Textarea::make('body')
                                ->required()
                                ->rows(2),
                        ])
                        ->addActionLabel('Aggiungi appunto')
                        ->defaultItems(0),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberSuppliers::route('/'),
            'create' => Pages\CreateTimberSupplier::route('/create'),
            'view' => Pages\ViewTimberSupplier::route('/{record}'),
            'edit' => Pages\EditTimberSupplier::route('/{record}/edit'),
        ];
    }
}
