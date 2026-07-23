{{-- RegisterWidget — vestito Sixteen; logica in Modules\User\Filament\Widgets\Auth\RegisterWidget --}}

<x-filament-widgets::widget>
    <x-filament::section class="rounded-none border-0 shadow-none">
        <div class="space-y-6 p-5 sm:p-7">
            @php
                $registerErrors = collect($errors->all())->filter()->unique()->values();
            @endphp

            @if ($registerErrors->isNotEmpty())
                <div
                    class="rounded-xl border-2 border-red-300 bg-red-50 px-4 py-4 shadow-sm"
                    role="alert"
                    aria-live="assertive"
                >
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-700" aria-hidden="true">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10A8 8 0 114.293 4.293 8 8 0 0118 10zm-8.75-3.25a.75.75 0 011.5 0v3.5a.75.75 0 01-1.5 0v-3.5zm.75 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-red-800">
                                {{ __('user::auth.register.failed.text') }}
                            </p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                                @foreach ($registerErrors as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form
                wire:submit="submit"
                class="space-y-6"
                aria-labelledby="auth-register-heading"
                novalidate
            >
                <div
                    @class([
                        'fo-filament-form-shell rounded-xl border border-slate-200 p-4 transition-colors',
                        'border-red-300 bg-red-50/40' => $registerErrors->isNotEmpty(),
                    ])
                >
                    {{ $this->form }}
                </div>

                <x-filament::button
                    type="submit"
                    color="primary"
                    wire:loading.attr="disabled"
                    class="w-full min-h-[44px]"
                >
                    {{ __('user::auth.register.submit.text') }}
                </x-filament::button>

                <nav class="border-t border-slate-200 pt-4 text-sm" aria-label="{{ __('user::auth.register.already_have_account.text') }}">
                    <p class="text-slate-600">
                        {{ __('user::auth.register.already_have_account.text') }}
                        <a
                            href="{{ url('/' . app()->getLocale() . '/auth/login') }}"
                            class="font-semibold text-italia-blue-700 underline decoration-italia-blue-500 underline-offset-2 hover:text-italia-blue-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-600 focus-visible:ring-offset-2"
                        >
                            {{ __('user::auth.register.login.text') }}
                        </a>
                    </p>
                </nav>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
