<?php

declare(strict_types=1);

namespace Modules\Compliance\DTO;

/**
 * One row of the heating-compliance deadlines overview. `status` is one of
 * overdue | expiring | ok | unknown.
 */
final readonly class HeatingComplianceRow
{
    public function __construct(
        public int $systemId,
        public ?int $boilerId,
        public string $customerName,
        public string $systemLabel,
        public string $boilerLabel,
        public ?string $maintenanceDueOn,
        public ?string $efficiencyDueOn,
        public string $status,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'system_id' => $this->systemId,
            'boiler_id' => $this->boilerId,
            'customer' => $this->customerName,
            'system' => $this->systemLabel,
            'boiler' => $this->boilerLabel,
            'maintenance_due_on' => $this->maintenanceDueOn,
            'efficiency_due_on' => $this->efficiencyDueOn,
            'status' => $this->status,
        ];
    }
}
