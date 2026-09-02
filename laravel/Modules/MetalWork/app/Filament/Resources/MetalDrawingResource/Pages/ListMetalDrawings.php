<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalDrawingResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\MetalWork\Filament\Resources\MetalDrawingResource;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListMetalDrawings extends XotBaseListRecords
{
    protected static string $resource = MetalDrawingResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'part_code' => Tables\Columns\TextColumn::make('part_code')->label(__('metalwork::metalwork.fields.part_code'))->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->label(__('metalwork::metalwork.fields.name'))->searchable()->wrap(),
            'bbox' => Tables\Columns\TextColumn::make('bbox')
                ->label(__('metalwork::metalwork.fields.bbox'))
                ->placeholder('—')
                ->state(fn (MetalDrawing $record): ?string => $record->bbox_length_mm === null
                    ? null
                    : sprintf(
                        '%s × %s × %s',
                        number_format((float) $record->bbox_length_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_width_mm, 1, ',', '.'),
                        number_format((float) $record->bbox_thickness_mm, 1, ',', '.'),
                    )),
            'solid_count' => Tables\Columns\TextColumn::make('solid_count')->label(__('metalwork::metalwork.fields.solid_count'))->numeric(),
            'cylinder_count' => Tables\Columns\TextColumn::make('cylinder_count')->label(__('metalwork::metalwork.fields.cylinder_count'))->numeric()->toggleable(),
            'is_assembly' => Tables\Columns\IconColumn::make('is_assembly')->label(__('metalwork::metalwork.fields.is_assembly'))->boolean(),
            'estimates_count' => Tables\Columns\TextColumn::make('estimates_count')
                ->label(__('metalwork::metalwork.part_estimates'))
                ->counts('estimates'),
            'source_file' => Tables\Columns\TextColumn::make('source_file')->label(__('metalwork::metalwork.fields.source_file'))->searchable()->toggleable(isToggledHiddenByDefault: true),
            'cad_system' => Tables\Columns\TextColumn::make('cad_system')->label(__('metalwork::metalwork.fields.cad_system'))->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
            'created_at' => Tables\Columns\TextColumn::make('created_at')->label(__('metalwork::metalwork.fields.created_at'))->dateTime('d/m/Y H:i')->sortable(),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_assembly' => Tables\Filters\TernaryFilter::make('is_assembly')->label(__('metalwork::metalwork.fields.is_assembly')),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'delete' => DeleteAction::make(),
        ];
    }
}
