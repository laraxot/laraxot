<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class CustomerSystemsTable extends XotBaseResourceTable
{
    protected static string $resource = CustomerSystemResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'system_type' => TextColumn::make('system_type')
                ->badge()
                ->formatStateUsing(fn (?string $state): string => CustomerSystemResource::systemTypeOptions()[$state] ?? (string) $state)
                ->sortable(),
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'customer_id' => TextColumn::make('customer_id')
                ->formatStateUsing(function ($state): string {
                    $name = DB::table('customers')->where('id', $state)->value('legal_name');

                    return is_string($name) ? $name : '—';
                })
                ->searchable(),
            'description' => TextColumn::make('description')->limit(40)->toggleable(),
            'media_count' => TextColumn::make('media_count')->counts('media'),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
