{{--
  Campo di testo dei moduli di accesso (DCP-290, FO-5-10/11/26): etichetta vera, aiuto, errore legato al campo.
  Callers: filament/widgets/auth/register.blade.php.
  Errore: aria-invalid + aria-describedby verso `{name}-error`; l'aiuto resta nel describedby anche con l'errore.
  Password: `revealable` mette l'occhio dentro il bordo (come il login Filament); l'etichetta cambia, il bottone non e' un toggle ARIA.
  Il testo dell'etichetta e' quello dell'attributo nei messaggi: vedi RegisterWidget::validationAttributes().
--}}
@props([
    'name',
    'label',
    'type' => 'text',
    'autocomplete' => null,
    'hint' => null,
    'required' => true,
    'revealable' => false,
])

@php
    $hasError = $errors->has($name);
    $hintId = $name.'-hint';
    $errorId = $name.'-error';
    $describedBy = trim(($hint ? $hintId : '').' '.($hasError ? $errorId : ''));
    $showLabel = __('filament-forms::components.text_input.actions.show_password.label');
    $hideLabel = __('filament-forms::components.text_input.actions.hide_password.label');
@endphp

<div class="auth-field" @if ($revealable) x-data="{ show: false }" @endif>
    <label class="auth-field__label" for="{{ $name }}">
        {{ $label }}@if ($required)<sup class="auth-field__req" aria-hidden="true">*</sup>@endif
    </label>

    @if ($revealable)
        <div class="auth-field__control--reveal">
            <div class="auth-field__input-ctn">
                <input
                    id="{{ $name }}"
                    :type="show ? 'text' : '{{ $type }}'"
                    type="{{ $type }}"
                    wire:model="{{ $name }}"
                    @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
                    @if ($required) required aria-required="true" @endif
                    @if ($hasError) aria-invalid="true" @endif
                    @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
                />
            </div>
            <button
                type="button"
                class="auth-field__toggle"
                @click="show = !show"
                aria-label="{{ $showLabel }}"
                :aria-label="show ? @js($hideLabel) : @js($showLabel)"
            >
                <svg x-show="!show" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <svg x-show="show" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </button>
        </div>
    @else
        <input
            id="{{ $name }}"
            type="{{ $type }}"
            wire:model="{{ $name }}"
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if ($required) required aria-required="true" @endif
            @if ($hasError) aria-invalid="true" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        />
    @endif

    @if ($hint)
        <span id="{{ $hintId }}" class="auth-field__hint">{{ $hint }}</span>
    @endif
    @error($name)
        <span id="{{ $errorId }}" class="auth-field__error">{{ $message }}</span>
    @enderror
</div>
