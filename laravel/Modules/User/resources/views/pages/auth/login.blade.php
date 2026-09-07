<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
=======

use App\Models\User;
>>>>>>> f548be94 (.)
=======

use App\Models\User;
=======
use Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Auth\Events\Login;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

middleware(['guest']);
name('login');

new class extends Component {
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function authenticate()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
<<<<<<< HEAD
<<<<<<< HEAD
            $this->addError('email', trans('user::login.actions.login.error'));
=======
            $this->addError('email', trans('auth.failed'));
>>>>>>> f548be94 (.)
=======
            $this->addError('email', trans('auth.failed'));
=======
            $this->addError('email', trans('user::login.actions.login.error'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return;
        }

        event(new Login(auth()->guard('web'), User::where('email', $this->email)->first(), $this->remember));

        return redirect()->intended('/');
    }
};

?>

<<<<<<< HEAD
<<<<<<< HEAD
<x-layouts.guest>
    <x-slot name="title">
        {{ __('user::login.title') }}
    </x-slot>
    <x-slot name="subtitle">
        {{ __('user::login.subtitle_start') }}
        <x-ui.text-link href="{{ route('register') }}">{{ __('user::login.subtitle_link') }}</x-ui.text-link>
    </x-slot>

    @volt('auth.login')
    <form wire:submit="authenticate" class="space-y-6">

        <x-ui.input 
            label="{{ __('user::login.fields.email.label') }}" 
            type="email" 
            id="email" 
            name="email" 
            wire:model="email" 
            placeholder="{{ __('user::login.fields.email.placeholder') }}"
        />

        <x-ui.input 
            label="{{ __('user::login.fields.password.label') }}" 
            type="password" 
            id="password" 
            name="password" 
            wire:model="password" 
            placeholder="{{ __('user::login.fields.password.placeholder') }}"
        />

        <div class="flex items-center justify-between mt-6 text-sm leading-5">
            <x-ui.checkbox 
                label="{{ __('user::login.fields.remember.label') }}" 
                id="remember" 
                name="remember" 
                wire:model="remember" 
            />
            <x-ui.text-link href="{{ route('password.request') }}">{{ __('user::login.actions.forgot_password.label') }}</x-ui.text-link>
        </div>

        <x-ui.button type="primary" rounded="md" submit="true" class="w-full flex justify-center">
            {{ __('user::login.actions.login.label') }}
        </x-ui.button>
    </form>
    @endvolt

</x-layouts.guest>
=======
=======
>>>>>>> 87273113 (.)
<x-layouts.main>

    <div class="flex flex-col items-stretch justify-center w-screen min-h-screen py-10 sm:items-center">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.link href="{{ route('home') }}">
                <x-ui.logo class="w-auto h-10 mx-auto text-gray-700 fill-current dark:text-gray-100" />
            </x-ui.link>

            <h2 class="mt-5 text-2xl font-extrabold leading-9 text-center text-gray-800 dark:text-gray-200">Sign in to
                your account</h2>
            <div class="text-sm leading-5 text-center text-gray-600 dark:text-gray-400 space-x-0.5">
                <span>Or</span>
                <x-ui.text-link href="{{ route('register') }}">create a new account</x-ui.text-link>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-10 py-0 sm:py-8 sm:shadow-sm sm:bg-white dark:sm:bg-gray-950/50 dark:border-gray-200/10 sm:border sm:rounded-lg border-gray-200/60">
                @volt('auth.login')
                <form wire:submit="authenticate" class="space-y-6">

                    <x-ui.input label="Email address" type="email" id="email" name="email" wire:model="email" />
                    <x-ui.input label="Password" type="password" id="password" name="password" wire:model="password" />

                    <div class="flex items-center justify-between mt-6 text-sm leading-5">
                        <x-ui.checkbox label="Remember me" id="remember" name="remember" wire:model="remember" />
                        <x-ui.text-link href="{{ route('password.request') }}">Forgot your password?</x-ui.text-link>
                    </div>

                    <x-ui.button type="primary" rounded="md" submit="true">Sign in</x-ui.button>
                </form>
                @endvolt
            </div>
        </div>

    </div>

</x-layouts.main>
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
<x-layouts.guest>
    <x-slot name="title">
        {{ __('user::login.title') }}
    </x-slot>
    <x-slot name="subtitle">
        {{ __('user::login.subtitle_start') }}
        <x-ui.text-link href="{{ route('register') }}">{{ __('user::login.subtitle_link') }}</x-ui.text-link>
    </x-slot>

    @volt('auth.login')
    <form wire:submit="authenticate" class="space-y-6">

        <x-ui.input 
            label="{{ __('user::login.fields.email.label') }}" 
            type="email" 
            id="email" 
            name="email" 
            wire:model="email" 
            placeholder="{{ __('user::login.fields.email.placeholder') }}"
        />

        <x-ui.input 
            label="{{ __('user::login.fields.password.label') }}" 
            type="password" 
            id="password" 
            name="password" 
            wire:model="password" 
            placeholder="{{ __('user::login.fields.password.placeholder') }}"
        />

        <div class="flex items-center justify-between mt-6 text-sm leading-5">
            <x-ui.checkbox 
                label="{{ __('user::login.fields.remember.label') }}" 
                id="remember" 
                name="remember" 
                wire:model="remember" 
            />
            <x-ui.text-link href="{{ route('password.request') }}">{{ __('user::login.actions.forgot_password.label') }}</x-ui.text-link>
        </div>

        <x-ui.button type="primary" rounded="md" submit="true" class="w-full flex justify-center">
            {{ __('user::login.actions.login.label') }}
        </x-ui.button>
    </form>
    @endvolt

</x-layouts.guest>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
