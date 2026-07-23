<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Platform\Settings\SettingsRepository;

final class GenerateCustomerReportAction
{
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
                'inventoryMovements' => fn ($query) => $materialsEnabled
                    ? $query->where('movement_type', 'scarico')
                        ->with(['product', 'fromWarehouse'])
                        ->orderBy('occurred_at')
                    : $query->whereRaw('1=0'),
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
                $storedMaterials = $intervention->materials_total;

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
        $content = Pdf::loadView('work_orders::reports.rendicontazione-cliente', [
            'customer' => $customer,
            'interventions' => $interventions,
            'from' => $from,
            'to' => $to,
            'totalMinutes' => $totalMinutes,
            'laborTotalAmount' => $laborTotalAmount,
            'calloutTotalAmount' => $calloutTotalAmount,
            'materialsTotalAmount' => $materialsTotalAmount,
            'totalAmount' => $totalAmount,
        ])
            ->setPaper('a4', 'portrait')
            ->output();

        $customerSlug = \Illuminate\Support\Str::slug($customer->legal_name ?? 'cliente');

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
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 900,
            'marginRight' => 900,
        ]);

        $titleStyle = ['bold' => true, 'size' => 14, 'color' => 'C0392B'];
        $headingStyle = ['bold' => true, 'size' => 11];
        $labelStyle = ['bold' => true];

        $section->addText('RENDICONTAZIONE INTERVENTI', $titleStyle);
        $section->addTextBreak(1);

        $section->addText('Cliente', $labelStyle);
        $section->addText($customer->legal_name ?? '—');
        $section->addText('Periodo', $labelStyle);
        $section->addText($from->format('d/m/Y') . ' — ' . $to->format('d/m/Y'));
        $section->addTextBreak(1);

        $section->addText('Interventi', $headingStyle);
        $section->addTextBreak(1);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => 'DDDDDD',
            'cellMargin' => 80,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'width' => 100 * 50,
        ];
        $table = $section->addTable($tableStyle);

        $headerCellStyle = ['bgColor' => 'F5F5F5'];
        $headerTextStyle = ['bold' => true, 'size' => 9];

        $table->addRow();
        foreach (['Data', 'Operatori', 'Commessa', 'Note / Esito', 'Minuti', 'Manodopera €', 'Chiamata €', 'Materiali €', 'Totale €'] as $col) {
            $cell = $table->addCell(null, $headerCellStyle);
            $cell->addText($col, $headerTextStyle);
        }

        foreach ($interventions as $intervention) {
            $table->addRow();

            $scheduledRaw = $intervention->scheduled_at;
            $formattedDate = '—';
            if ($scheduledRaw !== null) {
                $formattedDate = CarbonImmutable::parse((string) $scheduledRaw)->format('d/m/Y H:i');
            }
            $table->addCell()->addText($formattedDate);

            $operatorNames = $intervention->assignedUsers->pluck('name')->filter()->implode(', ');
            $table->addCell()->addText($operatorNames !== '' ? $operatorNames : ($intervention->performedBy->name ?? '—'));

            $woTitle = $intervention->workOrder?->title
                ?? $intervention->workOrder?->code
                ?? $intervention->customer?->legal_name
                ?? '—';
            $table->addCell()->addText($woTitle);

            $outcome = match ($intervention->outcome_code) {
                'completed' => 'Concluso',
                'suspended' => 'Sospeso',
                'quotation' => 'Preventivo',
                default => (string) ($intervention->outcome_code ?? ''),
            };
            /** @var Collection<int, InventoryMovement> $materials */
            $materials = $intervention->inventoryMovements;
            $manualMaterials = $intervention->materialLines;
            $materialsSummary = $materials
                ->map(function (InventoryMovement $movement): string {
                    $productName = $movement->product !== null
                        ? (string) $movement->product->name
                        : 'Materiale';
                    $quantity = (int) ($movement->quantity ?? 0);

                    return $quantity . 'x ' . $productName;
                })
                ->toBase()
                ->merge(
                    $manualMaterials->map(static function ($line): string {
                        $quantity = (float) ($line->quantity ?? 0);

                        return number_format($quantity, 2, ',', '.') . 'x ' . (string) ($line->description ?? 'Materiale');
                    })
                )
                ->implode(', ');

            $note = trim(($intervention->notes ?? '') . ($outcome !== '' ? " [{$outcome}]" : ''));
            if ($materialsSummary !== '') {
                $note = trim($note . ' | Materiali: ' . $materialsSummary);
            }
            $table->addCell()->addText($note);
            $table->addCell()->addText((string) ($intervention->work_minutes ?? '—'));
            $table->addCell()->addText(
                $intervention->amount !== null
                    ? number_format((float) $intervention->amount, 2, ',', '.') . ' €'
                    : '—'
            );
            $table->addCell()->addText(
                $intervention->callout_fee !== null && (float) $intervention->callout_fee > 0
                    ? number_format((float) $intervention->callout_fee, 2, ',', '.') . ' €'
                    : '—'
            );
            $rawMaterialsLineTotal = max(
                is_numeric($materials->sum('line_total')) ? (float) $materials->sum('line_total') : 0.0,
                is_numeric($manualMaterials->sum('line_total')) ? (float) $manualMaterials->sum('line_total') : 0.0,
                is_numeric($intervention->materials_total) ? (float) $intervention->materials_total : 0.0,
            );
            $materialsLineTotal = (float) $rawMaterialsLineTotal;
            $table->addCell()->addText(
                $materialsLineTotal > 0
                    ? number_format($materialsLineTotal, 2, ',', '.') . ' €'
                    : '—'
            );
            $table->addCell()->addText(
                $intervention->total_amount !== null && (float) $intervention->total_amount > 0
                    ? number_format((float) $intervention->total_amount, 2, ',', '.') . ' €'
                    : '—'
            );
        }

        $section->addTextBreak(1);
        $section->addText(
            'Totale: ' . $totalMinutes . ' min' .
            ($laborTotalAmount > 0 ? ' | Manodopera ' . number_format($laborTotalAmount, 2, ',', '.') . ' €' : '') .
            ($calloutTotalAmount > 0 ? ' | Diritto chiamata ' . number_format($calloutTotalAmount, 2, ',', '.') . ' €' : '') .
            ($materialsTotalAmount > 0 ? ' | Materiali ' . number_format($materialsTotalAmount, 2, ',', '.') . ' €' : '') .
            ($totalAmount > 0 ? ' | Complessivo ' . number_format($totalAmount, 2, ',', '.') . ' €' : ''),
            $labelStyle
        );

        $customerSlug = \Illuminate\Support\Str::slug($customer->legal_name ?? 'cliente');
        $filename = sprintf(
            'rendicontazione_%s_%s_%s.docx',
            $customerSlug,
            $from->format('Ymd'),
            $to->format('Ymd'),
        );

        $tmpPath = sys_get_temp_dir() . '/' . $filename;
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tmpPath);
        $content = (string) file_get_contents($tmpPath);
        @unlink($tmpPath);

        return [
            'content' => $content,
            'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'filename' => $filename,
        ];
    }
}