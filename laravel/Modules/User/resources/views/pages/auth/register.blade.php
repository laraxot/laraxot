<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use function Laravel\Folio\{middleware, name};
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$segments = request()->segments();
$locale = $segments[0] ?? 'it';
if (in_array($locale, ['it', 'en', 'es', 'de', 'fr', 'ru'], true)) {
    LaravelLocalization::setLocale($locale);
    app()->setLocale($locale);
}
=======
=======
>>>>>>> 87273113 (.)

use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use function Laravel\Folio\{middleware, name};
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$segments = request()->segments();
$locale = $segments[0] ?? 'it';
if (in_array($locale, ['it', 'en', 'es', 'de', 'fr', 'ru'], true)) {
    LaravelLocalization::setLocale($locale);
    app()->setLocale($locale);
}
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

middleware(['guest']);
name('register');

<<<<<<< HEAD
<<<<<<< HEAD
?>

<x-layouts.app>
    <x-slot name="title">
        {{ __('gdpr::register.title') }} - <nome progetto> Community
    </x-slot>

    <x-slot name="description">
        {{ __('gdpr::register.subtitle') }}
    </x-slot>

    <x-slot name="keywords">
        Laravel meetup, Laravel community, PHP developer community, Laravel tutorials, Laravel workshops, Laravel networking, <nome progetto>
    </x-slot>

    <section
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 relative overflow-hidden py-12 px-4"
        aria-labelledby="register-heading"
    >
        @include('pub_theme::components.ui.particles')
        
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-48 -right-32 w-80 h-80 bg-red-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-48 -left-36 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[28rem] h-[28rem] bg-red-600/5 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-6xl mx-auto relative z-10">
            <div class="space-y-10">
                <div class="text-center space-y-6">
                    <a href="{{ \LaravelLocalization::localizeUrl('/') }}" class="inline-block group" aria-label="{{ config('app.name') }}">
                        <x-ui.logo class="h-16 w-auto md:h-20 transition-transform duration-300 group-hover:scale-110" />
                    </a>

                    <div class="space-y-3">
                        <h1
                            id="register-heading"
                            class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white"
                        >
                            {{ __('gdpr::register.title') }}
                        </h1>
                        <p class="text-base sm:text-lg text-slate-200 max-w-3xl mx-auto">
                            {{ __('gdpr::register.subtitle') }}
                        </p>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-xl shadow-2xl rounded-2xl p-6 sm:p-8 md:p-10 border border-slate-800/70 w-full mx-auto max-w-2xl">
                    <div class="text-center mb-6 space-y-1">
                        <h2 class="text-2xl font-bold text-white">{{ __('gdpr::register.form.cta_title') }}</h2>
                        <p class="text-sm text-slate-400">{{ __('gdpr::register.form.cta_subtitle') }}</p>
                    </div>
                    @livewire(\Modules\Gdpr\Filament\Widgets\Auth\UserForm::class)
                    <p class="mt-4 text-center text-xs text-slate-500">{{ __('gdpr::register.form.terms_notice') }}</p>
                </div>

                <div class="grid md:grid-cols-3 gap-5">
                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-red-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center mt-1 text-red-300">
                            <x-filament::icon icon="heroicon-o-users" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.community.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.community.description') }}</p>
                        </div>
                    </article>

                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-orange-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center mt-1 text-orange-300">
                            <x-filament::icon icon="heroicon-o-academic-cap" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.tutorials.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.tutorials.description') }}</p>
                        </div>
                    </article>

                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-green-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center mt-1 text-green-300">
                            <x-filament::icon icon="heroicon-o-briefcase" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.networking.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.networking.description') }}</p>
                        </div>
                    </article>
                </div>

                <div class="text-center pt-6 border-t border-slate-800/60">
                    <p class="text-sm text-slate-300">
                        {{ __('gdpr::register.already_registered') }}
                        <a href="{{ \LaravelLocalization::localizeUrl('/auth/login') }}"
                           class="font-bold text-red-400 hover:text-red-300 transition-colors ml-1">
                            {{ __('gdpr::register.login') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>
=======
=======
>>>>>>> 87273113 (.)
new class extends Component {
    #[Validate('required')]
    public $name = '';

    #[Validate('required|email|unique:users')]
    public $email = '';

    #[Validate('required|min:8|same:passwordConfirmation')]
    public $password = '';

    #[Validate('required|min:8|same:password')]
    public $passwordConfirmation = '';

    public function register()
    {
        $this->validate();

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect()->intended('/');
    }
};

?>

<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white py-12">
        <div class="max-w-lg mx-auto px-6">
            <!-- Logo e intestazione -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <x-ui.logo class="h-12 text-blue-900" />
                </div>
                <h1 class="text-3xl font-light text-blue-900">Benvenuto in <span class="font-bold">il progetto</span></h1>
                <p class="text-gray-600 mt-2">Crea il tuo account per accedere a tutti i servizi1</p>
            </div>

            <!-- Card contenente il form di registrazione -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Intestazione card -->
                <div class="bg-blue-900 px-6 py-4">
                    <h2 class="text-xl font-medium text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        Registrazione
                    </h2>
                </div>

                <!-- Form di registrazione -->
                <div class="p-6">
                    @livewire(\Modules\User\Filament\Widgets\RegistrationWidget::class)
                </div>
            </div>

            <!-- Footer con informazioni aggiuntive -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Hai bisogno di assistenza? <a href="#" class="text-blue-800 hover:underline">Contattaci</a></p>
            </div>
        </div>
    </div>
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
?>

<x-layouts.app>
    <x-slot name="title">
        {{ __('gdpr::register.title') }} - <nome progetto> Community
    </x-slot>

    <x-slot name="description">
        {{ __('gdpr::register.subtitle') }}
    </x-slot>

    <x-slot name="keywords">
        Laravel meetup, Laravel community, PHP developer community, Laravel tutorials, Laravel workshops, Laravel networking, <nome progetto>
    </x-slot>

    <section
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-red-950 relative overflow-hidden py-12 px-4"
        aria-labelledby="register-heading"
    >
        @include('pub_theme::components.ui.particles')
        
        <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
            <div class="absolute -top-48 -right-32 w-80 h-80 bg-red-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-48 -left-36 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[28rem] h-[28rem] bg-red-600/5 rounded-full blur-3xl"></div>
        </div>

        <div class="w-full max-w-6xl mx-auto relative z-10">
            <div class="space-y-10">
                <div class="text-center space-y-6">
                    <a href="{{ \LaravelLocalization::localizeUrl('/') }}" class="inline-block group" aria-label="{{ config('app.name') }}">
                        <x-ui.logo class="h-16 w-auto md:h-20 transition-transform duration-300 group-hover:scale-110" />
                    </a>

                    <div class="space-y-3">
                        <h1
                            id="register-heading"
                            class="text-4xl sm:text-5xl font-extrabold tracking-tight text-white"
                        >
                            {{ __('gdpr::register.title') }}
                        </h1>
                        <p class="text-base sm:text-lg text-slate-200 max-w-3xl mx-auto">
                            {{ __('gdpr::register.subtitle') }}
                        </p>
                    </div>
                </div>

                <div class="bg-slate-900/80 backdrop-blur-xl shadow-2xl rounded-2xl p-6 sm:p-8 md:p-10 border border-slate-800/70 w-full mx-auto max-w-2xl">
                    <div class="text-center mb-6 space-y-1">
                        <h2 class="text-2xl font-bold text-white">{{ __('gdpr::register.form.cta_title') }}</h2>
                        <p class="text-sm text-slate-400">{{ __('gdpr::register.form.cta_subtitle') }}</p>
                    </div>
                    @livewire(\Modules\Gdpr\Filament\Widgets\Auth\UserForm::class)
                    <p class="mt-4 text-center text-xs text-slate-500">{{ __('gdpr::register.form.terms_notice') }}</p>
                </div>

                <div class="grid md:grid-cols-3 gap-5">
                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-red-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center mt-1 text-red-300">
                            <x-filament::icon icon="heroicon-o-users" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.community.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.community.description') }}</p>
                        </div>
                    </article>

                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-orange-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center mt-1 text-orange-300">
                            <x-filament::icon icon="heroicon-o-academic-cap" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.tutorials.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.tutorials.description') }}</p>
                        </div>
                    </article>

                    <article class="flex gap-4 bg-white/5 border border-white/10 rounded-xl p-4 hover:border-green-400/50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center mt-1 text-green-300">
                            <x-filament::icon icon="heroicon-o-briefcase" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-semibold text-white">{{ __('gdpr::register.benefits.networking.title') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('gdpr::register.benefits.networking.description') }}</p>
                        </div>
                    </article>
                </div>

                <div class="text-center pt-6 border-t border-slate-800/60">
                    <p class="text-sm text-slate-300">
                        {{ __('gdpr::register.already_registered') }}
                        <a href="{{ \LaravelLocalization::localizeUrl('/auth/login') }}"
                           class="font-bold text-red-400 hover:text-red-300 transition-colors ml-1">
                            {{ __('gdpr::register.login') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </section>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
</x-layouts.app>
