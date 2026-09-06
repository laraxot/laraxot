<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\LinkInvoiceToWorkOrderAction;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\InvoiceWorkOrderLink;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class WorkOrdersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'workOrderLinks';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('billing::billing.sections.work_orders_linked');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'work_order_id' => Select::make('work_order_id')
                ->label(__('billing::billing.fields.work_order'))
                ->options(function (): array {
                    $rows = DB::table('work_orders')
                        ->orderByDesc('id')
                        ->limit(200)
                        ->get(['id', 'code', 'title']);

                    $result = [];
                    foreach ($rows as $row) {
                        $id = is_int($row->id) || is_numeric($row->id) ? (int) $row->id : 0;
                        $label = is_string($row->code) && $row->code !== '' 
                            ? $row->code 
                            : (is_string($row->title) ? $row->title : 'Commessa senza codice');
                        $result[$id] = $label;
                    }

                    return $result;
                })
                ->searchable()
                ->preload()
                ->required(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('work_order_id')
            ->columns([
                Tables\Columns\TextColumn::make('work_order_id')
                    ->label(__('billing::billing.fields.work_order'))
                    ->formatStateUsing(function (mixed $state): string {
                        if (! is_numeric($state)) {
                            return 'Commessa non disponibile';
                        }
                        $record = DB::table('work_orders')->where('id', (int) $state)->first(['code', 'title']);

                        if ($record === null) {
                            return 'Commessa non disponibile';
                        }
                        $label = is_string($record->code) && $record->code !== '' 
                            ? $record->code 
                            : (is_string($record->title) ? $record->title : 'Commessa non disponibile');

                        return $label;
                    })
                    ->sortable()
                    ->searchable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn (): bool => ! request()->is('customer/*'))
                    ->using(function (array $data): InvoiceWorkOrderLink {
                        /** @var Invoice $invoice */
                        $invoice = $this->getOwnerRecord();
                        $workOrderId = is_numeric($data['work_order_id'] ?? null)
                            ? (int) $data['work_order_id']
                            : 0;

                        return app(LinkInvoiceToWorkOrderAction::class)->execute(
                            invoice: $invoice,
                            workOrderId: $workOrderId,
                        );
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->visible(fn (): bool => ! request()->is('customer/*')),
            ]);
    }
}
