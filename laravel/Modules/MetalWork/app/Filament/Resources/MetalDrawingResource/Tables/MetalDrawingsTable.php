<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalDrawingResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Modules\MetalWork\Filament\Resources\MetalDrawingResource;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class MetalDrawingsTable extends XotBaseResourceTable
{
    protected static string $resource = MetalDrawingResource::class;

    /**
     * MetalDrawingResource non definisce canView()/canEdit()/canDelete(): l'hook
     * di default di HasXotTable li richiede per popolare le azioni riga, quindi
     * senza questo override il pulsante Elimina della table() originale sparisce.
     *
     * @return array<string, DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'part_code' => TextColumn::make('part_code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable()->wrap(),
            'bbox' => TextColumn::make('bbox')
                ->placeholder('—')
                ->state(fn (MetalDrawing $record): ?string => $record->bbox_length_mm === null
                    ? null
                    : sprintf(
                        '%s × %s × %s',
                        number_format((float) $record->bbox_length_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_width_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_thickness_mm, 1, ',', '.'),
                    )),
            'solid_count' => TextColumn::make('solid_count')->numeric(),
            'cylinder_count' => TextColumn::make('cylinder_count')->numeric()->toggleable(),
            'is_assembly' => IconColumn::make('is_assembly')->boolean(),
            'estimates_count' => TextColumn::make('estimates_count')
                ->counts('estimates'),
            'source_file' => TextColumn::make('source_file')->searchable()->toggleable(isToggledHiddenByDefault: true),
            'cad_system' => TextColumn::make('cad_system')->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
        ];
    }

    /**
     * @return array<string, TernaryFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_assembly' => TernaryFilter::make('is_assembly'),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }
}
