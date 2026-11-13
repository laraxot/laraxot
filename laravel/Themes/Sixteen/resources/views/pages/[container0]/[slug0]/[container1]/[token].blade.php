<?php

declare(strict_types=1);

use Livewire\Volt\Component;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('container1.token');

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $container1 = '';
    public string $token = '';

    public function mount(string $container0, string $slug0, string $container1, string $token): void
    {
        // L'unica pagina a quattro segmenti e' il ripristino della password: per ogni altro percorso il frammento non avrebbe radice
        // e Livewire darebbe 500. Un percorso senza pagina e' un 404 vero (stesso criterio di DCP-126 per i due segmenti).
        abort_unless($container0 === 'auth' && $slug0 === 'password' && $container1 === 'reset', 404);

        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;
        $this->token = $token;
    }
};
?>

<x-layouts.app :title="__('pub_theme::auth_components.reset.title.label')">
    @volt('container1.token')
        @if ($container0 === 'auth' && $slug0 === 'password' && $container1 === 'reset')
            <section class="bg-slate-50 px-4 py-12 sm:px-6 lg:px-8" aria-labelledby="auth-password-token-heading">
                <div class="mx-auto max-w-md">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <h1 id="auth-password-token-heading" class="text-3xl font-bold tracking-tight text-slate-950">{{ __('pub_theme::auth_components.reset.title.label') }}</h1>
                        <p class="mt-2 text-slate-700">{{ __('pub_theme::auth_components.reset.help.message') }}</p>
                        <div class="mt-8">@livewire(\Modules\User\Filament\Widgets\Auth\ResetPasswordWidget::class, ['token' => $token, 'email' => request()->query('email')])</div>
                    </div>
                </div>
            </section>
        @endif
    @endvolt
</x-layouts.app>
