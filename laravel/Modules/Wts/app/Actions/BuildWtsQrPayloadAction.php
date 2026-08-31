<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsUnit;

/**
 * Contenuto del QR stampato sull'etichetta del collo: un URL che, scansionato,
 * porta l'operatore allo SNODO LAVORAZIONI (UI-07) del lotto del collo,
 * es. https://commesse.cavalliniservice.com/wts/processing?lot=16
 */
final class BuildWtsQrPayloadAction
{
    public function execute(WtsUnit $unit): string
    {
        $unit->loadMissing('inboundLot');

        $lot = $unit->inboundLot;
        $lotId = $lot instanceof WtsInboundLot ? $lot->id : 0;

        $urlRaw = config('app.url');
        $base = rtrim(is_scalar($urlRaw) ? (string) $urlRaw : '', '/');

        return $base.'/wts/processing?lot='.$lotId;
    }
}
