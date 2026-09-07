<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource\RelationManagers\ItemsRelationManager;
use Modules\Quotation\Models\QuotationTemplate;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class QuotationTemplateResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = QuotationTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    /** @var string */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.quotations';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    /** @var string */
    private const ACCOUNT_FEATURE_OPERATOR = 'operator.quotations';

    public static function getNavigationGroup(): string
    {
        return __('quotations.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('quotations.quotation_template');
    }

    public static function getPluralModelLabel(): string
    {
        return __('quotations.quotation_templates');
    }

    public static function getFormSchema(): array
    {
        return [
            'title' => Section::make()->columnSpanFull()->components([
                TextInput::make('title')

                    ->required()
                    ->maxLength(255),
                Textarea::make('notes')

                    ->columnSpanFull(),
            ])->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')

                    ->searchable()
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

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotationTemplates::route('/'),
            'create' => Pages\CreateQuotationTemplate::route('/create'),
            'view' => Pages\ViewQuotationTemplate::route('/{record}'),
            'edit' => Pages\EditQuotationTemplate::route('/{record}/edit'),
        ];
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }
}
