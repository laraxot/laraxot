<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Timber\Filament\Resources\TimberPaymentInstallmentResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberPaymentInstallments extends XotBaseListRecords
{
    protected static string $resource = TimberPaymentInstallmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'send_reminders' => TimberPaymentInstallmentResource::sendRemindersAction(),
            'create' => CreateAction::make()->label('Nuova scadenza'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'due_date' => Tables\Columns\TextColumn::make('due_date')
                ->label('Scadenza')
                ->date('d/m/Y')
                ->sortable(),
            'invoice.number' => Tables\Columns\TextColumn::make('invoice.number')
                ->label('Documento')
                ->toggleable(),
            'customer.business_name' => Tables\Columns\TextColumn::make('customer.business_name')
                ->label('Cliente')
                ->searchable()
                ->toggleable(),
            'direction' => Tables\Columns\TextColumn::make('direction')
                ->label('Tipo')
                ->formatStateUsing(fn (?string $state): string => $state === 'out' ? 'Uscita' : 'Entrata')
                ->badge()
                ->color(fn (?string $state): string => $state === 'out' ? 'warning' : 'success'),
            'amount' => Tables\Columns\TextColumn::make('amount')
                ->label('Importo')
                ->money('EUR')
                ->sortable(),
            'payment_method_code' => Tables\Columns\TextColumn::make('payment_method_code')
                ->label('Metodo')
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.payment_methods')[$state] ?? (string) $state)
                ->toggleable(),
            'status_code' => Tables\Columns\TextColumn::make('status_code')
                ->label('Stato')
                ->badge()
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.payment_statuses')[$state] ?? (string) $state)
                ->color(fn (?string $state): string => match ($state) {
                    'saldato' => 'success',
                    'sollecitato' => 'warning',
                    default => 'gray',
                }),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'status_code' => Tables\Filters\SelectFilter::make('status_code')
                ->label('Stato')
                ->options(TimberConfigMap::get('timber.payment_statuses')),
            'payment_method_code' => Tables\Filters\SelectFilter::make('payment_method_code')
                ->label('Metodo')
                ->options(TimberConfigMap::get('timber.payment_methods')),
            'direction' => Tables\Filters\SelectFilter::make('direction')
                ->label('Tipo')
                ->options(['in' => 'Entrate', 'out' => 'Uscite']),
            'overdue' => Tables\Filters\Filter::make('overdue')
                ->label('Solo scadute da saldare')
                ->query(fn (Builder $query): Builder => $query->where('status_code', '!=', 'saldato')->whereDate('due_date', '<=', now())),
            'riba_to_issue' => Tables\Filters\Filter::make('riba_to_issue')
                ->label('RIBA da emettere')
                ->query(fn (Builder $query): Builder => $query->where('payment_method_code', 'riba')->where('status_code', 'da_saldare')),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'markpaid' => TimberPaymentInstallmentResource::markPaidAction(),
            'edit' => EditAction::make(),
        ];
    }
}
