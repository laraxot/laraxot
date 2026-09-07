<?php

declare(strict_types=1);

namespace Modules\User\Http\Livewire\Auth\Passwords;

<<<<<<< HEAD
use Illuminate\Contracts\View\View;
<<<<<<< HEAD
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Modules\Xot\Actions\File\ViewCopyAction;
=======
use Illuminate\Contracts\View\Factory;
use Modules\Xot\Actions\File\ViewCopyAction;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Http\RedirectResponse;
use Livewire\Component;
use Modules\Xot\Actions\File\ViewCopyAction;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

class Confirm extends Component
{
    public string $password = '';

    public function confirm(): RedirectResponse
    {
        $this->validate([
            'password' => 'required|current_password',
        ]);

        session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('home'));
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function render(): View
=======
    public function render(): View|Factory
>>>>>>> f548be94 (.)
=======
    public function render(): View|Factory
=======
    public function render(): mixed
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        app(ViewCopyAction::class)
            ->execute('user::livewire.auth.passwords.confirm', 'pub_theme::livewire.auth.passwords.confirm');
        app(ViewCopyAction::class)->execute('user::layouts.auth', 'pub_theme::layouts.auth');
        app(ViewCopyAction::class)->execute('user::layouts.base', 'pub_theme::layouts.base');

        /**
         * @phpstan-var view-string
         */
        $view = 'pub_theme::livewire.auth.passwords.confirm';

        return view($view)->extends('pub_theme::layouts.auth');
    }
}
