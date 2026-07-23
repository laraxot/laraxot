{{-- 
/**
 * Radio Button Component - Bootstrap Italia Compliant
 * 
 * Accessible radio button elements with proper form-check structure
 * Supports individual radio buttons or grouped radio sets with fieldset/legend
 * 
 * @param string $name Form field name for radio group
 * @param array $options Array of radio options [value => label] or [['value' => '', 'label' => '', 'checked' => false, 'disabled' => false]]
 * @param string $selected Currently selected value
 * @param string $legend Legend text for fieldset (when using options array)
 * @param bool $inline Whether to render radio buttons inline
 * @param bool $withGap Whether to add 'with-gap' class for alternative styling
 * @param bool $disabled Whether to disable all radio buttons
 * @param bool $required Whether the radio group is required
 * @param string $helpText Optional help text
 */
--}}

@props([
    'name' => 'radio-' . uniqid(),
    'options' => [],
    'selected' => '',
    'legend' => '',
    'inline' => false,
    'withGap' => false,
    'disabled' => false,
    'required' => false,
    'helpText' => ''
])

@php
    $baseClasses = collect(['form-check']);
    if ($inline) {
        $baseClasses->push('form-check-inline');
    }
    $baseClass = $baseClasses->implode(' ');
    
    $inputClasses = collect(['form-check-input']);
    if ($withGap) {
        $inputClasses->push('with-gap');
    }
    $inputClass = $inputClasses->implode(' ');
@endphp

@if(!empty($options) && $legend)
    {{-- Grouped radio buttons with fieldset --}}
    <fieldset>
        <legend>
            {{ $legend }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </legend>
        
        @foreach($options as $value => $option)
            @php
                if (is_array($option)) {
                    $optionValue = $option['value'];
                    $optionLabel = $option['label'];
                    $isChecked = $option['checked'] ?? ($optionValue == $selected);
                    $isDisabled = $option['disabled'] ?? $disabled;
                } else {
                    $optionValue = $value;
                    $optionLabel = $option;
                    $isChecked = ($optionValue == $selected);
                    $isDisabled = $disabled;
                }
                
                $radioId = $name . '_' . $optionValue;
            @endphp
            
            <div class="{{ $baseClass }}">
                <input 
                    name="{{ $name }}" 
                    type="radio" 
                    class="{{ $inputClass }}"
                    id="{{ $radioId }}"
                    value="{{ $optionValue }}"
                    {{ $isChecked ? 'checked' : '' }}
                    {{ $isDisabled ? 'disabled' : '' }}
                    {{ $required ? 'required' : '' }}
                    aria-describedby="{{ $helpText ? $name . '-help' : '' }}"
                >
                <label for="{{ $radioId }}" class="{{ $isDisabled ? 'disabled' : '' }}">
                    {{ $optionLabel }}
                </label>
            </div>
        @endforeach
        
        @if($helpText)
            <div id="{{ $name }}-help" class="form-text">
                {{ $helpText }}
            </div>
        @endif
    </fieldset>
@elseif(!empty($options))
    {{-- Simple radio group without fieldset --}}
    @foreach($options as $value => $option)
        @php
            if (is_array($option)) {
                $optionValue = $option['value'];
                $optionLabel = $option['label'];
                $isChecked = $option['checked'] ?? ($optionValue == $selected);
                $isDisabled = $option['disabled'] ?? $disabled;
            } else {
                $optionValue = $value;
                $optionLabel = $option;
                $isChecked = ($optionValue == $selected);
                $isDisabled = $disabled;
            }
            
            $radioId = $name . '_' . $optionValue;
        @endphp
        
        <div class="{{ $baseClass }}">
            <input 
                name="{{ $name }}" 
                type="radio" 
                class="{{ $inputClass }}"
                id="{{ $radioId }}"
                value="{{ $optionValue }}"
                {{ $isChecked ? 'checked' : '' }}
                {{ $isDisabled ? 'disabled' : '' }}
                {{ $required ? 'required' : '' }}
                aria-describedby="{{ $helpText ? $name . '-help' : '' }}"
            >
            <label for="{{ $radioId }}" class="{{ $isDisabled ? 'disabled' : '' }}">
                {{ $optionLabel }}
            </label>
        </div>
    @endforeach
    
    @if($helpText)
        <div id="{{ $name }}-help" class="form-text">
            {{ $helpText }}
        </div>
    @endif
@else
    {{-- Single radio button using slot --}}
    <div class="{{ $baseClass }}">
        {{ $slot }}
    </div>
@endif

{{-- 
Usage Examples:

1. Basic radio group with fieldset:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="gruppo1"
    legend="Gruppo di radio"
    :options="[
        'option1' => 'Radio di esempio 1',
        'option2' => 'Radio di esempio 2'
    ]"
    selected="option1" />

2. Inline radio buttons:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="gruppo2"
    legend="Gruppo di radio"
    :inline="true"
    :options="[
        'opt1' => 'Opzione 1',
        'opt2' => 'Opzione 2'
    ]"
    selected="opt1" />

3. Radio buttons with with-gap styling:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="gruppo3"
    legend="Gruppo di radio"
    :with-gap="true"
    :options="[
        'choice1' => 'Opzione 1',
        'choice2' => 'Opzione 2'
    ]" />

4. Disabled radio group:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="gruppo4"
    legend="Gruppo di radio"
    :disabled="true"
    :options="[
        'dis1' => 'Opzione 1 selezionato',
        'dis2' => 'Opzione 2 non selezionato'
    ]"
    selected="dis1" />

5. Advanced options array format:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="gruppo5"
    legend="Opzioni avanzate"
    :options="[
        ['value' => 'adv1', 'label' => 'Opzione 1', 'checked' => true],
        ['value' => 'adv2', 'label' => 'Opzione 2', 'disabled' => true],
        ['value' => 'adv3', 'label' => 'Opzione 3']
    ]" />

6. Required radio group with help text:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="required_group"
    legend="Campo obbligatorio"
    :required="true"
    help-text="Seleziona una delle opzioni disponibili"
    :options="['req1' => 'Prima opzione', 'req2' => 'Seconda opzione']" />

7. Simple radio group without fieldset:
<x-pub_theme::radio 
<x-pub_theme::radio 
<x-pub_theme::radio 
    name="simple_group"
    :options="['simple1' => 'Opzione semplice 1', 'simple2' => 'Opzione semplice 2']" />

8. Single radio button with slot (manual control):
<x-pub_theme::radio>
    <input name="custom_radio" type="radio" id="custom1" class="form-check-input">
    <label for="custom1">Radio personalizzato</label>
</x-pub_theme::radio>
<x-pub_theme::radio>
    <input name="custom_radio" type="radio" id="custom1" class="form-check-input">
    <label for="custom1">Radio personalizzato</label>
</x-pub_theme::radio>

Bootstrap Italia Classes Reference:
- .form-check: Container for radio button
- .form-check-inline: Inline arrangement of radio buttons
- .form-check-input: Input styling class
- .with-gap: Alternative radio button styling
- .disabled: Disabled label styling
- .form-text: Help text styling
- .text-danger: Required field indicator
--}}
