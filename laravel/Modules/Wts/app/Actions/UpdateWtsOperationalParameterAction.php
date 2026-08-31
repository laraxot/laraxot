<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Wts\Queries\GetWtsOperationalParametersQuery;
use InvalidArgumentException;

final readonly class UpdateWtsOperationalParameterAction
{
    public function __construct(
        private GetWtsOperationalParametersQuery $parametersQuery,
    ) {}

    public function execute(string $code, string $value, int|string|null $updatedByUserId): WtsOperationalParameter
    {
        $parameters = $this->parametersQuery->execute();
        $normalizedCode = trim($code);
        $normalizedValue = trim($value);

        if (! array_key_exists($normalizedCode, $parameters)) {
            throw new InvalidArgumentException('Parametro operativo WTS non configurato.');
        }

        if ($normalizedValue === '') {
            throw new InvalidArgumentException('Il valore del parametro operativo e obbligatorio.');
        }

        $parameter = $parameters[$normalizedCode];
        $parameter->forceFill([
            'value' => $normalizedValue,
            'updated_by_user_id' => $updatedByUserId,
        ])->save();

        return $parameter->refresh();
    }
}
