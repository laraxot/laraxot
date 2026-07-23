<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Domain\DTO;

/**
 * Confronto fra tempo preventivato e tempo realmente speso su una commessa.
 */
final readonly class WorkOrderEffort
{
    public function __construct(
        public ?int $plannedMinutes,
        public int $actualMinutes,
    ) {}

    /**
     * Minuti oltre il previsto. Negativo se si e' rimasti sotto.
     * Null quando non e' stato previsto nulla: non c'e' scostamento da calcolare.
     */
    public function varianceMinutes(): ?int
    {
        if ($this->plannedMinutes === null) {
            return null;
        }

        return $this->actualMinutes - $this->plannedMinutes;
    }

    /**
     * Scostamento percentuale sul previsto.
     * Null se non c'e' un previsto, o se e' zero: dividere non avrebbe senso.
     */
    public function variancePercent(): ?float
    {
        if ($this->plannedMinutes === null || $this->plannedMinutes === 0) {
            return null;
        }

        return round((($this->actualMinutes - $this->plannedMinutes) / $this->plannedMinutes) * 100, 2);
    }

    public function isOverBudget(): bool
    {
        $variance = $this->varianceMinutes();

        return $variance !== null && $variance > 0;
    }
}
