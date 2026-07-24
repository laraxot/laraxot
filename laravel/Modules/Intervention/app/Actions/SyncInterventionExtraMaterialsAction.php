<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Models\Intervention;
use Spatie\QueueableAction\QueueableAction;

/**
 * Ricalcola l'extra preventivo partendo da un intervento.
 *
 * Delega a SyncWorkOrderExtraMaterialsAction perche' il confronto corretto e'
 * sulla commessa: il preventivo copre il totale della commessa, non ogni singola
 * uscita. Valutando un intervento alla volta, il materiale distribuito su piu'
 * interventi risulterebbe sempre coperto e non verrebbe mai fatturato.
 */
final class SyncInterventionExtraMaterialsAction
{
    use QueueableAction;

    public function __construct(
        private SyncWorkOrderExtraMaterialsAction $syncWorkOrderExtraMaterials,
    ) {}

    /**
     * @return float Valore complessivo fuori preventivo della commessa dell'intervento.
     */
    public function execute(Intervention $intervention): float
    {
        return $this->syncWorkOrderExtraMaterials->executeForIntervention($intervention);
    }
}
