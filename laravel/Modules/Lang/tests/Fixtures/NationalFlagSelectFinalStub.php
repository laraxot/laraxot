<?php

declare(strict_types=1);

namespace Modules\Lang\Tests\Fixtures;

use Modules\Lang\Filament\Forms\Components\NationalFlagSelect;

final class NationalFlagSelectFinalStub extends NationalFlagSelect
{
    /** @var array<int, mixed> */
    public array $forcedCountries = [];

    /** @var array<int, mixed> */
    public array $extraFilteredRows = [];

    protected function resolveCountries(): array
    {
        return $this->forcedCountries;
    }

    protected function finalizeFilteredCountries(array $filteredCountries): array
    {
        return array_merge(array_values($filteredCountries), $this->extraFilteredRows);
    }
}
