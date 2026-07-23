<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Columns;

use Modules\Xot\Filament\Tables\Columns\XotBaseTextColumn;

class ScheduleOptions extends XotBaseTextColumn
{
    protected bool $withValue = true;

    public function withValue(bool $withValue = true): static
    {
        $this->withValue = $withValue;

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getTags(): array
    {
        /*
         * if($this->record==null){
         * return [];
         * }
         * if($this->withValue)
         * return $this->record->getOptions();
         * else{
         * return parent::getTags();
         * }
         */
        return [];
    }
}
