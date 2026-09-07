<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire\Profile;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\User\Models\User;
>>>>>>> f548be94 (.)
=======
use Modules\User\Models\User;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\User\Actions\User\DeleteUserAction;
use Modules\User\Contracts\UserContract;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\User;
=======
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Models\User;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class DeleteAccount extends Component
{
    public string $delete_confirm_password = '';

    public function render(): View
    {
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var view-string $viewName */
        $viewName = 'user::livewire.profile.delete-account';

        return view($viewName);
=======
        return view('user::livewire.profile.delete-account');
>>>>>>> f548be94 (.)
=======
        return view('user::livewire.profile.delete-account');
=======
        /** @var view-string $viewName */
        $viewName = 'user::livewire.profile.delete-account';

        return view($viewName);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function destroy(): void
    {
        /** @var User|null $user */
        $user = Auth::user();
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user) {
=======
        if (!$user) {
>>>>>>> f548be94 (.)
=======
        if (!$user) {
=======
        if (! $user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $this->dispatch('toast', [
                'message' => 'Utente non trovato',
                'type' => 'error',
            ]);
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        // Assicuriamoci che sia del tipo corretto per l'action
<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user instanceof UserContract) {
=======
        if (!($user instanceof UserContract)) {
>>>>>>> f548be94 (.)
=======
        if (!($user instanceof UserContract)) {
=======
        if (! $user instanceof UserContract) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $this->dispatch('toast', [
                'message' => 'Tipo di utente non supportato',
                'type' => 'error',
            ]);
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        $result = app(DeleteUserAction::class)->execute($user, $this->delete_confirm_password);

<<<<<<< HEAD
<<<<<<< HEAD
        if (! $result['success']) {
=======
        if (!$result['success']) {
>>>>>>> f548be94 (.)
=======
        if (!$result['success']) {
=======
        if (! $result['success']) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $this->dispatch('toast', [
                'message' => $result['message'],
                'type' => 'error',
            ]);
            $this->reset(['delete_confirm_password']);
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        $this->redirect('/');
    }
}
