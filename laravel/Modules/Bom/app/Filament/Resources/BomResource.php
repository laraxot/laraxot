<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Bom\Filament\Resources\BomResource\Pages\CreateBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\EditBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\ListBoms;
use Modules\Bom\Filament\Resources\BomResource\Pages\ViewBom;
use Modules\Bom\Filament\Resources\BomResource\RelationManagers\BomItemsRelationManager;
use Modules\Bom\Models\Bom;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class BomResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Bom::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.bom';

    /** @var null */
    protected const ACCOUNT_FEATURE_OPERATOR = null;

    /** @var bool */
    protected const ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    public static function getNavigationGroup(): string
    {
        return __('boms.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('boms.bom');
    }

    public static function getPluralModelLabel(): string
    {
        return __('boms.boms');
    }

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Select::make('product_id')
                        ->label(__('boms.fields.product_id'))
                        ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('name')
                        ->label(__('boms.fields.name'))
                        ->maxLength(255),
                    Toggle::make('is_active')
                        ->label(__('boms.fields.is_active'))
                        ->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label(__('boms.fields.product_id'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label(__('boms.fields.name'))->toggleable(),
                Tables\Columns\IconColumn::make('is_active')->label(__('boms.fields.is_active'))->boolean()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BomItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoms::route('/'),
            'create' => CreateBom::route('/create'),
            'view' => ViewBom::route('/{record}'),
            'edit' => EditBom::route('/{record}/edit'),
        ];
    }
}
