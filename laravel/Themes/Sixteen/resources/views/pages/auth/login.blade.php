<x-layouts.app bodyPage="auth-login">
    <x-slot name="title">
        {{ __('user::auth.login.page.meta_title.label') }}
    </x-slot>

    <x-slot name="metaDescription">
        {{ __('user::auth.login.page.description.label') }}
    </x-slot>

    <section class="bg-slate-50 py-10 sm:py-14">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            <header class="mb-8">
                <p class="text-sm font-semibold tracking-wide text-primary-700 uppercase">
                    {{ __('user::auth.login.page.kicker.label') }}
                </p>
                <h1 class="mt-1 text-3xl font-bold text-slate-900" id="auth-login-heading">
                    {{ __('user::auth.login.page.title.label') }}
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-600">
                    {{ __('user::auth.login.page.description.label') }}
                </p>
            </header>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="auth-login-card overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="p-5 sm:p-7 space-y-6">
                            @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
                            @livewire(\Modules\User\Filament\Widgets\Auth\SocialLoginWidget::class)
                            <nav class="space-y-3 border-t border-slate-200 pt-4 text-sm" aria-label="{{ __('user::auth.login.page.support_title.label') }}">
                                <p class="text-slate-600">
                                    {{ __('user::login.no_account') }}
                                    <a href="{{ url('/' . app()->getLocale() . '/auth/register') }}"
                                        class="font-semibold text-italia-blue-700 underline decoration-italia-blue-500 underline-offset-2 hover:text-italia-blue-800"
                                    >{{ __('user::login.register_now') }}</a>
                                </p>
                                <p class="text-slate-600">
                                    {{ __('user::login.forgot_password_text') }}
                                    <a href="{{ url('/' . app()->getLocale() . '/auth/password/reset') }}"
                                        class="font-semibold text-italia-blue-700 underline decoration-italia-blue-500 underline-offset-2 hover:text-italia-blue-800"
                                    >{{ __('user::login.reset_it') }}</a>
                                </p>
                            </nav>
                        </div>
                    </div>
                </div>

                <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" aria-labelledby="auth-login-support-heading">
                    <h2 class="text-base font-semibold text-slate-900" id="auth-login-support-heading">
                        {{ __('user::auth.login.page.support_title.label') }}
                    </h2>
                    <ul class="mt-4 list-none space-y-3 p-0 text-sm text-slate-600">
                        <li>{{ __('user::auth.login.page.support_item_email.label') }}</li>
                        <li>{{ __('user::auth.login.page.support_item_password.label') }}</li>
                        <li>{{ __('user::auth.login.page.support_item_help.label') }}</li>
                    </ul>
                </aside>
            </div>

            @if (Route::has('register'))
                <div class="mt-8 rounded-xl border border-primary-200 bg-primary-50 p-4 text-sm text-slate-900">
                    {{ __('user::auth.login.page.register_cta_text.label') }}
                    <a href="{{ route('register') }}" class="ml-1 font-semibold text-primary-700 underline decoration-primary-600 underline-offset-2">
                        {{ __('user::auth.login.page.register_cta_link.label') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
