<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Auth;
use Modules\Quotation\Actions\ApplyQuotationTemplateToQuotationAction;
use Modules\Quotation\Actions\GenerateConfirmedQuotationPdfAction;
use Modules\Quotation\Actions\GenerateQuotationPdfAction;
use Modules\Quotation\Filament\Pages\RecordQuotationVoicePage;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationTemplate;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\AssignUserToWorkOrderAction;
use Modules\WorkOrder\Actions\CreateWorkOrderFromQuotationAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewQuotation extends XotBaseViewRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'voiceSimilar' => Action::make('voiceSimilar')
                ->label(__('quotations.voice.create_similar'))
                ->icon('heroicon-o-microphone')
                ->visible(fn (): bool => RecordQuotationVoicePage::canAccess())
                ->url(fn (): string => RecordQuotationVoicePage::getUrl(['source' => $this->getRecord()->getKey()])),

            'downloadPdf' => Action::make('downloadPdf')
                ->label(__('quotations.actions.download_pdf'))
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();

                    return response()->streamDownload(
                        function () use ($quotation): void {
                            echo app(GenerateQuotationPdfAction::class)->execute($quotation);
                        },
                        sprintf('quotation-%s.pdf', $quotation->code ?? (string) $quotation->id),
                    );
                }),

            'downloadConfirmedPdf' => Action::make('downloadConfirmedPdf')
                ->label(__('quotations.actions.download_confirmed_pdf'))
                ->icon('heroicon-o-check-badge')
                ->visible(function (): bool {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();

                    return (bool) $quotation->confirmed_at;
                })
                ->action(function () {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();

                    return response()->streamDownload(
                        function () use ($quotation): void {
                            echo app(GenerateConfirmedQuotationPdfAction::class)->execute($quotation);
                        },
                        sprintf('quotation-confirmed-%s.pdf', $quotation->code ?? (string) $quotation->id),
                    );
                }),

            'applyTemplate' => Action::make('applyTemplate')
                ->label(__('quotations.actions.apply_template'))
                ->icon('heroicon-o-rectangle-stack')
                ->schema([
                    Select::make('template_id')
                        ->label(__('quotations.quotation_template'))
                        ->helperText('Applica un modello per inserire automaticamente righe e note precompilate nel preventivo.')
                        ->options(fn (): array => QuotationTemplate::query()->orderBy('title')->pluck('title', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();
                    $templateId = $data['template_id'];
                    /** @var QuotationTemplate $template */
                    $template = QuotationTemplate::query()->findOrFail(is_numeric($templateId) ? (int) $templateId : 0);
                    app(ApplyQuotationTemplateToQuotationAction::class)->execute($quotation, $template);
                }),

            'createWorkOrder' => Action::make('createWorkOrder')
                ->label(__('quotations.actions.create_work_order'))
                ->icon('heroicon-o-briefcase')
                ->visible(function (): bool {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();

                    return $quotation->work_order_id === null;
                })
                ->schema([
                    TextInput::make('title')
                        ->label(__('work_orders.fields.title'))
                        ->required()
                        ->default(function (): string {
                            /** @var Quotation $quotation */
                            $quotation = $this->getRecord();

                            return $quotation->title ?? ('Da preventivo '.($quotation->code ?? ('#'.$quotation->id)));
                        }),
                    DateTimePicker::make('scheduled_at')
                        ->label(__('work_orders.fields.scheduled_at')),
                    DateTimePicker::make('delivery_due_at')
                        ->label(__('workorder::work_orders.fields.delivery_due_at'))
                        ->default(function (): ?string {
                            /** @var Quotation $quotation */
                            $quotation = $this->getRecord();

                            return $quotation->delivery_at !== null
                                ? (string) $quotation->delivery_at
                                : null;
                        }),
                    Select::make('status_code')
                        ->options(fn (): array => WorkOrderStatus::query()->orderBy('sort_order')->pluck('name', 'code')->all()),
                    Select::make('assigned_user_id')
                        ->label(__('work_orders.fields.assigned_to'))
                        ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all())
                        ->searchable()
                        ->preload(),
                ])
                ->action(function (array $data): void {
                    /** @var Quotation $quotation */
                    $quotation = $this->getRecord();

                    $title = $data['title'];

                    /** @var WorkOrder $workOrder */
                    $workOrder = app(CreateWorkOrderFromQuotationAction::class)->execute($quotation, [
                        'title' => is_string($title) ? $title : '',
                        'scheduled_at' => $data['scheduled_at'] ?? null,
                        'delivery_due_at' => $data['delivery_due_at'] ?? null,
                        'status_code' => is_string($data['status_code'] ?? null) ? $data['status_code'] : null,
                    ]);

                    $rawAssignee = $data['assigned_user_id'] ?? null;
                    $assignedUserId = (is_int($rawAssignee) || (is_string($rawAssignee) && $rawAssignee !== '')) ? $rawAssignee : null;
                    if ($assignedUserId !== null) {
                        $assignedByUserId = Auth::id();

                        app(AssignUserToWorkOrderAction::class)->execute(
                            workOrderId: (int) $workOrder->id,
                            userId: $assignedUserId,
                            role: 'operator',
                            assignedByUserId: $assignedByUserId,
                            assignedAt: null,
                        );
                    }

                    $this->redirect(WorkOrderResource::getUrl('view', ['record' => $workOrder->id]));
                }),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'status_code' => TextEntry::make('status_code'),
            'customer_id' => TextEntry::make('customer_id'),
            'total' => TextEntry::make('total'),
            'cost_total' => TextEntry::make('cost_total'),
            'margin' => TextEntry::make('margin'),
            'project' => TextEntry::make('project'),
            'delivery_at' => TextEntry::make('delivery_at'),
            'issued_at' => TextEntry::make('issued_at'),
            'updated_at' => TextEntry::make('updated_at'),
        ];
    }
}
