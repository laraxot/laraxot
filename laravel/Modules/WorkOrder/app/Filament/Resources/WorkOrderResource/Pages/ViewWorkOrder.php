<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Carbon\CarbonImmutable;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Infolists\Components\Entry;
use Filament\Schemas\Components\Component;
use Modules\WorkOrder\Actions\GenerateWorkOrderPdfAction;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewWorkOrder extends XotBaseViewRecord
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * @return array<string, Component|Entry>
     */
    protected function getInfolistSchema(): array
    {
        return WorkOrderResource::getInfolistSchema();
    }

    protected function getHeaderActions(): array
    {
        return [
            'downloadPdf' => Actions\Action::make('downloadPdf')
                ->label(__('work_orders.actions.download_pdf'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (): mixed {
                    $workOrder = $this->resolveWorkOrderRecord();

                    return response()->streamDownload(
                        function () use ($workOrder): void {
                            echo app(GenerateWorkOrderPdfAction::class)->execute($workOrder);
                        },
                        sprintf('work-order-%s.pdf', $workOrder->code ?? (string) $workOrder->id),
                    );
                }),
            'exportReport' => Actions\Action::make('exportReport')
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
                    $workOrder = $this->resolveWorkOrderRecord();
                    $format = match ((string) ($data['format'] ?? 'pdf')) {
                        'docx' => 'docx',
                        default => 'pdf',
                    };
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
            'restoreCascade' => Actions\Action::make('restoreCascade')
                ->label(__('work_orders.actions.restore_cascade'))
                ->icon('heroicon-o-arrow-path')
                ->visible(function (): bool {
                    $record = $this->getRecord();

                    return $record instanceof WorkOrder && $record->trashed();
                })
                ->requiresConfirmation()
                ->action(function (): void {
                    app(RestoreWorkOrderCascadeAction::class)->execute($this->resolveWorkOrderRecord());
                }),
            'edit' => Actions\EditAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }

    private function resolveWorkOrderRecord(): WorkOrder
    {
        $record = $this->getRecord();
        if (! $record instanceof WorkOrder) {
            throw new \RuntimeException('Expected WorkOrder record.');
        }

        return $record;
    }
}
