<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;
use Modules\Xot\Models\Extra;

class ExtrasTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Extra>
     */
    protected static string $model = Extra::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'model_type' => TextColumn::make('model_type')
                ->badge()
                ->formatStateUsing(static fn (string $state): string => class_basename($state))
                ->tooltip(static fn (string $state): string => $state)
                ->searchable()
                ->sortable(),
            'model_id' => TextColumn::make('model_id')->searchable()->sortable(),
            'extra_attributes' => TextColumn::make('extra_attributes')->label('Extra')->limit(50)->wrap()->toggleable(isToggledHiddenByDefault: true),
            'id' => TextColumn::make('id')->sortable()->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->placeholder('—')->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->placeholder('—')->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
