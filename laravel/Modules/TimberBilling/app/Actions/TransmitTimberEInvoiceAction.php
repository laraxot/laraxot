<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Modules\TimberBilling\Models\TimberEInvoice;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Segna la fattura elettronica come trasmessa al Sistema di Interscambio.
 *
 * La trasmissione reale verso SdI (via provider accreditato/PEC) e' il punto di
 * innesto esterno: qui si registra l'esito e l'identificativo SdI.
 */
final class TransmitTimberEInvoiceAction
{
    use QueueableAction;

    public function execute(TimberEInvoice $eInvoice, ?string $sdiIdentifier = null): TimberEInvoice
    {
        if ($eInvoice->sdi_status !== 'da_inviare') {
            throw new RuntimeException('La fattura elettronica non e in stato "da inviare".');
        }

        $eInvoice->forceFill([
            'sdi_status' => 'inviata',
            'sdi_identifier' => $sdiIdentifier ?? ('SDI'.now()->format('YmdHis').$eInvoice->id),
            'transmitted_at' => now(),
            'error_message' => null,
        ])->save();

        return $eInvoice->refresh();
    }
}
