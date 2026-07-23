<?php

use function Laravel\Folio\{middleware, name};
use Filament\Notifications\Notification;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
use Livewire\Volt\Component;
use Modules\Cms\Http\Volt\LoginComponent;

/** @var array */
//$base_middleware=Arr::get($middleware,'base',[]);
$base_middleware=[];

name('counter');
middleware(['guest']);
 
new class extends LoginComponent {
} 


?>
 
<x-layouts.main>

    <div class="flex flex-col items-stretch justify-center w-screen min-h-screen py-10 sm:items-center">

        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.link href="{{ route('home') }}">
                <x-ui.logo class="w-auto h-10 mx-auto text-gray-700 fill-current dark:text-gray-100" />
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

                    <h1>{{ $count }}</h1>
                    <button wire:click="increment">+</button>
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
