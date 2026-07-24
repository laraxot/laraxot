<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Compliance\Models\MaintenanceControl;
use Spatie\QueueableAction\QueueableAction;

/**
 * Renders the energy-efficiency control report (RCEE-style) for a maintenance
 * control as a PDF, ready to hand to the customer / attach to the plant booklet.
 */
final class GenerateRceePdfAction
{
    use QueueableAction;

    public function execute(MaintenanceControl $control): string
    {
        $control->loadMissing(['heatingSystem.customer', 'heatingSystem.site', 'boiler']);

        return Pdf::loadView('compliance::pdf.rcee', [
            'control' => $control,
        ])->output();
    }
}
