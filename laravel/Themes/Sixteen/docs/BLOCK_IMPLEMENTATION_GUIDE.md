# Block Implementation Guide

> **TailwindCSS + Alpine.js + Multilingual Patterns**

## 🎯 Purpose

This guide defines the **correct patterns** for implementing blade blocks in the Sixteen theme.
It prevents common mistakes: Bootstrap Italia usage, hardcoded language strings, inconsistent architecture.

---

## 🚫 CRITICAL RULES (NEVER VIOLATE)

### 1. HTML Structural Parity è ESSENZIALE

La **parità HTML strutturale** è ESSENZIALE, NON secondaria. Replichiamo ESATTAMENTE la struttura HTML del reference (italia.github.io/design-comuni-pagine-statiche): stessi tag, stessi attributi, stessi nomi classe, stessi `data-element`, stessa gerarchia. Poi raggiungiamo la parità visiva/funzionale con TailwindCSS @apply e Alpine.js.

**Priorità:**
1. ✅ HTML Structural Parity (ESSENZIALE): Stessi tag, classi, gerarchia del reference
2. ✅ Visual Parity: TailwindCSS @apply replica lo styling
3. ✅ Functional Parity: Alpine.js replica il comportamento

```blade
{{-- ✅ CORRETTO: STESSA struttura HTML del reference —}}
<div id="rating" class="bg-primary" data-element="feedback">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="cmp-rating pt-5 pb-5">
                    <fieldset class="rating">
                        <input class="visually-hidden" type="radio" id="star5" name="rating" value="5">
                        <label class="full rating-star" for="star5" data-element="feedback-rate-5">
                            <svg class="icon icon-sm">...</svg>
                        </label>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ❌ SBAGLIATO: Inventare classi custom —}}
<div class="rating-block flex flex-col gap-4">  <!-- NON è nel reference -->
```

**Regole:**
- ✅ Copiare ESATTAMENTE classi Bootstrap dal reference
- ✅ Copiare ESATTAMENTE attributi `data-element` dal reference
- ✅ Stessa gerarchia tag del reference
- ✅ Tailwind @apply per styling: `style-apply.css`
- ✅ Alpine.js per comportamento: `x-data`, `@click`, `x-show`
- ❌ File Bootstrap CSS/JS: MAI
- ❌ Attributi `data-bs-*`: MAI
- ❌ Inventare classi custom: MAI

### 2. NO Hardcoded Language Strings

**The site is multilingual.** All text must come from translation files.

```blade
{{-- ❌ WRONG: Hardcoded Italian —}}
<h2>Quanto sono chiare le informazioni?</h2>
<button>Invia</button>
<p>Grazie per il tuo feedback!</p>

{{-- ✅ CORRECT: Translation helper —}}
@php
    $ns = 'fixcity::rating';
@endphp
<h2>{{ __($ns . '.fields.title.label') }}</h2>
<button>{{ __($ns . '.actions.submit.label') }}</button>
<p>{{ __($ns . '.messages.thank_you.text') }}</p>
```

### 3. Translation Namespace Pattern

**Format:** `namespace::context.collection.element.type` (5 livelli, SEMPRE!)

```
✅ fixcity::rating.fields.title.label
✅ fixcity::rating.fields.star.labels.5
✅ fixcity::rating.actions.submit.label
✅ fixcity::rating.messages.thank_you.text
✅ fixcity::segnalazione.heading.title.label

❌ fixcity::rating.title (manca .collection.)
❌ fixcity::rating.star.labels.5 (manca .collection.)
❌ SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE (namespace uppercase)
❌ fixcity::segnalazione.heading.title_label (underscore invece di dot)
```

**Translation files location:**
- `laravel/Modules/Fixcity/lang/it/rating.php`
- `laravel/Modules/Fixcity/lang/en/rating.php`

**Structure example:**
```php
return [
    'rating' => [         // context
        'fields' => [     // collection
            'title' => [  // element
                'label' => 'Titolo',  // type
            ],
            'star' => [
                'labels' => [
                    5 => 'Valuta 5 stelle su 5',
                ],
            ],
        ],
        'actions' => [    // collection
            'submit' => [ // element
                'label' => 'Invia',  // type
            ],
        ],
    ],
];
```

---

## ✅ CORRECT PATTERNS

### Block Structure

```blade
{{--
    Block Name - Short description
    Usage: <x-pub_theme::components.blocks.feedback.rating :data="$blockData" />
    Tech: TailwindCSS + Alpine.js (NO Bootstrap)
    Multilingual: ALL text from translations
--}}
@props(['data' => []])

@php
    $ns = 'fixcity::blockname';
    $title = $data['title'] ?? __($ns . '.title');
@endphp

<div x-data="{ /* Alpine state */ }">
    {{-- Block content with Tailwind classes --}}
</div>
```

### Alpine.js Patterns

**Star Rating:**
```blade
<div x-data="{ rating: 0, hover: 0 }">
    @for ($star = 5; $star >= 1; $star--)
    <input type="radio" id="star{{ $star }}" x-model="rating" class="sr-only">
    <label for="star{{ $star }}"
           :class="(hover >= {{ $star }} || rating >= {{ $star }}) ? 'text-yellow-400' : 'text-gray-300'"
           @click="rating = {{ $star }}"
           @mouseenter="hover = {{ $star }}"
           @mouseleave="hover = 0">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">...</svg>
    </label>
    @endfor
</div>
```

**Multi-step Wizard:**
```blade
<div x-data="{ step: 1 }">
    <div x-show="step === 1" x-transition>Step 1</div>
    <div x-show="step === 2" x-cloak x-transition>Step 2</div>
    <button @click="step = 2">Next</button>
</div>
```

**Accordion:**
```blade
<div x-data="{ activeIndex: null }">
    @foreach($items as $index => $item)
    <button @click="activeIndex === {{ $index }} ? activeIndex = null : activeIndex = {{ $index }}"
            :aria-expanded="activeIndex === {{ $index }}">
        {{ $item['question'] }}
    </button>
    <div x-show="activeIndex === {{ $index }}" x-cloak>
        {{ $item['answer'] }}
    </div>
    @endforeach
</div>
```

**Dismissible Alert:**
```blade
<div x-data="{ show: true }"
     x-show="show"
     x-transition:leave="transition ease-in duration-200">
    <button @click="show = false">Close</button>
    {{ $message }}
</div>
```

### TailwindCSS Layout Patterns

**Centered Container:**
```blade
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    {{ $content }}
</div>
```

**Card:**
```blade
<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-5 sm:px-8 border-b border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-900">{{ $title }}</h2>
    </div>
    <div class="px-6 py-6 sm:px-8">
        {{ $content }}
    </div>
</div>
```

**Button Primary:**
```blade
<button class="inline-flex items-center justify-center px-6 py-2 border border-transparent
               text-sm font-medium rounded-md shadow-sm text-white
               bg-primary-500 hover:bg-primary-600
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
    {{ $label }}
</button>
```

**Button Secondary/Outline:**
```blade
<button class="inline-flex items-center justify-center px-4 py-2 border border-gray-300
               shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white
               hover:bg-gray-50
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
    {{ $label }}
</button>
```

**Radio Group:**
```blade
<div class="space-y-3">
    @foreach($options as $index => $option)
    <div class="flex items-start">
        <input type="radio" id="opt-{{ $index }}"
               class="mt-1 h-4 w-4 border-gray-300 text-primary-500 focus:ring-primary-500">
        <label for="opt-{{ $index }}" class="ml-3 text-base text-gray-700 cursor-pointer">
            {{ $option }}
        </label>
    </div>
    @endforeach
</div>
```

**Text Input:**
```blade
<label for="input-id" class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
<textarea id="input-id"
          class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
          rows="3"></textarea>
<p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
```

### Icon Patterns

**Filament Icons (PREFERRED):**
```blade
<x-filament::icon icon="heroicon-o-check-circle" class="h-6 w-6 text-green-500" />
<x-filament::icon icon="heroicon-o-x-mark" class="h-4 w-4" />
```

**Dynamic Icons:**
```blade
<x-dynamic-component :component="$iconComponent" class="h-5 w-5" />
```

---

## 📐 Rating Block Example (REFERENCE IMPLEMENTATION)

See: `resources/views/components/blocks/feedback/rating.blade.php`

**Key patterns demonstrated:**
1. Translation helper function `$t()`
2. Star rating with Alpine.js interactivity
3. Multi-step wizard (step 1 → 2 → 3)
4. Positive/negative feedback branching
5. TailwindCSS layout (NO Bootstrap)
6. Full accessibility (sr-only, role, aria-*)

---

## 🔄 Multi-Agent Collaboration Rules

1. **Study existing code first** - Check `docs/` indexes, read working blocks
2. **Don't override other agents' work** - Check git log for recent changes
3. **Small, focused commits** - One block at a time
4. **Document everything** - Update this guide, add to indexes
5. **Cross-reference** - Link to related docs (min 3 bidirectional links)

---

## 📚 Related Documentation

- [Blocks Implementation Status](./BLOCKS_IMPLEMENTATION.md)
- [Design Comuni Block Analysis](./design-comuni/BLOCK_ANALYSIS.md)
- [Layout Architecture](./architecture/layout-architecture.md)
- [Alpine.js Components](./ALPINE-JS-COMPONENTS.md)
- [Translation Management](../../Modules/Lang/docs/translation-guide.md)

---

## ✅ Block Checklist

```markdown
## Before Creating
- [ ] Studied existing working blocks (accordion, alert, card)
- [ ] Checked translation namespace doesn't conflict
- [ ] Reviewed this guide for correct patterns

## Implementation
- [ ] @props(['data' => []]) defined
- [ ] Translation namespace set ($ns = 'fixcity::blockname')
- [ ] ALL text uses __() helper
- [ ] TailwindCSS classes only (NO Bootstrap)
- [ ] Alpine.js for interactivity (x-data, @click, x-show)
- [ ] Accessibility: sr-only, aria-*, role attributes
- [ ] Responsive: sm:, md:, lg: breakpoints

## Translations
- [ ] Created lang/it/blockname.php
- [ ] Created lang/en/blockname.php
- [ ] All keys follow namespace::context.element.type pattern
- [ ] No hardcoded strings in blade

## Documentation
- [ ] Added comment block at top of blade
- [ ] Updated BLOCKS_IMPLEMENTATION.md
- [ ] Linked to this guide
- [ ] Added to docs index

## Quality
- [ ] Tested in browser (all languages)
- [ ] No PHPStan errors
- [ ] Pint formatted
```

---

**Version:** 1.0
**Last Updated:** 2026-04-08
**Status:** ✅ Active reference
