<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Tables;

use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Webmozart\Assert\Assert;
use Illuminate\Support\Facades\DB;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class InvoicesTable extends XotBaseResourceTable
{
    protected static string $resource = InvoiceResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'number' => TextColumn::make('number')
                ->searchable()
                ->sortable(),
            'customer_id' => TextColumn::make('customer_id')
                ->formatStateUsing(function ($state): string {
                    if (is_numeric($state)) {
                        $name = DB::table('customers')->where('id', $state)->value('legal_name');

                        return is_string($name) ? $name : 'Cliente non disponibile';
                    }

                    return 'Cliente non disponibile';
                })
                ->toggleable()
                ->searchable(),
            'issued_on' => TextColumn::make('issued_on')
                ->date()
                ->toggleable()
                ->sortable(),
            'total' => TextColumn::make('total')
                ->money('EUR')
                ->sortable(),
            'electronic_invoice_sent_at' => TextColumn::make('electronic_invoice_sent_at')
                ->dateTime()
                ->toggleable()
                ->sortable(),
            'electronic_invoice_external_id' => TextColumn::make('electronic_invoice_external_id')
                ->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<int|string, \Filament\Tables\Actions\Action|\Filament\Tables\Actions\ActionGroup>
     */
    public function getTableActions(): array
    {
        /** @var array<int|string, Action|ActionGroup> $actions */
        $actions = [];

        $resource = app(static::$resource);
        Assert::isInstanceOf($resource, \Illuminate\Database\Eloquent\Model::class);
        if (method_exists($resource, 'canView')) {
            $actions['view'] = ViewAction::make()
                ->iconButton()
                ->visible(static fn (mixed $record): bool => (bool) $resource->canView($record));
        }

        if (method_exists($resource, 'canEdit')) {
            $actions['edit'] = EditAction::make()
                ->iconButton()
                ->visible(
                    fn (mixed $record): bool => ! request()->is('customer/*')
                        && (bool) $resource->canEdit($record)
                );
        }

        if (method_exists($resource, 'canDelete')) {
            $actions['delete'] = DeleteAction::make()
                ->iconButton()
                ->visible(static fn (mixed $record): bool => (bool) $resource->canDelete($record));
        }

        return $actions;
    }
}
