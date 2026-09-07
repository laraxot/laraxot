<<<<<<< HEAD
<<<<<<< HEAD
<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex items-center justify-between mt-4">
            <div class="text-sm">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('user::auth.register.login.text') }}
                </a>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('user::auth.register.submit.text') }}
            </button>
        </div>
    </form>
</div>
=======
=======
>>>>>>> 87273113 (.)
<?php

declare(strict_types=1);

?>
@php
    $form = $getForm();
@endphp

<div class="space-y-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold tracking-tight">
            {{ __('user::auth.register.title') }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ __('user::auth.register.subtitle') }}
        </p>
    </div>

    <form wire:submit="register" class="space-y-6">
        {{ $form }}

        <div class="flex items-center justify-between">
            <div class="text-sm">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('user::auth.register.already_registered') }}
                </a>
            </div>
        </div>

        <div>
            <x-filament::button
                type="submit"
                class="w-full"
            >
                {{ __('user::auth.register.submit') }}
            </x-filament::button>
        </div>
    </form>
</div>
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex items-center justify-between mt-4">
            <div class="text-sm">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('user::auth.register.login.text') }}
                </a>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('user::auth.register.submit.text') }}
            </button>
        </div>
    </form>
</div>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
