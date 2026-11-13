<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Themes\Sixteen\Enums\Concerns\HasLangLabel;

/**
 * Stati appuntamento conformi AGID (sixteen_appointments.status).
 *
 * Sostituisce le costanti Appointment::STATUS_*. Label, colore e icona vivono in
 * lang/<locale>/appointment_status_enum.php (namespace `sixteen::`).
 */
enum AppointmentStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    use HasLangLabel;

    /** In attesa di conferma */
    case PENDING = 'pending';

    /** Confermato */
    case CONFIRMED = 'confirmed';

    /** Completato */
    case COMPLETED = 'completed';

    /** Cancellato */
    case CANCELLED = 'cancelled';

    /** Non presentato */
    case NO_SHOW = 'no_show';

    /**
     * Appuntamento ancora "vivo": in attesa o confermato, quindi cancellabile/modificabile
     * (entro le finestre orarie del modello).
     */
    public function isOpen(): bool
    {
        return match ($this) {
            self::PENDING, self::CONFIRMED => true,
            self::COMPLETED, self::CANCELLED, self::NO_SHOW => false,
        };
    }

    public function getColor(): string
    {
        return $this->translate('color');
    }

    public function getIcon(): string
    {
        return $this->translate('icon');
    }

    private function translate(string $field): string
    {
        return trans('sixteen::appointment_status_enum.values.'.$this->value.'.'.$field);
    }
}
