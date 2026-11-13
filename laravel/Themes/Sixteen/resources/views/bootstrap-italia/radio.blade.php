{{-- Bootstrap Italia Radio Component --}}

<fieldset class="radio-group radio-italia">
    @if(isset($legend))
    <legend class="radio-legend">{{ $legend }}</legend>
    @endif
    @foreach($radios ?? [] as $radio)
    <div class="form-check form-check-radio">
        <input class="form-check-input"
               type="radio"
               name="{{ $name }}"
               id="{{ $radio['id'] }}"
               value="{{ $radio['value'] }}"
               @if($radio['checked'] ?? false) checked @endif>
        <label class="form-check-label" for="{{ $radio['id'] }}">
            {{ $radio['label'] }}
        </label>
    </div>
    @endforeach
</fieldset>
