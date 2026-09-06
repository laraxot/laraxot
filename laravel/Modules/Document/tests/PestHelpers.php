<?php

declare(strict_types=1);

use Modules\Intervention\Database\Factories\InterventionFactory;
use Modules\Intervention\Models\Intervention;

/**
 * Helper Pest/PHPStan — modulo Document.
 *
 * @see Modules/Cms/tests/PestHelpers.php
 */

/**
 * Costruisce un'Intervention di test senza persistere sulla connessione
 * 'intervention' (mysql, non migrata in questo ambiente di test — la sua
 * tabella `interventions` non esiste). document_media non ha una FK verso
 * mediable (`morphs()` crea solo colonne + indice, mai un vincolo), quindi
 * un id fittizio basta a esercitare la relazione polimorfica MorphMany
 * senza toccare l'altra connessione. work_order_id resta null per evitare
 * di risolvere anche la factory annidata WorkOrder::factory() -> Customer::factory().
 *
 * @param  array<string, mixed>  $attributes
 */
function documentCreateIntervention(array $attributes = []): Intervention
{
    $intervention = InterventionFactory::new()->makeOne([
        'work_order_id' => null,
        ...$attributes,
    ]);

    $intervention->setAttribute($intervention->getKeyName(), random_int(100000, 999999));
    $intervention->exists = true;
    $intervention->syncOriginal();

    return $intervention;
}
