# Filosofia JSON-Driven — La Religione Laraxot

**Status**: Active  
**Created**: 2026-04-12  
**Last Updated**: 2026-04-12  
**Category**: Architecture / Philosophy / CMS

## Lo Zen del CMS

### La Regola Sacra

> **I blocchi si definiscono nel JSON, NON si chiamano hardcoded nelle blade.**

### Perche (La Visione)

In Laraxot, la separazione tra **contenuto** e **rendering** e assoluta:

| Livello | Dove | Cosa |
|---------|------|------|
| **Contenuto** | JSON del CMS | Quali blocchi, in che ordine, con quali dati |
| **Rendering** | Blade view | COME si renderizza un singolo blocco |
| **Stile** | CSS | COME appare visivamente |

### Lo Scopo

1. **Componibilita**: Gli stessi blocchi si riusano su pagine diverse
2. **Modificabilita**: Cambiare ordine/presenza blocchi senza toccare codice
3. **Testabilita**: Il JSON e testabile indipendentemente dalle view
4. **Multi-lingua**: Ogni lingua ha i suoi blocchi nel JSON
5. **Editoriale**: I content manager possono comporre pagine senza sviluppatori

### La Politica

**✅ CORRETTO — Blocco nel JSON:**
```json
{
    "type": "contacts-card",
    "data": {
        "view": "pub_theme::components.blocks.design-comuni.contacts-card",
        "contacts": {
            "faq": "#",
            "phone": "05 0505"
        }
    }
}
```

**❌ SBAGLIATO — Hardcoded nella blade:**
```blade
{{-- NON FARE MAI QUESTO --}}
<x-pub_theme::blocks.design-comuni.contacts-card :contacts="$contacts" />
```

### La Religione

```
JSON (contenuto) → CMS render → Blade view (rendering) → CSS (stile)
```

Ogni livello ha una responsabilita esclusiva:
- **JSON**: COSA mostrare
- **Blade**: COME renderizzare
- **CSS**: COME apparire

### Il KISS

La blade del widget deve essere **minimale**:
```blade
<div class="ticket-wizard-root">
    <x-filament-widgets::widget>
        <form wire:submit="submit">
            {{ $this->form }}
        </form>
        <x-filament-actions::modals />
    </x-filament-widgets::widget>
</div>
```

Tutto il resto (titolo, contatti, breadcrumb) va nel JSON.

### Il DRY

Un blocco contacts-card si scrive UNA volta:
- `themes/Sixteen/resources/views/components/blocks/design-comuni/contacts-card.blade.php`

E si usa DOVE serve:
- Nel JSON di `segnalazione-crea`
- Nel JSON di `segnalazione-01-privacy`
- Nel JSON di qualsiasi altra pagina

### Esempio Pratico

**Prima (SBAGLIATO):**
```blade
{{-- ticket-create-wizard.blade.php — 60 righe di HTML hardcoded —}}
<div class="container">
    <h1>{{ $pageTitle }}</h1>
    {{ $this->form }}
    <div class="contacts-section">
        <h2>Contatta il comune</h2>
        <!-- 30 righe di HTML contatti -->
    </div>
</div>
```

**Dopo (CORRETTO):**
```blade
{{-- ticket-create-wizard.blade.php — 8 righe —}}
<div class="ticket-wizard-root">
    <x-filament-widgets::widget>
        <form wire:submit="submit">{{ $this->form }}</form>
        <x-filament-actions::modals />
    </x-filament-widgets::widget>
</div>
```

```json
// tests.segnalazione-crea.json
{
    "content_blocks": {
        "it": [
            { "type": "breadcrumb", "data": { ... } },
            { "type": "segnalazione-crea", "data": { ... } },
            { "type": "contacts-card", "data": { ... } }
        ]
    }
}
```

## Riferimenti

- [Contacts Card Component](../../../../Themes/Sixteen/resources/views/components/blocks/design-comuni/contacts-card.blade.php)
- [JSON pagina segnalazione-crea](../../../../../config/local/fixcity/database/content/pages/tests.segnalazione-crea.json)
- [TICKET-CREATION-WIZARD.md](../../../../Themes/Sixteen/docs/design-comuni/TICKET-CREATION-WIZARD.md)
- [Story 7-30](../../../../_bmad-output/implementation-artifacts/7-30-refactor-ticket-wizard-to-filament-pure.md)
