<?php

declare(strict_types=1);

namespace Modules\User\Filament\Actions;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Modules\Xot\Filament\Actions\XotBaseAction;

final class AlwaysAskPasswordConfirmationAction extends XotBaseAction
=======
=======
>>>>>>> 87273113 (.)
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

class AlwaysAskPasswordConfirmationAction extends Action
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Modules\Xot\Filament\Actions\XotBaseAction;

final class AlwaysAskPasswordConfirmationAction extends XotBaseAction
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    protected function setUp(): void
    {
        $this->requiresConfirmation()
            ->modalHeading(__('filament-jet::jet.password_confirmation_modal.heading'))
<<<<<<< HEAD
<<<<<<< HEAD
            ->modalDescription(__('filament-jet::jet.password_confirmation_modal.description'))
=======
            ->modalSubheading(__('filament-jet::jet.password_confirmation_modal.description'))
>>>>>>> f548be94 (.)
=======
            ->modalSubheading(__('filament-jet::jet.password_confirmation_modal.description'))
=======
            ->modalDescription(__('filament-jet::jet.password_confirmation_modal.description'))
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ->schema([
                TextInput::make('current_password')
                    ->required()
                    ->password()
                    ->rule('current_password'),
            ]);
    }
}
