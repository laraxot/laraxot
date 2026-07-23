<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;

name('logout');

new class extends Component
{
    public function logout(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('/'.app()->getLocale().'/auth/login', navigate: false);
    }
}

?>

<div wire:init="logout" class="d-flex align-items-center justify-content-center min-vh-100">
    <p class="text-muted">Disconnessione in corso…</p>
</div>
