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

    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'grid' => Grid::make(2)->components([
                    'business_name' => TextInput::make('business_name')->required()->maxLength(255),
                    'status' => Select::make('status')->default('active')->options([
                        'active' => 'Attivo',
                        'suspended' => 'Sospeso',
                        'blocked' => 'Bloccato',
                    ]),
                    'vat_number' => TextInput::make('vat_number')->maxLength(50),
                    'tax_code' => TextInput::make('tax_code')->maxLength(50),
                    'contact_name' => TextInput::make('contact_name')->maxLength(255),
                    'email' => TextInput::make('email')->email()->maxLength(255),
                    'phone' => TextInput::make('phone')->tel()->maxLength(255),
                ]),
                'notes' => Textarea::make('notes')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('vat_number')->toggleable(),
                Tables\Columns\TextColumn::make('contact_name')->toggleable(),
                Tables\Columns\TextColumn::make('email')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
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
