<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource\RelationManagers\ItemsRelationManager;
use Modules\Quotation\Models\QuotationTemplate;

final class QuotationTemplateResource extends Resource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = QuotationTemplate::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.quotations';

    private const ACCOUNT_FEATURE_OPERATOR = 'operator.quotations';

    public static function getNavigationGroup(): ?string
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                TextInput::make('title')
                    ->label(__('quotations.fields.title'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label(__('quotations.fields.notes'))
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('quotations.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
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
}

