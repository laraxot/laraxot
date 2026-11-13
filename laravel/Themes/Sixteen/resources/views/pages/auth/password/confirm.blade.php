<?php

declare(strict_types=1);

use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\name;

name('password.confirm');

new class extends Component {
    #[Validate('required|current_password')]
    public string $password = '';

    public function confirm(): void
    {
        $this->validate();
        session()->put('auth.password_confirmed_at', time());
        redirect()->intended(route('home'));
    }
};
?>

{{--
  Conferma password: stesso guscio delle altre pagine di accesso (x-auth.shell via partial, DCP-290).
  Prima era una card Bootstrap `card shadow-sm` separata dal login/registrazione: incoerente.
  Logica invariata: `auth.password_confirmed_at` + redirect()->intended.
--}}
<x-layouts.app
    :title="__('user::auth_pages.sections.password_confirm.title')"
    :meta-description="__('user::auth_pages.sections.password_confirm.description')"
    body-page="auth-password-confirm"
>
    @volt('auth.password.confirm')
        <div class="page-content content" data-dc data-slug="auth.password.confirm" data-side="content">
            @include('pub_theme::components.pages.auth.password-confirm')
        </div>
    @endvolt
</x-layouts.app>
