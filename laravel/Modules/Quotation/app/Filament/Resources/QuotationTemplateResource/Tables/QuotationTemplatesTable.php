<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class QuotationTemplatesTable extends XotBaseResourceTable
{
    protected static string $resource = QuotationTemplateResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'title' => TextColumn::make('title')
                ->searchable()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => QuotationTemplateResource::canView($record)),
            'edit' => EditAction::make()
                ->iconButton()
                ->visible(static fn (Model $record): bool => QuotationTemplateResource::canEdit($record)),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
            'restore' => RestoreBulkAction::make(),
            'forceDelete' => ForceDeleteBulkAction::make(),
        ];
    }
}
