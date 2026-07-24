<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Data\QuotedMaterial;
use Modules\Intervention\Data\UsedMaterial;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Quotation\Models\Quotation;
use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;

/**
 * Ricalcola l'extra preventivo di una commessa intera.
 *
 * Il confronto DEVE avvenire sulla commessa e non sul singolo intervento: il
 * preventivo copre la commessa, quindi 4 valvole preventivate coprono 4 valvole
 * in totale, non 4 per ogni uscita. Valutando un intervento alla volta, il
 * materiale distribuito su piu' interventi risulterebbe sempre coperto e non
 * verrebbe mai fatturato.
 *
 * Gli interventi si consumano in ordine cronologico: il preventivo copre prima
 * le uscite piu' vecchie.
 */
final class SyncWorkOrderExtraMaterialsAction
{
    use QueueableAction;

    public function __construct(
        private DetectExtraQuotationMaterialsAction $detectExtraMaterials,
    ) {}

    /**
     * @return float Valore complessivo fuori preventivo della commessa.
     */
    public function execute(WorkOrder $workOrder): float
    {
        $interventions = $workOrder->interventions()
            ->with('materialLines')
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();

        $usedMaterials = [];

        foreach ($interventions as $intervention) {
            foreach ($intervention->materialLines as $line) {
                $usedMaterials[] = new UsedMaterial(
                    materialLineId: $line->id,
                    description: (string) $line->description,
                    quantity: (float) $line->quantity,
                    unitPrice: (float) $line->unit_price,
                );
            }
        }

        $extraLines = $this->detectExtraMaterials->execute(
            usedMaterials: $usedMaterials,
            quotedMaterials: $this->quotedMaterials($workOrder),
        );

        $total = 0.0;

        foreach ($extraLines as $extraLine) {
            $total += $extraLine->extraAmount;

            InterventionMaterialLine::query()
                ->whereKey($extraLine->materialLineId)
                ->update([
                    'quotation_item_id' => $extraLine->quotationItemId,
                    'extra_quantity' => $extraLine->extraQuantity,
                    'extra_amount' => $extraLine->extraAmount,
                ]);
        }

        return round($total, 2);
    }

    /**
     * @return array<int, QuotedMaterial>
     */
    private function quotedMaterials(WorkOrder $workOrder): array
    {
        // Un preventivo rifiutato non copre nulla: il materiale che ne deriva e' extra.
        $quotations = Quotation::query()
            ->where('work_order_id', $workOrder->id)
            ->where('status_code', '!=', 'rejected')
            ->with('items')
            ->get();

        $quoted = [];

        foreach ($quotations as $quotation) {
            foreach ($quotation->items as $item) {
                $quoted[] = new QuotedMaterial(
                    quotationItemId: $item->id,
                    description: (string) $item->description,
                    quantity: (float) $item->quantity,
                );
            }
        }

        return $quoted;
    }

    /**
     * Comodita' per chi ha in mano un intervento: l'extra si ricalcola comunque
     * sull'intera commessa a cui appartiene.
     */
    public function executeForIntervention(Intervention $intervention): float
    {
        $workOrder = $intervention->workOrder;

        return $workOrder instanceof WorkOrder ? $this->execute($workOrder) : 0.0;
    }
}
