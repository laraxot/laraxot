<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Tables\Columns;

use Modules\Xot\Filament\Tables\Columns\XotBaseTextColumn;
use Webmozart\Assert\Assert;

class ScheduleOptions extends XotBaseTextColumn
{
    protected bool $withValue = true;

    public function withValue(bool $withValue = true): static
    {
        $this->withValue = $withValue;

        return $this;
    }

    /**
     * @return array<int|string, string>
     */
    public function getTags(): array
    {
        if ($this->record === null) {
            return [];
        }

        if ($this->withValue && \is_object($this->record) && method_exists($this->record, 'getOptions')) {
            $options = $this->record->getOptions();
            Assert::isArray($options);

            /** @var array<int|string, string> $options */
            return $options;
        }

        return [];
    }
}
