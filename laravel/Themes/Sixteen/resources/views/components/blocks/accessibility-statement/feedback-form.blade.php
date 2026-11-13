{{--
    Modulo di feedback sull'accessibilita' (vista del componente Livewire Modules\Fixcity\Livewire\AccessibilityFeedbackForm, DCP-132).
    Una sola radice (requisito di Livewire). Esito in un'area `role="status"` sempre presente; errori di campo collegati con aria-describedby.
    Senza casella indicata dall'ente il modulo non viene mostrato e resta il messaggio `inactive`.
--}}
<div>
    @if ($isAvailable)
        <form wire:submit="submit" novalidate x-data x-on:accessibility-feedback-invalid.window="setTimeout(() => $el.querySelector('[aria-invalid=true]')?.focus(), 50)">
            <h3 class="h5">{{ __('fixcity::accessibility.form.title') }}</h3>
            @foreach ([
                'problem' => ['kind' => 'textarea', 'label' => 'problem', 'hint' => 'problem_hint', 'required' => true],
                'page' => ['kind' => 'text', 'label' => 'page', 'hint' => null, 'required' => false],
                'contact' => ['kind' => 'email', 'label' => 'contact', 'hint' => 'contact_hint', 'required' => false],
            ] as $field => $spec)
                <div class="form-group cmp-input mb-4">
                    <label for="a11y-{{ $field }}" class="d-block">{{ __('fixcity::accessibility.form.'.$spec['label']) }}</label>
                    @php($describedBy = trim(($spec['hint'] ? 'a11y-'.$field.'-hint ' : '').($errors->has($field) ? 'a11y-'.$field.'-error' : '')))
                    @if ($spec['kind'] === 'textarea')
                        <textarea id="a11y-{{ $field }}" wire:model="{{ $field }}" rows="5" maxlength="3000" class="text-area form-control{{ $errors->has($field) ? ' is-invalid' : '' }}"@if ($spec['required']) required @endif @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif @if ($errors->has($field)) aria-invalid="true" @endif></textarea>
                    @else
                        <input id="a11y-{{ $field }}" wire:model="{{ $field }}" type="{{ $spec['kind'] }}" maxlength="255" class="form-control{{ $errors->has($field) ? ' is-invalid' : '' }}" @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif @if ($errors->has($field)) aria-invalid="true" @endif>
                    @endif
                    @if ($spec['hint'])
                        <span class="form-text" id="a11y-{{ $field }}-hint">{{ __('fixcity::accessibility.form.'.$spec['hint']) }}</span>
                    @endif
                    @error($field)
                        <div class="invalid-feedback d-block" id="a11y-{{ $field }}-error">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">{{ __('fixcity::accessibility.form.submit') }}</button>
        </form>
    @else
        <p>{{ __('fixcity::accessibility.form.inactive') }}</p>
    @endif
    <div role="status" class="mt-3" data-element="accessibility-feedback-status">
        @if ($outcome === 'sent')
            <div class="alert alert-success" data-element="accessibility-feedback-sent">{{ __('fixcity::accessibility.form.sent') }}</div>
        @elseif ($outcome === 'throttled' || $outcome === 'unavailable')
            <div class="callout callout-highlight ps-3 warning"><p class="mb-0">{{ __('fixcity::accessibility.form.'.$outcome) }}</p></div>
        @endif
    </div>
</div>
