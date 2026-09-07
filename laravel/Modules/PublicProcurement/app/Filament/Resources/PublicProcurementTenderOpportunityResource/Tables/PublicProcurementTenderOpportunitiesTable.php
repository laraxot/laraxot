<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Actions\EvaluateTenderEligibilityAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementTenderOpportunityResource;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class PublicProcurementTenderOpportunitiesTable extends XotBaseResourceTable
{
    protected static string $resource = PublicProcurementTenderOpportunityResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'status' => TextColumn::make('status')
                ->label(__('public_procurement.fields.status'))
                ->badge()
                ->formatStateUsing(fn (string $state): string => PublicProcurementTenderOpportunityResource::statuses()[$state] ?? $state)
                ->sortable(),
            'title' => TextColumn::make('title')->label(__('public_procurement.fields.title'))->searchable()->limit(50),
            'contracting_authority' => TextColumn::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->searchable()->limit(34),
            'cig' => TextColumn::make('cig')->label(__('public_procurement.fields.cig'))->searchable()->toggleable(),
            'category' => TextColumn::make('category')->label(__('public_procurement.fields.category'))->searchable()->toggleable(),
            'territory' => TextColumn::make('territory')->label(__('public_procurement.fields.territory'))->searchable()->toggleable(),
            'estimated_amount' => TextColumn::make('estimated_amount')->label(__('public_procurement.fields.estimated_amount'))->money('EUR')->sortable(),
            'submission_deadline_at' => TextColumn::make('submission_deadline_at')->label(__('public_procurement.fields.submission_deadline_at'))->dateTime()->sortable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')
                ->label(__('public_procurement.fields.status'))
                ->options(PublicProcurementTenderOpportunityResource::statuses()),
            'category' => SelectFilter::make('category')
                ->label(__('public_procurement.fields.category'))
                ->options(fn (): array => PublicProcurementTenderOpportunity::query()
                    ->whereNotNull('category')
                    ->distinct()
                    ->pluck('category', 'category')
                    ->all()),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'evaluateEligibility' => Action::make('evaluateEligibility')
                ->label(__('public_procurement.actions.evaluate_eligibility'))
                ->icon('heroicon-o-shield-check')
                ->color('warning')
                ->action(function (PublicProcurementTenderOpportunity $record): void {
                    $userId = Auth::id();
                    app(EvaluateTenderEligibilityAction::class)->execute($record, is_int($userId) ? $userId : null);
                    Notification::make()->title(__('public_procurement.messages.eligibility_completed'))->success()->send();
                }),
            'createContract' => Action::make('createContract')
                ->label(__('public_procurement.actions.create_contract'))
                ->icon('heroicon-o-briefcase')
                ->color('success')
                ->action(function (PublicProcurementTenderOpportunity $record): void {
                    app(CreateContractFromOpportunityAction::class)->execute($record);
                    Notification::make()->title(__('public_procurement.messages.contract_created'))->success()->send();
                }),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'submission_deadline_at';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }
}
