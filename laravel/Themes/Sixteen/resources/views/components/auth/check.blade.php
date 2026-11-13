{{--
  Casella di consenso dei moduli di accesso (DCP-290, FO-5-10): etichetta cliccabile (slot), errore legato alla casella.
  Callers: filament/widgets/auth/register.blade.php (privacy, termini, marketing).
  Errore: aria-invalid + aria-describedby verso `{name}-error`, testo tradotto dal widget (messages()).
--}}
@props(['name', 'required' => false])

@php
    $hasError = $errors->has($name);
    $errorId = $name.'-error';
@endphp

<div class="auth-check-item">
    <label class="auth-check" for="{{ $name }}">
        <input
            id="{{ $name }}"
            type="checkbox"
            wire:model="{{ $name }}"
            @if ($required) required aria-required="true" @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
        />
        <span>{{ $slot }}</span>
    </label>
    @error($name)
        <span id="{{ $errorId }}" class="auth-field__error">{{ $message }}</span>
    @enderror
</div>
