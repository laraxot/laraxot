<?php

declare(strict_types=1);

namespace Modules\Costing\Datas;

/**
 * Un ricarico risolto e da dove arriva.
 *
 * La provenienza viaggia insieme al numero perche' all'operatore mostriamo il
 * calcolo di ogni riga e deve poter distinguere "il centro di lavoro dice 40%"
 * da "non l'ha detto nessuno, quindi 0%".
 */
final readonly class MarkupDecision
{
    public function __construct(
        public float $percent,
        public string $sourceCode,
    ) {}

    /** Prezzo di vendita di una riga che costa `$cost`, arrotondato al centesimo. */
    public function priceOf(float $cost): float
    {
        return round($cost * (1 + $this->percent / 100), 2);
    }
}
