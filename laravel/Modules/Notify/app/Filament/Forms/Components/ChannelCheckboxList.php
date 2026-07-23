<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Forms\Components;

use Modules\Notify\Enums\ChannelEnum;
use Modules\Xot\Filament\Forms\Components\XotBaseCheckboxList;

/**
 * Reusable CheckboxList component for ChannelEnum selection.
 *
 * Pre-configured with:
 * - Label from translation files
 * - Options from ChannelEnum cases (using getLabel() for translations)
 * - 3 columns layout
 * - Required validation
 *
 * Usage:
 * ```php
 * ChannelCheckboxList::make()
 *     ->minItems(1) // Optional: require at least one channel
 * ```
 */
class ChannelCheckboxList extends XotBaseCheckboxList
{
    /**
     * Set up the component configuration.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->options(ChannelEnum::class)
            ->columns(3)
            ->required();
    }

    /**
     * Create a new ChannelCheckboxList instance.
     *
     * @param  string|null  $name  Field name (default: 'channels')
     */
    public static function make(?string $name = null): static
    {
        $name = $name ?? 'channels';

        return parent::make($name);
    }
}
