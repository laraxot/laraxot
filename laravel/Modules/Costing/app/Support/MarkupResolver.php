<?php

declare(strict_types=1);

namespace Modules\Costing\Support;

use Modules\Costing\Datas\MarkupDecision;

/**
 * Sceglie il ricarico di una riga costata da un elenco ordinato di candidati,
 * dal piu' specifico al piu' generico, e ricorda quale ha vinto.
 *
 * I livelli li decide il chiamante: una voce di catalogo insegne risolve
 * voce -> centro di lavoro -> listino -> config, un preventivo carpenteria
 * risolve preventivo -> centro di lavoro -> config. Quello che non deve cambiare
 * fra i due e' la regola — vince il primo non nullo — e l'aritmetica che porta
 * dal costo al prezzo: per questo stanno qui e non una volta per modulo.
 */
final class MarkupResolver
{
    /**
     * @param  array<string, float|null>  $candidates  codice sorgente => percentuale, dal piu' specifico
     */
    public static function firstOf(array $candidates): MarkupDecision
    {
        foreach ($candidates as $sourceCode => $percent) {
            if ($percent !== null) {
                return new MarkupDecision($percent, $sourceCode);
            }
        }

        return new MarkupDecision(0.0, 'none');
    }

    /**
     * Legge un default di config che puo' mancare o essere un valore assurdo, così
     * il chiamante lo puo' passare direttamente a {@see firstOf()} come ultimo candidato.
     */
    public static function configDefault(string $key): float
    {
        $value = config($key);

        return is_numeric($value) ? (float) $value : 0.0;
    }
}
