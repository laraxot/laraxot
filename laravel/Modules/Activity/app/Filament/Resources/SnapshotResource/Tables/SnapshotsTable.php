<?php

declare(strict_types=1);

namespace Modules\Activity\Filament\Resources\SnapshotResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Activity\Models\Snapshot;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

use function Safe\json_encode;

class SnapshotsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Snapshot>
     */
    protected static string $model = Snapshot::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'aggregate_uuid' => TextColumn::make('aggregate_uuid')->searchable()->sortable()->copyable(),
            'aggregate_version' => TextColumn::make('aggregate_version')->numeric()->sortable(),
            'state' => TextColumn::make('state')
                ->state(static fn (Snapshot $record): string => json_encode($record->state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->limit(100)
                ->wrap()
                ->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'id' => TextColumn::make('id')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
