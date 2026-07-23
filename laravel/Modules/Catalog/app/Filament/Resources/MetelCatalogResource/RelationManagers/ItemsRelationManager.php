<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\MetelCatalogResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Modules\Catalog\Actions\LinkMetelItemToProductAction;
use Modules\Catalog\Models\MetelCatalogItem;

final class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('metel.items');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metel_code')
                    ->label(__('metel.fields.metel_code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('metel.fields.description'))
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                Tables\Columns\TextColumn::make('unit_of_measure')
                    ->label(__('metel.fields.unit_of_measure'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gross_price')
                    ->label(__('metel.fields.gross_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_price')
                    ->label(__('metel.fields.net_price'))
                    ->money('EUR')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('product_id')
                    ->label(__('metel.fields.in_products'))
                    ->boolean(),
            ])
            ->actions([
                Action::make('linkToProduct')
                    ->label(__('metel.actions.link_to_product'))
                    ->icon('heroicon-o-cube')
                    ->visible(fn (MetelCatalogItem $record): bool => $record->product_id === null)
                    ->action(function (MetelCatalogItem $record): void {
                        app(LinkMetelItemToProductAction::class)->execute($record);

                        Notification::make()->success()->title(__('metel.actions.linked'))->send();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('linkSelectedToProducts')
                    ->label(__('metel.actions.link_to_product'))
                    ->icon('heroicon-o-cube')
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            /** @var MetelCatalogItem $record */
                            app(LinkMetelItemToProductAction::class)->execute($record);
                        }

                        Notification::make()->success()->title(__('metel.actions.linked'))->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
