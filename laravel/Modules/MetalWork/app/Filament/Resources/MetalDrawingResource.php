<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

/**
 * The archive of parsed drawings. Read-only by design: everything here was
 * measured out of the CAD file, and editing it by hand would detach the number
 * from the drawing it came from. Corrections belong on the estimate, where they
 * are recorded as the operator's own inputs.
 */
final class MetalDrawingResource extends GatedXotBaseResource
{
    protected static ?string $model = MetalDrawing::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.drawing');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.drawings');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * I disegni entrano dall'import STEP, non si compilano a mano: la
     * risorsa serve a consultarli e a lanciare la preventivazione.
     *
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('part_code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('bbox')
                    ->placeholder('—')
                    ->state(fn (MetalDrawing $record): ?string => $record->bbox_length_mm === null
                        ? null
                        : sprintf(
                            '%s × %s × %s',
                            number_format((float) $record->bbox_length_mm, 1, ',', '.'),
                            number_format((float) $record->bbox_width_mm, 1, ',', '.'),
                            number_format((float) $record->bbox_thickness_mm, 1, ',', '.'),
                        )),
                Tables\Columns\TextColumn::make('solid_count')->numeric(),
                Tables\Columns\TextColumn::make('cylinder_count')->numeric()->toggleable(),
                Tables\Columns\IconColumn::make('is_assembly')->boolean(),
                Tables\Columns\TextColumn::make('estimates_count')
                    ->counts('estimates'),
                Tables\Columns\TextColumn::make('source_file')->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cad_system')->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_assembly'),
            ])
            ->actions([
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalDrawingResource\Pages\ListMetalDrawings::route('/'),
        ];
    }
}
