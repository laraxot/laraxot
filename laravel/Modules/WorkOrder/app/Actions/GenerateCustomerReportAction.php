<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Settings\SettingsRepository;
use Modules\WorkOrder\Support\DocumentExportBridge;
use Spatie\QueueableAction\QueueableAction;

final class GenerateCustomerReportAction
{
    use QueueableAction;

    /**
     * Genera la rendicontazione degli interventi per un cliente in un range di date.
     *
     * @param  'pdf'|'docx'  $format
     * @return array{content: string, mime: string, filename: string}
     */
    public function execute(
        Customer $customer,
        CarbonImmutable $from,
        CarbonImmutable $to,
        string $format = 'pdf',
    ): array {
        $materialsEnabled = app(SettingsRepository::class)->getBool('inventory.allow_link_interventions', true);

        /** @var Collection<int, Intervention> $interventions */
        $interventions = Intervention::query()
            ->with([
                'workOrder.customer',
                'workOrder.site',
                'customer',
                'performedBy',
                'assignedUsers',
                'materialLines',
                'inventoryMovements' => static function (Relation $query) use ($materialsEnabled): void {
                    if ($materialsEnabled) {
                        $query->where('movement_type', 'scarico')
                            ->with(['product', 'fromWarehouse'])
                            ->orderBy('occurred_at');
                    } else {
                        $query->whereRaw('1=0');
                    }
                },
            ])
            ->where(function ($q) use ($customer): void {
                $q->whereHas('workOrder', fn ($wq) => $wq->where('customer_id', $customer->id))
                    ->orWhere('customer_id', $customer->id);
            })
            ->whereBetween('scheduled_at', [$from->startOfDay(), $to->endOfDay()])
            ->orderBy('scheduled_at')
            ->get();

        /** @var int|float|string $rawMinutes */
        $rawMinutes = $interventions->sum('work_minutes');
        $totalMinutes = (int) $rawMinutes;

        /** @var int|float|string $rawAmount */
        $rawAmount = $interventions->sum('amount');
        $laborTotalAmount = is_numeric($rawAmount) ? (float) $rawAmount : 0.0;

        /** @var int|float|string $rawCalloutAmount */
        $rawCalloutAmount = $interventions->sum('callout_fee');
        $calloutTotalAmount = is_numeric($rawCalloutAmount) ? (float) $rawCalloutAmount : 0.0;

        /** @var int|float|string $rawMaterialsAmount */
        $rawMaterialsAmount = $interventions->sum(
            static function (Intervention $intervention): float {
                $inventoryMaterials = $intervention->inventoryMovements->sum('line_total');
                $manualMaterials = $intervention->materialLines->sum('line_total');
                $storedMaterials = $intervention->getAttribute('materials_total');

                return max(
                    is_numeric($inventoryMaterials) ? (float) $inventoryMaterials : 0.0,
                    is_numeric($manualMaterials) ? (float) $manualMaterials : 0.0,
                    is_numeric($storedMaterials) ? (float) $storedMaterials : 0.0,
                );
            }
        );
        $materialsTotalAmount = $materialsEnabled && is_numeric($rawMaterialsAmount) ? (float) $rawMaterialsAmount : 0.0;

        /** @var int|float|string $rawTotalAmount */
        $rawTotalAmount = $interventions->sum('total_amount');
        $storedGrandTotal = is_numeric($rawTotalAmount) ? (float) $rawTotalAmount : 0.0;
        $totalAmount = $storedGrandTotal > 0 ? $storedGrandTotal : ($laborTotalAmount + $calloutTotalAmount + $materialsTotalAmount);

        if ($format === 'docx') {
            return $this->generateDocx($customer, $interventions, $from, $to, $totalMinutes, $laborTotalAmount, $calloutTotalAmount, $materialsTotalAmount, $totalAmount);
        }

        return $this->generatePdf($customer, $interventions, $from, $to, $totalMinutes, $laborTotalAmount, $calloutTotalAmount, $materialsTotalAmount, $totalAmount);
    }

    /**
     * @param  Collection<int, Intervention>  $interventions
     * @return array{content: string, mime: string, filename: string}
     */
    private function generatePdf(
        Customer $customer,
        Collection $interventions,
        CarbonImmutable $from,
        CarbonImmutable $to,
        int $totalMinutes,
        float $laborTotalAmount,
        float $calloutTotalAmount,
        float $materialsTotalAmount,
        float $totalAmount,
    ): array {
        $content = DocumentExportBridge::pdfFromView('work_orders::reports.rendicontazione-cliente', [
            'customer' => $customer,
            'interventions' => $interventions,
            'from' => $from,
            'to' => $to,
            'totalMinutes' => $totalMinutes,
            'laborTotalAmount' => $laborTotalAmount,
            'calloutTotalAmount' => $calloutTotalAmount,
            'materialsTotalAmount' => $materialsTotalAmount,
            'totalAmount' => $totalAmount,
        ]);

        $customerSlug = Str::slug($customer->legal_name ?? 'cliente');

        return [
            'content' => $content,
            'mime' => 'application/pdf',
            'filename' => sprintf(
                'rendicontazione_%s_%s_%s.pdf',
                $customerSlug,
                $from->format('Ymd'),
                $to->format('Ymd'),
            ),
        ];
    }

    /**
     * @param  Collection<int, Intervention>  $interventions
     * @return array{content: string, mime: string, filename: string}
     */
    private function generateDocx(
        Customer $customer,
        Collection $interventions,
        CarbonImmutable $from,
        CarbonImmutable $to,
        int $totalMinutes,
        float $laborTotalAmount,
        float $calloutTotalAmount,
        float $materialsTotalAmount,
        float $totalAmount,
    ): array {
        throw new \RuntimeException(
            'DOCX export requires phpoffice/phpword — composer require phpoffice/phpword'
        );
    }
}
