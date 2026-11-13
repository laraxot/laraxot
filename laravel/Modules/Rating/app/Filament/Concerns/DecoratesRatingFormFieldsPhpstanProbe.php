<?php

declare(strict_types=1);

namespace Modules\Rating\Filament\Concerns;

/** Probe host so PHPStan analyses DecoratesRatingFormFields in app context. */
final class DecoratesRatingFormFieldsPhpstanProbe
{
    use DecoratesRatingFormFields;
}
