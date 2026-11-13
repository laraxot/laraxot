<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\User\Actions\User\DeleteUserAction;
use Modules\User\Contracts\UserContract;
<<<<<<< .merge_file_Qr4QeI
use Modules\User\Models\User;
=======
>>>>>>> .merge_file_3yMRrx
use Modules\Xot\Filament\Widgets\XotBaseWidget;

/**
 * DeleteAccountWidget: widget per la cancellazione dell'account dal menu utente.
 *
 * Sostituisce il vecchio Livewire `Modules\User\Http\Livewire\Profile\DeleteAccount`.
 */
final class DeleteAccountWidget extends XotBaseWidget
{
    public string $delete_confirm_password = '';

    public function render(): View
    {
        /** @var view-string $viewName */
        $viewName = 'user::filament.widgets.profile.delete-account';

        return view($viewName);
    }

    public function destroy(): void
    {
<<<<<<< .merge_file_Qr4QeI
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) {
            $this->dispatch('toast', [
                'message' => 'Utente non trovato',
=======
        /** @var UserContract|null $user */
        $user = Auth::user();
        if (! $user) {
            $this->dispatch('toast', [
                'message' => __('user::messages.user_not_found'),
>>>>>>> .merge_file_3yMRrx
                'type' => 'error',
            ]);

            return;
        }

        if (! $user instanceof UserContract) {
            $this->dispatch('toast', [
<<<<<<< .merge_file_Qr4QeI
                'message' => 'Utente non valido',
=======
                'message' => __('user::messages.user_invalid'),
>>>>>>> .merge_file_3yMRrx
                'type' => 'error',
            ]);

            return;
        }

<<<<<<< .merge_file_Qr4QeI
        $result = app(DeleteUserAction::class)->execute($user, $this->delete_confirm_password);
=======
        app(DeleteUserAction::class)->execute($user, $this->delete_confirm_password);
>>>>>>> .merge_file_3yMRrx
    }
}
