<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\LinkInvoiceToWorkOrderAction;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\InvoiceWorkOrderLink;

final class WorkOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'workOrderLinks';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('billing::billing.sections.work_orders_linked');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('work_order_id')
                ->label(__('billing::billing.fields.work_order'))
                ->options(fn (): array => DB::table('work_orders')
                    ->orderByDesc('id')
                    ->limit(200)
                    ->get(['id', 'code', 'title'])
                    ->mapWithKeys(fn ($row): array => [
                        (int) $row->id => (string) ($row->code ?: $row->title ?: 'Commessa senza codice'),
                    ])
                    ->all()
                )
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('work_order_id')
            ->columns([
                Tables\Columns\TextColumn::make('work_order_id')
                    ->label(__('billing::billing.fields.work_order'))
                    ->formatStateUsing(function ($state): string {
                        $record = DB::table('work_orders')->where('id', $state)->first(['code', 'title']);

                        return (string) ($record?->code ?: $record?->title ?: 'Commessa non disponibile');
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

                        return app(LinkInvoiceToWorkOrderAction::class)->execute(
                            invoice: $invoice,
                            workOrderId: (int) $data['work_order_id'],
                        );
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->visible(fn (): bool => ! request()->is('customer/*')),
            ]);
    }
}
