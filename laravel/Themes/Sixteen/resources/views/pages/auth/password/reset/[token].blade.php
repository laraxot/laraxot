<?php

declare(strict_types=1);

use Livewire\Volt\Component;

use function Laravel\Folio\name;

name('password.reset.token');

new class extends Component {
    public string $token = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }
};
?>

<x-layouts.app
    :title="__('user::password_recovery.reset_title')"
    :meta-description="__('user::password_recovery.reset_intro')"
>
    @volt('password.reset.token')
        <div class="page-content content" data-dc data-side="content">
            @include('pub_theme::components.pages.auth.password-token', [
                'token' => $this->token,
                'email' => request()->query('email'),
            ])
        </div>
    @endvolt
</x-layouts.app>
