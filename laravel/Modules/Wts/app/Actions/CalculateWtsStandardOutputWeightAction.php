<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Queries\GetWtsOperationalParametersQuery;

final readonly class CalculateWtsStandardOutputWeightAction
{
    public function __construct(
        private GetWtsOperationalParametersQuery $operationalParametersQuery,
    ) {}

    public function execute(string $parameterCode, int $quantity): float
    {
        $parameters = $this->operationalParametersQuery->execute();
        $parameter = $parameters[$parameterCode] ?? null;
        $unitWeight = $parameter !== null && is_numeric($parameter->value) ? (float) $parameter->value : 0.0;

        return round($unitWeight * $quantity, 2);
    }
}
