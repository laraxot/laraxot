<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class InterventionsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'interventions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.interventions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('interventions.fields.scheduled_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('operators')
                    ->label(__('interventions.fields.operators'))
                    ->state(function (Intervention $record): string {
                        $names = $record->assignedUsers()
                            ->orderBy('name')
                            ->get()
                            ->map(fn (User $user): string => $user->name)
                            ->all();

                        if ($names === [] && $record->performedBy !== null) {
                            $names = [$record->performedBy->name];
                        }

                        return implode(', ', $names);
                    }),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('interventions.fields.notes'))
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\TextColumn::make('materials_summary')
                    ->label(__('interventions.fields.material_lines'))
                    ->state(function (Intervention $record): string {
                        $manualMaterials = $record->materialLines()
                            ->orderBy('description')
                            ->get()
                            ->map(static function ($line): string {
                                $quantityRaw = $line->getAttribute('quantity');
                                $quantity = is_numeric($quantityRaw) ? (float) $quantityRaw : 0.0;

                                $descriptionRaw = $line->getAttribute('description');
                                $description = is_string($descriptionRaw) ? $descriptionRaw : '';

                                return number_format($quantity, 2, ',', '.').'x '.$description;
                            });

                        if ($manualMaterials->isNotEmpty()) {
                            return $manualMaterials->implode(', ');
                        }

                        $inventoryMaterials = $record->inventoryMovements()
                            ->with('product')
                            ->where('movement_type', 'scarico')
                            ->orderBy('occurred_at')
                            ->get()
                            ->map(static function ($movement): string {
                                $quantityRaw = $movement->getAttribute('quantity');
                                $quantity = is_int($quantityRaw) ? $quantityRaw : 0;
                                $product = $movement->getRelationValue('product');
                                $productName = 'Materiale';
                                if ($product instanceof Model) {
                                    $name = $product->getAttribute('name');
                                    if (is_string($name)) {
                                        $productName = $name;
                                    }
                                }

                                return $quantity.'x '.$productName;
                            });

                        return $inventoryMaterials->implode(', ');
                    })
                    ->wrap()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('interventions.fields.total_amount'))
                    ->money('EUR')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('notes_text_search')
                    ->schema([
                        TextInput::make('term'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $term = is_string($data['term'] ?? null) ? trim($data['term']) : '';

                        if ($term === '') {
                            return $query;
                        }

                        return $query->where('notes', 'like', '%'.$term.'%');
                    }),
                Filter::make('material_search')
                    ->schema([
                        TextInput::make('term'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $term = is_string($data['term'] ?? null) ? trim($data['term']) : '';

                        if ($term === '') {
                            return $query;
                        }

                        return $query->where(function (Builder $innerQuery) use ($term): void {
                            $innerQuery
                                ->whereHas('materialLines', function (Builder $materialsQuery) use ($term): void {
                                    $materialsQuery->where('description', 'like', '%'.$term.'%');
                                })
                                ->orWhereHas('inventoryMovements.product', function (Builder $materialsQuery) use ($term): void {
                                    $materialsQuery->where('name', 'like', '%'.$term.'%');
                                })
                                ->orWhereHas('inventoryMovements', function (Builder $materialsQuery) use ($term): void {
                                    $materialsQuery->where('note', 'like', '%'.$term.'%');
                                });
                        });
                    }),
            ])
            ->defaultSort('scheduled_at', 'desc')
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Intervention $record): string => InterventionResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
