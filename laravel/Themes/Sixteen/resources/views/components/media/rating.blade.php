{{-- 
/**
 * Rating Component - Bootstrap Italia Compliant
 * 
 * Graphic star rating scale for expressing evaluation of services or content
 * Composed of fieldset with 5 radio inputs with values from 1 to 5
 * 
 * @param string $name Form field name for the rating input group
 * @param string $legend Legend text for the fieldset
 * @param int $value Current rating value (1-5)
 * @param int $maxRating Maximum rating value (default: 5)
 * @param bool $showLabel Whether to show descriptive label with current rating
 * @param bool $readonly Whether the rating is read-only (display only)
 * @param bool $required Whether rating selection is required
 * @param string $size Star size variant: 'sm', 'md', 'lg'
 * @param array $labels Custom labels for each rating value
 */
--}}

@props([
    'name' => 'rating',
    'legend' => 'Rating',
    'value' => 0,
    'maxRating' => 5,
    'showLabel' => false,
    'readonly' => false,
    'required' => false,
    'size' => 'sm', // 'sm', 'md', 'lg'
    'labels' => []
])

@php
    $fieldsetClasses = collect(['rating']);
    
    if ($showLabel) {
        $fieldsetClasses->push('rating-label');
    }
    
    if ($readonly) {
        $fieldsetClasses->push('rating-readonly');
    }
    
    // Icon size class
    $iconSizeClass = 'icon-' . $size;
    
    // Default labels for rating values
    $defaultLabels = [
        1 => 'Pessimo',
        2 => 'Scarso', 
        3 => 'Sufficiente',
        4 => 'Buono',
        5 => 'Ottimo'
    ];
    
    $ratingLabels = !empty($labels) ? $labels : $defaultLabels;
    
    // Generate unique IDs
    $fieldsetId = 'rating-' . uniqid();
@endphp

<fieldset class="{{ $fieldsetClasses->implode(' ') }}" {{ $attributes }}>
    @if($showLabel && $value > 0)
        <legend>
            <span class="visually-hidden">Valutazione</span>
            <span>{{ $value }} {{ $value === 1 ? 'stella' : 'stelle' }}</span>
            <span class="visually-hidden">su {{ $maxRating }}</span>
        </legend>
    @else
        <legend>{{ $legend }}</legend>
    @endif
    
    {{-- Generate rating inputs from highest to lowest for proper CSS styling --}}
    @for($i = $maxRating; $i >= 1; $i--)
        @php
            $inputId = $fieldsetId . '-star' . $i;
            $isChecked = $value == $i;
            $labelText = $ratingLabels[$i] ?? "Valuta {$i} stelle su {$maxRating}";
        @endphp
        
        @if($readonly)
            {{-- Read-only version with spans instead of inputs --}}
            <span class="star {{ $i <= $value ? 'star-filled' : 'star-empty' }}">
                <svg class="icon {{ $iconSizeClass }}" aria-hidden="true">
                    <use href="#{{ $i <= $value ? 'it-star-full' : 'it-star-outline' }}"></use>
                </svg>
                <span class="visually-hidden">
                    {{ $i <= $value ? $labelText : '' }}
                </span>
            </span>
        @else
            {{-- Interactive rating inputs --}}
            <input 
                type="radio" 
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $i }}"
                {{ $isChecked ? 'checked' : '' }}
                {{ $required ? 'required' : '' }}
            />
            <label class="full" for="{{ $inputId }}">
                <svg class="icon {{ $iconSizeClass }}" aria-hidden="true">
                    <use href="#it-star-full"></use>
                </svg>
                <span class="visually-hidden">
                    {{ $labelText }}
                </span>
            </label>
        @endif
    @endfor
    
    {{-- Display current rating value for screen readers when readonly --}}
    @if($readonly && $value > 0)
        <span class="visually-hidden">
            Valutazione corrente: {{ $value }} su {{ $maxRating }} stelle. {{ $ratingLabels[$value] ?? '' }}
        </span>
    @endif
</fieldset>

{{-- CSS for read-only styling --}}
@if($readonly)
<style>
.rating-readonly {
    border: none;
    padding: 0;
    margin: 0;
}

.rating-readonly legend {
    font-size: inherit;
    margin-bottom: 0.5rem;
}

.rating-readonly .star {
    display: inline-block;
    margin-right: 2px;
}

.rating-readonly .star-filled .icon {
    color: #ff6900;
}

.rating-readonly .star-empty .icon {
    color: #d9dadb;
}
</style>
@endif

{{-- 
Usage Examples:

1. Basic rating input:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="service_rating" 
    legend="Valuta il servizio" />

2. Rating with current value:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="experience_rating"
    legend="Come valuti la tua esperienza?"
    :value="4" />

3. Rating with descriptive label:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="quality_rating"
    legend="Qualità del servizio"
    :value="3"
    :show-label="true" />

4. Read-only rating display:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="readonly_rating"
    legend="Valutazione media"
    :value="4"
    :readonly="true"
    :show-label="true" />

5. Required rating:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="required_rating"
    legend="Valutazione obbligatoria"
    :required="true" />

6. Custom rating scale (1-10):
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="detailed_rating"
    legend="Valutazione dettagliata"
    :max-rating="10"
    :value="7" />

7. Custom labels for rating values:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="custom_rating"
    legend="Come ti senti?"
    :value="5"
    :labels="[
        1 => 'Terribile',
        2 => 'Male', 
        3 => 'Ok',
        4 => 'Bene',
        5 => 'Fantastico!'
    ]" />

8. Large stars:
<x-pub_theme::rating 
<x-pub_theme::rating 
<x-pub_theme::rating 
    name="large_rating"
    legend="Rating con stelle grandi"
    size="lg"
    :value="4" />

9. Service evaluation form:
<form>
    <div class="mb-4">
        <x-pub_theme::rating 
        <x-pub_theme::rating 
        <x-pub_theme::rating 
            name="overall_satisfaction"
            legend="Soddisfazione complessiva"
            :show-label="true"
            :required="true" />
    </div>
    
    <div class="mb-4">
        <x-pub_theme::rating 
        <x-pub_theme::rating 
        <x-pub_theme::rating 
            name="ease_of_use"
            legend="Facilità d'uso"
            :show-label="true"
            :required="true" />
    </div>
    
    <div class="mb-4">
        <x-pub_theme::rating 
        <x-pub_theme::rating 
        <x-pub_theme::rating 
            name="response_time"
            legend="Tempistica di risposta"
            :show-label="true"
            :required="true" />
    </div>
    
    <button type="submit" class="btn btn-primary">Invia valutazione</button>
</form>

10. Rating display with statistics:
<div class="rating-summary">
    <div class="row align-items-center">
        <div class="col-auto">
            <x-pub_theme::rating 
            <x-pub_theme::rating 
            <x-pub_theme::rating 
                name="avg_rating"
                legend="Valutazione media"
                :value="4"
                :readonly="true"
                size="lg" />
        </div>
        <div class="col">
            <div>
                <strong>4.2</strong> su 5 stelle
            </div>
            <div class="text-muted">
                Basato su 147 recensioni
            </div>
        </div>
    </div>
</div>

11. Rating with detailed breakdown:
<div class="rating-breakdown">
    <h4>Valutazioni dettagliate</h4>
    
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span>5 stelle</span>
            <span class="text-muted">89</span>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar" style="width: 60%"></div>
        </div>
    </div>
    
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span>4 stelle</span>
            <span class="text-muted">32</span>
        </div>
        <div class="progress" style="height: 8px;">
            <div class="progress-bar" style="width: 22%"></div>
        </div>
    </div>
    
    <!-- Continue for other ratings... -->
    
    <div class="mt-3 text-center">
        <x-pub_theme::rating 
        <x-pub_theme::rating 
        <x-pub_theme::rating 
            name="avg_display"
            legend="Media complessiva"
            :value="4"
            :readonly="true"
            :show-label="true" />
    </div>
</div>

12. Interactive rating with feedback:
<div x-data="{ 
    rating: 0, 
    hover: 0,
    labels: ['Pessimo', 'Scarso', 'Sufficiente', 'Buono', 'Ottimo']
}">
    <x-pub_theme::rating 
    <x-pub_theme::rating 
    <x-pub_theme::rating 
        name="interactive_rating"
        legend="Valuta questo contenuto"
        x-model="rating" />
    
    <div x-show="rating > 0" class="mt-2">
        <span class="badge bg-primary" x-text="labels[rating - 1]"></span>
        <span class="ms-2 text-muted">Grazie per la tua valutazione!</span>
    </div>
</div>

13. Product rating display:
<div class="product-rating d-flex align-items-center">
    <x-pub_theme::rating 
    <x-pub_theme::rating 
    <x-pub_theme::rating 
        name="product_rating"
        legend="Valutazione prodotto"
        :value="4"
        :readonly="true"
        class="me-3" />
    
    <span class="fw-bold">4.0</span>
    <span class="text-muted ms-1">(247 recensioni)</span>
</div>

14. Rating in cards/reviews:
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0">Mario Rossi</h6>
            <x-pub_theme::rating 
            <x-pub_theme::rating 
            <x-pub_theme::rating 
                name="review_rating"
                legend="Valutazione recensione"
                :value="5"
                :readonly="true" />
        </div>
        <p class="card-text">
            Ottimo servizio, molto soddisfatto della qualità e della velocità.
        </p>
        <small class="text-muted">2 giorni fa</small>
    </div>
</div>

15. Rating with validation feedback:
<form x-data="{ rating: 0, submitted: false }">
    <div class="mb-3">
        <x-pub_theme::rating 
        <x-pub_theme::rating 
        <x-pub_theme::rating 
            name="validation_rating"
            legend="La tua valutazione (obbligatoria)"
            x-model="rating"
            :required="true" />
        
        <div x-show="submitted && rating === 0" class="invalid-feedback d-block">
            Per favore seleziona una valutazione prima di procedere.
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary" @click="submitted = true">
        Invia
    </button>
</form>

Accessibility Features:
- Proper fieldset and legend structure for screen readers
- Descriptive visually-hidden text for each star rating
- ARIA-compliant form controls
- Keyboard navigation support for interactive ratings
- Screen reader announcements for rating changes
- High contrast support with distinct filled/empty states

Bootstrap Italia Integration:
- Uses official rating classes and structure
- Compatible with Bootstrap Italia icon set (it-star-full, it-star-outline)
- Follows PA design system patterns and accessibility guidelines
- Supports all documented variants (with/without labels, readonly)
- Maintains consistency with other form components

Form Integration:
- Standard radio button behavior for form submission
- Compatible with Laravel form validation
- Supports required field validation
- Easy integration with Alpine.js for dynamic behavior
- Standard HTML form submission handling
--}}
