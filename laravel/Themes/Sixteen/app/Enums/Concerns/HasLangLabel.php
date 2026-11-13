<?php

declare(strict_types=1);

namespace Themes\Sixteen\Enums\Concerns;

use BackedEnum;
use Illuminate\Support\Str;

/**
 * Label di un backed enum del tema, risolta da lang/<locale>/<nome_enum_snake_case>.php
 * (namespace `sixteen::`): `'values' => ['<valore>' => ['label' => '...']]`.
 *
 * `Modules\Xot\Traits\EnumTrait` non e' usabile nei temi: deriva la chiave lang da `Modules\<M>\...`.
 *
 * @phpstan-require-implements BackedEnum
 */
trait HasLangLabel
{
    public function getLabel(): string
    {
        $key = 'sixteen::'.Str::snake(class_basename(static::class)).'.values.'.$this->value.'.label';
        $label = trans($key);

        return is_string($label) ? $label : $key;
    }

    /**
     * Opzioni [valore => label] per select e filtri.
     *
     * @return array<value-of<static>, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (static::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }

        return $options;
    }
}
