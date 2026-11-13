<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Forms\Components;

use Filament\Forms\Components\Select as FilamentSelect;

/**
 * Base class for custom Select components following Laraxot philosophy.
 *
 * In the Laraxot framework, all custom Select components should extend
 * XotBaseSelect instead of directly extending Filament\Forms\Components\Select.
 * This ensures consistency with the framework's architecture and provides
 * a foundation for common Select functionality across the application.
 *
<<<<<<< HEAD
 * @method static static make(?string $name = null) Create a new instance of the component
=======
<<<<<<< .merge_file_i8KiMA
 * @method static static make(?string $name = null) Create a new instance of the component
=======
 * <<<<<<< HEAD
 *
 * @method static static make(?string $name = null) Create a new instance of the component
 *                                                  =======
 *                                                  <<<<<<< HEAD
 *                                                  <<<<<<< HEAD
 * @method static static make(?string $name = null) Create a new instance of the component
 *                                                  =======
 * @method static static make(string $name)         Create a new instance of the component
 *                                                  >>>>>>> laraxot/dev
 *                                                  =======
 * @method static static make(?string $name = null) Create a new instance of the component
 *                                                  >>>>>>> laraxot/dev
 *                                                  >>>>>>> laraxot/dev
>>>>>>> .merge_file_wOXwTG
>>>>>>> 77b1e2748 (first)
 */
abstract class XotBaseSelect extends FilamentSelect
{
    protected function setUp(): void
    {
        parent::setUp();
        // Common setup for all XotBaseSelect components can be added here.
    }
}
