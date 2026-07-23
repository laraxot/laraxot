<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Resources\Pages\ViewRecord;
use Modules\WorkOrder\Actions\GenerateWorkOrderPdfAction;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;

final class ViewWorkOrder extends ViewRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadPdf')
                ->label(__('work_orders.actions.download_pdf'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $workOrder = $this->getRecord();

                    return response()->streamDownload(
                        function () use ($workOrder): void {
                            echo app(GenerateWorkOrderPdfAction::class)->execute($workOrder);
                        },
                        sprintf('work-order-%s.pdf', $workOrder->code ?: $workOrder->id),
                    );
                }),
            Actions\Action::make('exportReport')
                ->label(__('work_orders.actions.export_report'))
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->form([
                    Radio::make('format')
                        ->label(__('work_orders.fields.report_format'))
                        ->options([
                            'pdf' => 'PDF',
                            'docx' => 'Word (DOCX)',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                    DatePicker::make('from')
                        ->label(__('work_orders.fields.report_from'))
                        ->displayFormat('d/m/Y')
                        ->default(now()->startOfMonth()->toDateString())
                        ->required(),
                    DatePicker::make('to')
                        ->label(__('work_orders.fields.report_to'))
                        ->displayFormat('d/m/Y')
                        ->default(now()->endOfMonth()->toDateString())
                        ->required(),
                ])
                ->action(function (array $data): mixed {
                    $workOrder = $this->getRecord();
                    $format = (string) ($data['format'] ?? 'pdf');
                    $from = CarbonImmutable::parse((string) $data['from'])->startOfDay();
                    $to = CarbonImmutable::parse((string) $data['to'])->endOfDay();

                    $result = app(GenerateWorkOrderReportAction::class)->execute(
                        workOrder: $workOrder,
                        from: $from,
                        to: $to,
                        format: $format,
                    );

                    return response()->streamDownload(
                        function () use ($result): void {
                            echo $result['content'];
                        },
                        $result['filename'],
                        ['Content-Type' => $result['mime']],
                    );
                }),
            Actions\Action::make('restoreCascade')
                ->label(__('work_orders.actions.restore_cascade'))
                ->icon('heroicon-o-arrow-path')
                ->visible(fn (): bool => $this->getRecord()->trashed())
                ->requiresConfirmation()
                ->action(function (): void {
                    /** @var \Modules\WorkOrder\Models\WorkOrder $workOrder */
                    $workOrder = $this->getRecord();
                    app(RestoreWorkOrderCascadeAction::class)->execute($workOrder);
                }),
            Actions\EditAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }
}

