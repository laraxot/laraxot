<?php

declare(strict_types=1);

namespace Modules\Wts\Queries;

use Modules\Wts\Models\WtsOperationalParameter;
use Illuminate\Database\Eloquent\Collection;

final class GetWtsOperationalParametersQuery
{
    /**
     * @return array<string, WtsOperationalParameter>
     */
    public function execute(): array
    {
        $configuredParameters = config('wts.operational_parameters', []);

        if (is_array($configuredParameters)) {
            foreach ($configuredParameters as $code => $parameter) {
                if (! is_string($code) || ! is_array($parameter)) {
                    continue;
                }

                $label = $parameter['label'] ?? $code;
                $value = $parameter['value'] ?? '';
                $unit = $parameter['unit'] ?? null;

                WtsOperationalParameter::query()->firstOrCreate(
                    ['code' => $code],
                    [
                        'label' => is_scalar($label) ? (string) $label : $code,
                        'value' => is_scalar($value) ? (string) $value : '',
                        'unit' => is_scalar($unit) ? (string) $unit : null,
                    ],
                );
            }
        }

        /** @var Collection<int, WtsOperationalParameter> $parameters */
        $parameters = WtsOperationalParameter::query()
            ->orderBy('code')
            ->get();

        $indexed = [];
        foreach ($parameters as $parameter) {
            $indexed[$parameter->code] = $parameter;
        }

        return $indexed;
    }
}
