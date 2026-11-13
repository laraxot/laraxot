{{--
  Riepilogo errori dei moduli di accesso (DCP-290, FO-5-10): un solo avviso role="alert" con il conteggio e tutti i messaggi.
  Callers: filament/widgets/auth/{login,register,forgot-password,reset-password}.blade.php.
  Gli errori sono quelli del widget Livewire ($errors e' condiviso con i componenti anonimi durante il rendering).
  Il campo con errore e' marcato a parte (aria-invalid + aria-describedby): qui si elenca solo cosa correggere.
--}}
@php
    $summaryMessages = collect($errors->all())->filter(fn ($message): bool => is_string($message) && $message !== '')->unique()->values();
@endphp

@if ($summaryMessages->isNotEmpty())
    <div class="auth-login-alert" role="alert" aria-live="assertive" data-auth-error-summary>
        <svg class="auth-login-alert__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
            <path fill-rule="evenodd" d="M18 10A8 8 0 114.293 4.293 8 8 0 0118 10zm-8.75-3.25a.75.75 0 011.5 0v3.5a.75.75 0 01-1.5 0v-3.5zm.75 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
        </svg>
        <div class="auth-login-alert__text">
            <p class="auth-summary__title">{{ trans_choice('pub_theme::auth_form.summary', $summaryMessages->count()) }}</p>
            <ul class="auth-summary__list">
                @foreach ($summaryMessages as $summaryMessage)
                    <li>{{ $summaryMessage }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
