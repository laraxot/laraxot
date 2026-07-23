---
title: "Filament Custom Field — State Binding Modifiers: confine tema/modulo"
type: concept
confidence: verified
created: 2026-04-28
updated: 2026-04-28
tags: [filament5, custom-field, entangle, state-binding, livewire, alpine, blade, theme-boundary]
source: "https://filamentphp.com/docs/5.x/forms/custom-fields"
canonical: "Modules/Geo/docs/wiki/concepts/filament5-custom-field-entangle-contract.md"
---

# Filament Custom Field — State Binding Modifiers: confine tema/modulo

## La domanda ricorrente

> "Perché il CoordinatePicker usa quella sintassi 'complicata' per l'entangle invece di `$wire.$entangle('{{ $getStatePath() }}')`?"

## Risposta breve

`$applyStateBindingModifiers()` è il **contratto obbligatorio** di Filament 5 per i custom field
riutilizzabili. Non è una complicazione: è la forma che rispetta `->live()`, `->defer()`,
`->live(debounce: '500ms')` impostati in PHP dal developer che usa il field.

La forma semplice (`$wire.$entangle('...')`) **non esiste nei componenti Filament ufficiali** — è
solo un esempio introduttivo della documentazione, non la forma raccomandata per field reali.

## Prova dal codice sorgente Filament 5

Ogni componente ufficiale usa `$applyStateBindingModifiers`, mai il bare `$wire.$entangle`:

| Componente | Sintassi nel vendor |
|------------|---------------------|
| `key-value` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}` |
| `tags-input` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}` |
| `select` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}` |
| `date-time-picker` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}` |
| `file-upload` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}` |
| `rich-editor` | `$wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }}` |
| `toggle` | `'$wire.' . $applyStateBindingModifiers('$entangle(\'' . $statePath . '\')')` |

**Nessun componente Filament ufficiale usa** `$wire.$entangle('{{ $getStatePath() }}')` senza wrapper.

## Come funziona la trasformazione

`$applyStateBindingModifiers()` trasforma `$entangle(...)` in base ai modificatori PHP del field:

| Chiamata PHP nel form schema | Output JavaScript generato |
|------------------------------|----------------------------|
| `CoordinatePicker::make('location')` | `$wire.$entangle('location')` |
| `->live()` | `$wire.$entangle('location').live` |
| `->live(onBlur: true)` | `$wire.$entangle('location').blur` |
| `->live(debounce: '500ms')` | `$wire.$entangle('location').debounce.500ms` |
| `->defer()` | `$wire.$entangle('location')` (deferral esplicito) |

Senza `$applyStateBindingModifiers()`, il field **ignora sempre** questi modificatori — il campo
non sarebbe reattivo anche quando il developer specifica `->live()`.

## Cosa significa per il tema Sixteen

Il tema possiede la **parity visuale** del field, non il **protocollo Livewire/Filament**
di sincronizzazione dello stato.

### Il tema PUÒ

- Stilizzare `.coordinate-picker-field-wrapper`, la mappa, il readout, i controlli
- Sovrascrivere dimensioni e colori via Tailwind/CSS

### Il tema NON DEVE

- Riscrivere o "semplificare" il binding state del field
- Sostituire `$applyStateBindingModifiers(...)` con `$wire.$entangle('...')`
- Modificare la sintassi `x-data` del componente Alpine
- Cambiare il contratto `state` tra Blade e Lit Element

```blade
{{-- CORRETTO — contratto Filament, da non toccare nel tema --}}
state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},

{{-- VIETATO — degrada il binding, rompe live() e defer() --}}
state: $wire.$entangle('{{ $getStatePath() }}'),
```

## Sintassi corretta di riferimento

```blade
@php
/** @var \Modules\Geo\Filament\Forms\Components\CoordinatePicker $field */
$statePath = $getStatePath();
@endphp

<div x-data="{
    state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
}">
```

**Note tecniche**:
- Usare **doppi apici** in PHP: `"\$entangle('{$statePath}')"`
- L'echo Blade `{{ }}` avvolge l'intera chiamata a `$applyStateBindingModifiers()`
- `$statePath` è pre-calcolato nel blocco `@php` per DRY

## Checklist veloce (per review tema)

- Se trovi `state: $wire.$entangle('{{ $getStatePath() }}')` → **downgrade**: mancano i modifier
- Se trovi `callSchemaComponentMethod('some-id', ...)` senza `@js($getKey())` → **rischio**: usare sempre la component key

## Best practices (tema)

- Curare solo layer visuale/UX del picker: spacing, colori, responsive, accessibilita.
- Trattare il binding state come contratto modulo owner (`Geo`) e non come dettaglio stilistico.
- Allineare ogni fix tema alla pagina canonica del modulo prima di proporre override.

## Bad practices (tema)

- Modificare `x-data` del custom field per "semplificare" entangle.
- Introdurre fork locale del field logic dentro il tema.
- Legare chiamate schema component a id HTML o selettori fragili.

## False friends (tema)

- "E nel tema, quindi posso toccare anche la sincronizzazione Livewire": falso, quello e boundary modulo owner.
- "Il fix visuale richiede riscrivere il binding": falso, i due layer vanno separati.
- "Funziona in una route frontoffice, quindi e valido in admin": falso, admin e frontoffice hanno runtime diversi.

## Riferimenti

- **Documento canonico (modulo)**: `Modules/Geo/docs/wiki/concepts/filament5-custom-field-entangle-contract.md`
- **Regola permanente**: `bashscripts/ai/.claude/rules/coordinate-picker-state-binding-rule.md`
- **Filament 5 docs**: [Custom Fields — Obeying state binding modifiers](https://filamentphp.com/docs/5.x/forms/custom-fields)
- **Filament forms overview**: [Forms overview](https://filamentphp.com/docs/5.x/forms/overview)
- **Livewire + Alpine**: [Livewire Alpine integration](https://livewire.laravel.com/docs/alpine)
