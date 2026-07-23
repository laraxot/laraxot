<?php

declare(strict_types=1);

namespace Themes\Sixteen\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Themes\Sixteen\Models\Office;

class AppointmentAvailability implements ValidationRule
{
    public function __construct(
        private readonly ?int $officeId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        unset($attribute);

        if ($this->officeId === null) {
            $fail('Ufficio non selezionato.');

            return;
        }

        $date = SafeStringCastAction::cast($value);
        if ($date === '') {
            $fail('Data appuntamento non valida.');

            return;
        }

        $office = Office::query()->find($this->officeId);
        if ($office === null) {
            $fail('Ufficio non trovato.');

            return;
        }

        if (! in_array($date, $office->getAvailableDates(), true)) {
            $fail('La data selezionata non è disponibile.');
        }
    }
}
