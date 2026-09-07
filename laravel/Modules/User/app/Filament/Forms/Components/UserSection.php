<?php

declare(strict_types=1);

/*
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

namespace Modules\User\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Schemas\Components\XotBaseSection;

class UserSection extends XotBaseSection
{
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Section;

class UserSection extends Section
{
    public static function getDefaultName(): ?string
    {
        return 'user';
    }

<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\Xot\Filament\Schemas\Components\XotBaseSection;

class UserSection extends XotBaseSection
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected function setUp(): void
    {
        parent::setUp();

        $this->schema([
            Grid::make(4)->schema([
                // TextInput::make('ente'),
                // TextInput::make('matr'),
                TextInput::make('first_name'),
                TextInput::make('last_name'),
                TextInput::make('email'),
            ]),
        ]);
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)

    public static function getDefaultName(): ?string
    {
        return 'user';
    }
<<<<<<< HEAD
=======
>>>>>>> 60a2c9a9 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
