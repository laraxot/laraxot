{{-- 
/**
 * Toggle Component - Bootstrap Italia Compliant
 * 
 * Switch-type "lever" fields for boolean selections
 * Uses .toggles container with .lever span for styling
 * 
 * @param string $id Unique ID for the toggle input
 * @param string $name Form field name
 * @param string $label Toggle label text
 * @param bool $checked Whether the toggle is checked
 * @param bool $disabled Whether the toggle is disabled
 * @param bool $inline Whether to render inline (with form-check-inline)
 * @param bool $leverRight Whether to use leverRight class for alternative styling
 * @param string $value Toggle value when checked (default: '1')
 * @param array $toggles Array of toggle options for grouped toggles
 * @param string $legend Legend for grouped toggles fieldset
 */
--}}

@props([
    'id' => 'toggle-' . uniqid(),
    'name' => '',
    'label' => '',
    'checked' => false,
    'disabled' => false,
    'inline' => false,
    'leverRight' => false,
    'value' => '1',
    'toggles' => [],
    'legend' => ''
])

@php
    $formCheckClasses = collect(['form-check']);
    if ($inline) {
        $formCheckClasses->push('form-check-inline');
    }
    $formCheckClass = $formCheckClasses->implode(' ');
    
    $leverClasses = collect(['lever']);
    if ($leverRight) {
        $leverClasses->push('leverRight');
    }
    $leverClass = $leverClasses->implode(' ');
@endphp

@if(!empty($toggles) && $legend)
    {{-- Grouped toggles with fieldset --}}
    <fieldset>
        <legend>{{ $legend }}</legend>
        
        @foreach($toggles as $toggle)
            @php
                $toggleId = $toggle['id'] ?? 'toggle-' . uniqid();
                $toggleName = $toggle['name'] ?? $name;
                $toggleLabel = $toggle['label'] ?? '';
                $toggleChecked = $toggle['checked'] ?? false;
                $toggleDisabled = $toggle['disabled'] ?? false;
                $toggleValue = $toggle['value'] ?? '1';
                $toggleLeverRight = $toggle['leverRight'] ?? false;
                
                $toggleLeverClasses = collect(['lever']);
                if ($toggleLeverRight) {
                    $toggleLeverClasses->push('leverRight');
                }
                $toggleLeverClass = $toggleLeverClasses->implode(' ');
            @endphp
            
            <div class="{{ $formCheckClass }}">
                <div class="toggles">
                    <label for="{{ $toggleId }}">
                        {{ $toggleLabel }}
                        <input 
                            type="checkbox" 
                            id="{{ $toggleId }}"
                            name="{{ $toggleName }}"
                            value="{{ $toggleValue }}"
                            {{ $toggleChecked ? 'checked' : '' }}
                            {{ $toggleDisabled ? 'disabled' : '' }}
                        >
                        <span class="{{ $toggleLeverClass }}"></span>
                    </label>
                </div>
            </div>
        @endforeach
    </fieldset>
@elseif(!empty($toggles))
    {{-- Simple grouped toggles without fieldset --}}
    @foreach($toggles as $toggle)
        @php
            $toggleId = $toggle['id'] ?? 'toggle-' . uniqid();
            $toggleName = $toggle['name'] ?? $name;
            $toggleLabel = $toggle['label'] ?? '';
            $toggleChecked = $toggle['checked'] ?? false;
            $toggleDisabled = $toggle['disabled'] ?? false;
            $toggleValue = $toggle['value'] ?? '1';
            $toggleLeverRight = $toggle['leverRight'] ?? false;
            
            $toggleLeverClasses = collect(['lever']);
            if ($toggleLeverRight) {
                $toggleLeverClasses->push('leverRight');
            }
            $toggleLeverClass = $toggleLeverClasses->implode(' ');
        @endphp
        
        <div class="{{ $formCheckClass }}">
            <div class="toggles">
                <label for="{{ $toggleId }}">
                    {{ $toggleLabel }}
                    <input 
                        type="checkbox" 
                        id="{{ $toggleId }}"
                        name="{{ $toggleName }}"
                        value="{{ $toggleValue }}"
                        {{ $toggleChecked ? 'checked' : '' }}
                        {{ $toggleDisabled ? 'disabled' : '' }}
                    >
                    <span class="{{ $toggleLeverClass }}"></span>
                </label>
            </div>
        </div>
    @endforeach
@else
    {{-- Single toggle --}}
    <div class="{{ $formCheckClass }}">
        <div class="toggles">
            <label for="{{ $id }}">
                {{ $label }}
                <input 
                    type="checkbox" 
                    id="{{ $id }}"
                    name="{{ $name ?: $id }}"
                    value="{{ $value }}"
                    {{ $checked ? 'checked' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $attributes }}
                >
                <span class="{{ $leverClass }}"></span>
            </label>
            {{ $slot }}
        </div>
    </div>
@endif

{{-- 
Usage Examples:

1. Basic toggle:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    id="toggle1"
    name="notifications"
    label="Abilita notifiche" />

2. Checked toggle:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    id="toggle2"
    name="auto_save"
    label="Salvataggio automatico"
    :checked="true" />

3. Disabled toggle:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    id="toggle3"
    name="premium_feature"
    label="Funzionalità premium"
    :disabled="true" />

4. Inline toggles:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    id="toggle4"
    name="setting1"
    label="Impostazione 1"
    :inline="true" />
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    id="toggle5"
    name="setting2"
    label="Impostazione 2"
    :inline="true"
    :lever-right="true" />

5. Grouped toggles with fieldset:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    legend="Gruppo di toggle"
    :inline="true"
    :toggles="[
        [
            'id' => 'toggle_option1',
            'name' => 'options[]',
            'label' => 'Opzione 1',
            'value' => 'option1',
            'checked' => false
        ],
        [
            'id' => 'toggle_option2',
            'name' => 'options[]', 
            'label' => 'Opzione 2',
            'value' => 'option2',
            'checked' => true,
            'leverRight' => true
        ]
    ]" />

6. Simple grouped toggles:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    :toggles="[
        ['id' => 'email_notifications', 'label' => 'Notifiche email', 'name' => 'notifications[email]'],
        ['id' => 'sms_notifications', 'label' => 'Notifiche SMS', 'name' => 'notifications[sms]']
    ]" />

7. Toggle with custom value:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    name="consent"
    label="Accetto i termini e condizioni"
    value="accepted"
    :required="true" />

8. Toggle with slot content:
<x-pub_theme::toggle 
<x-pub_theme::toggle 
<x-pub_theme::toggle 
    name="custom_toggle"
    label="Toggle personalizzato">
    <small class="form-text text-muted">
        Informazioni aggiuntive sul toggle
    </small>
</x-pub_theme::toggle>
</x-pub_theme::toggle>
</x-pub_theme::toggle>

Bootstrap Italia Classes Reference:
- .form-check: Container for form check elements
- .form-check-inline: Inline arrangement
- .toggles: Main container for toggle switch
- .lever: Toggle switch lever element
- .leverRight: Alternative lever positioning
--}}
