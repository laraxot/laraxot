<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

/**
 * EnumIntegerTrait — Specialization of EnumTrait for int-backed enums.
 *
 * Inherits translation + Filament support from EnumTrait + TransTrait.
 * Adds integer-specific navigation and comparison methods.
 *
 * Do NOT override translation methods (getLabel, getColor, etc.) —
 * EnumTrait already provides them via TransTrait using transClass().
 *
 * Usage:
 * ```php
 * enum Status: int implements HasLabel, HasColor, HasIcon, HasDescription
 * {
 *     use EnumIntegerTrait;  // Inherits all translation methods
 *
 *     case PENDING = 1;
 *     case ACTIVE = 2;
 *     case ARCHIVED = 3;
 * }
 * ```
 *
 * Translation structure (resources/lang/it/modules/xot/enums/status.php):
 * ```php
 * return [
 *     'values' => [
 *         1 => ['label' => 'Pending', 'color' => 'amber', 'icon' => 'clock'],
 *         2 => ['label' => 'Active', 'color' => 'green', 'icon' => 'check'],
 *         3 => ['label' => 'Archived', 'color' => 'gray', 'icon' => 'archive'],
 *     ],
 * ];
 * ```
 *
 * @phpstan-ignore trait.unused (Trade-off: API di piattaforma pubblicata da Xot per gli enum int-backed, documentata in docs/wiki/rules/enum-pattern-filament.md. Nessun consumer in questo repo: si cancella solo se il pattern viene ritirato, non per chiudere l'errore.)
 */
trait EnumIntegerTrait
{
    use EnumTrait;  // ← Inherits getLabel, getColor, getIcon, getDescription via TransTrait

    /**
     * Get next enum case by order.
     *
     * @return static|null Next case or null if at end
     */
    public function next(): ?static
    {
        $cases = static::cases();
        $current = $this;
        $found = false;

        foreach ($cases as $case) {
            if ($found) {
                return $case;
            }
            if ($case->value === $current->value) {
                $found = true;
            }
        }

        return null;
    }

    /**
     * Get previous enum case by order.
     *
     * @return static|null Previous case or null if at start
     */
    public function previous(): ?static
    {
        $cases = static::cases();
        $previous = null;

        foreach ($cases as $case) {
            if ($case->value === $this->value) {
                return $previous;
            }
            $previous = $case;
        }

        return null;
    }

    /**
     * Get enum case by integer value.
     *
<<<<<<< HEAD
     * @param  int  $value  Integer value to find
=======
<<<<<<< .merge_file_4Svs9E
     * @param  int  $value  Integer value to find
=======
     * <<<<<<< HEAD
     *
     * @param int $value Integer value to find
     *                   =======
     *                   <<<<<<< HEAD
     *                   <<<<<<< HEAD
     * @param int $value Integer value to find
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Integer value to find
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_tYlISH
     * @param int $value Integer value to find
     *                   =======
     *                   <<<<<<< HEAD
     * @param int $value Integer value to find
     *                   =======
     * @param int $value Integer value to find
     *                   <<<<<<< .merge_file_1jcXwV
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Integer value to find
     *                   =======
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     * @param int $value Integer value to find
     *                   =======
     * @param int $value Integer value to find
     *
     * >>>>>>> laraxot/dev
     * <<<<<<< HEAD
     * >>>>>>> .merge_file_kRf6cQ
     *
     * >>>>>>> laraxot/dev
     * =======
     * >>>>>>> .merge_file_kRf6cQ
     *
     * >>>>>>> laraxot/dev
     * >>>>>>> .merge_file_cEwT4Z
     *
     * >>>>>>> laraxot/dev
     *
     * >>>>>>> .merge_file_BjMWVg
     * =======
     * @param int $value Integer value to find
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
     *
     * >>>>>>> laraxot/dev
     *
>>>>>>> .merge_file_rkfg3E
>>>>>>> 77b1e2748 (first)
     * @return static|null Matching case or null
     */
    public static function fromInt(int $value): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Check if this enum value is greater than another.
     *
<<<<<<< HEAD
     * @param  int  $value  Value to compare against
=======
<<<<<<< .merge_file_4Svs9E
     * @param  int  $value  Value to compare against
=======
     * <<<<<<< HEAD
     *
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_tYlISH
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   <<<<<<< .merge_file_1jcXwV
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   =======
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_cEwT4Z
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_BjMWVg
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_rkfg3E
>>>>>>> 77b1e2748 (first)
     */
    public function isGreaterThan(int $value): bool
    {
        return $this->value > $value;
    }

    /**
     * Check if this enum value is less than another.
     *
<<<<<<< HEAD
     * @param  int  $value  Value to compare against
=======
<<<<<<< .merge_file_4Svs9E
     * @param  int  $value  Value to compare against
=======
     * <<<<<<< HEAD
     *
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_tYlISH
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   <<<<<<< .merge_file_1jcXwV
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   =======
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_cEwT4Z
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_BjMWVg
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_rkfg3E
>>>>>>> 77b1e2748 (first)
     */
    public function isLessThan(int $value): bool
    {
        return $this->value < $value;
    }

    /**
     * Check if this enum value equals another.
     *
<<<<<<< HEAD
     * @param  int  $value  Value to compare against
=======
<<<<<<< .merge_file_4Svs9E
     * @param  int  $value  Value to compare against
=======
     * <<<<<<< HEAD
     *
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_tYlISH
     * @param int $value Value to compare against
     *                   =======
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   <<<<<<< .merge_file_1jcXwV
     *                   =======
     *                   =======
     *                   <<<<<<< .merge_file_VA3lej
     * @param int $value Value to compare against
     *                   =======
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     * @param int $value Value to compare against
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   <<<<<<< HEAD
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   =======
     *                   >>>>>>> .merge_file_kRf6cQ
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_cEwT4Z
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> .merge_file_BjMWVg
     *                   =======
     * @param int $value Value to compare against
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
     *                   >>>>>>> laraxot/dev
>>>>>>> .merge_file_rkfg3E
>>>>>>> 77b1e2748 (first)
     */
    public function equals(int $value): bool
    {
        return $this->value === $value;
    }
}
