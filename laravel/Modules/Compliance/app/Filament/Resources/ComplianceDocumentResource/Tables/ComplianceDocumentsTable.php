<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Compliance\Actions\AnalyzeComplianceDocumentWithOpenAiAction;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource;
use Modules\Compliance\Filament\Resources\ComplianceDocumentResource\Schemas\ComplianceDocumentForm;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class ComplianceDocumentsTable extends XotBaseResourceTable
{
    protected static string $resource = ComplianceDocumentResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'type' => TextColumn::make('type')->badge()->searchable()->sortable(),
            'title' => TextColumn::make('title')->searchable()->sortable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
            'workOrder.title' => TextColumn::make('workOrder.title')->limit(40)->toggleable(),
            'expires_on' => TextColumn::make('expires_on')->date()->sortable(),
            'is_required' => IconColumn::make('is_required')->boolean(),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'type' => SelectFilter::make('type')->options(ComplianceDocumentForm::documentTypes()),
            'status' => SelectFilter::make('status')->options(ComplianceDocumentForm::statuses()),
        ];
    }

    /**
     * @return array<string, Action|ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'analyzeWithAi' => Action::make('analyzeWithAi')

                ->icon('heroicon-o-sparkles')
                ->color('info')
                ->action(function (ComplianceDocument $record): void {
                    try {
                        app(AnalyzeComplianceDocumentWithOpenAiAction::class)->execute($record);
                        Notification::make()
                            ->title(__('compliance.messages.ai_analysis_completed'))
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title(__('compliance.messages.ai_analysis_failed'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
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
