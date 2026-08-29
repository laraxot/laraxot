<?php

declare(strict_types=1);

namespace Modules\Timber\Data;

/**
 * Una riga del registro tracciabilita, nel formato del foglio Excel del cliente:
 * INGRESSO MATERIA PRIMA | INIZIO LAVORAZIONE | CONFEZIONAMENTO.
 */
final readonly class TimberTraceabilityRow
{
    public function __construct(
        public string $supplierName,
        public string $supplierCode,
        public string $ingressYear,
        public string $ddtNumber,
        public float $quantity,
        public string $unit,
        public ?string $workStartDate,
        public ?string $articleCode,
        public ?string $packagingDate,
        public int $bancali,
        public string $traceableCode,
    ) {}
}
