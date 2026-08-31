<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Support;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Espone il callback protetto di XotBaseResourceForm per i test.
 */
class OptionLabelProbeForm extends XotBaseResourceForm
{
    public static function labelFor(Model $record, string $titleAttribute = 'name'): string
    {
        $callback = static::optionLabelFromRecord($titleAttribute);

        return $callback($record);
    }
}
