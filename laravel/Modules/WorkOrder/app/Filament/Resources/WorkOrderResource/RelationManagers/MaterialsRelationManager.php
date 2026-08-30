<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * La distinta materiali della commessa: ciò che è arrivato dal preventivo,
 * più quello che la produzione aggiunge strada facendo.
 */
final class MaterialsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'materials';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('workorder::work_orders.materials.title');
    }

    /** @return array<int|string, Component|Field> */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('description')
                ->label(__('workorder::work_orders.materials.fields.description'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Select::make('product_id')
                ->label(__('workorder::work_orders.materials.fields.product'))
                ->options(fn (): array => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),
            TextInput::make('unit_code')
                ->label(__('workorder::work_orders.materials.fields.unit'))
                ->maxLength(20),
            TextInput::make('quantity')
                ->label(__('workorder::work_orders.materials.fields.quantity'))
                ->numeric()
                ->default(1)
                ->required(),
            TextInput::make('unit_cost')
                ->label(__('workorder::work_orders.materials.fields.unit_cost'))
                ->numeric()
                ->default(0)
                ->required(),
            Textarea::make('notes')
                ->label(__('workorder::work_orders.materials.fields.notes'))
                ->rows(2)
                ->columnSpanFull(),
        ];
    }

    /** @return array<string, Tables\Columns\Column> */
    public function getTableColumns(): array
    {
        return [
            'description' => Tables\Columns\TextColumn::make('description')
                ->label(__('workorder::work_orders.materials.fields.description'))
                ->searchable()
                ->wrap(),
            'source_code' => Tables\Columns\TextColumn::make('source_code')
                ->label(__('workorder::work_orders.materials.fields.source'))
                ->badge()
                ->formatStateUsing(fn (?string $state): string => (string) __('workorder::work_orders.materials.sources.'.($state ?? 'manual'))),
            'quantity' => Tables\Columns\TextColumn::make('quantity')
                ->label(__('workorder::work_orders.materials.fields.quantity'))
                ->numeric(decimalPlaces: 2),
            'unit_code' => Tables\Columns\TextColumn::make('unit_code')
                ->label(__('workorder::work_orders.materials.fields.unit')),
            'unit_cost' => Tables\Columns\TextColumn::make('unit_cost')
                ->label(__('workorder::work_orders.materials.fields.unit_cost'))
                ->money('EUR'),
            'line_total' => Tables\Columns\TextColumn::make('line_total')
                ->label(__('workorder::work_orders.materials.fields.line_total'))
                ->money('EUR')
                ->summarize(Tables\Columns\Summarizers\Sum::make()->money('EUR')),
        ];
    }

    /** @return array<string, Action> */
    public function getTableHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()
                ->authorize(fn (): bool => $this->canEditMaterials())
                ->mutateDataUsing(static fn (array $data): array => self::withLineTotal($data)),
        ];
    }

    /** @return array<string, Action> */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make()
                ->authorize(fn (): bool => $this->canEditMaterials())
                ->mutateDataUsing(static fn (array $data): array => self::withLineTotal($data)),
            'delete' => DeleteAction::make()
                ->authorize(fn (): bool => $this->canEditMaterials()),
        ];
    }

    /**
     * La distinta appartiene alla commessa: chi può cambiare la commessa può
     * cambiare di cosa è fatta. Nessun permesso separato da distribuire.
     */
    private function canEditMaterials(): bool
    {
        return Gate::allows('update', $this->getOwnerRecord());
    }

    /**
     * Il totale riga è derivato, mai digitato: un totale a mano in disaccordo
     * con quantità × costo rende il job costing inaffidabile.
     *
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private static function withLineTotal(array $data): array
    {
        $quantity = is_numeric($data['quantity'] ?? null) ? (float) $data['quantity'] : 0.0;
        $unitCost = is_numeric($data['unit_cost'] ?? null) ? (float) $data['unit_cost'] : 0.0;

        $data['line_total'] = round($quantity * $unitCost, 2);
        $data['source_code'] = $data['source_code'] ?? 'manual';

        return $data;
    }
}
