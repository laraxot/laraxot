<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Filament\Resources\ResellerResource\Pages;
use Modules\Customer\Models\Reseller;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

final class ResellerResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Reseller::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.resellers';

    private const ACCOUNT_FEATURE_OPERATOR = null;

    private const ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    public static function getNavigationGroup(): string
    {
        return (string) __('customers.navigation');
    }

    /**
     * Explicit feature wiring (constants referenced for PHPStan + AccountFeatures).
     */
    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            operatorDefault: self::ACCOUNT_FEATURE_OPERATOR_DEFAULT,
            fallback: false,
        );
    }

    public static function getModelLabel(): string
    {
        return __('resellers.reseller');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resellers.resellers');
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('name')
                            ->label(__('resellers.fields.name'))
                            ->required()
                            ->maxLength(255),

                        Select::make('user_id')
                            ->label(__('resellers.fields.user'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                    Textarea::make('notes')
                        ->label(__('resellers.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('resellers.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('resellers.fields.user'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResellers::route('/'),
            'create' => Pages\CreateReseller::route('/create'),
            'view' => Pages\ViewReseller::route('/{record}'),
            'edit' => Pages\EditReseller::route('/{record}/edit'),
        ];
    }
}
