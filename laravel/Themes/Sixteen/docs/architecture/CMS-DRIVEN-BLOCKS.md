# CMS-Driven Blocks Philosophy

> **Date:** 2026-04-13
> **Related:** [CSS Scoping Rule](./CSS-SCOPING-RULE.md) · [HTML Parity](./HTML-PARITY-RULE.md) · [Page Routing](./PAGE_ROUTING_ARCHITECTURE.md)

## The Rule

**I blocchi riutilizzabili si definiscono nel JSON della pagina CMS, NON si hardcodano nelle blade.**

```php
// ✅ CORRECT — La blade itera blocchi dal JSON
@foreach($blocks as $block)
    @include($block->view, ['data' => $block->data])
@endforeach

// ❌ WRONG — Contatti hardcoded nella blade
<div class="bg-grey-card shadow-contacts">
    <x-bootstrap-italia.contacts-card :contacts="$contacts" />
</div>
```

## Perché (Visione / Filosofia / Zen)

### 1. Separazione Contenuto / Presentazione

La blade è **renderer**, non **decisore**. Non deve sapere quali blocchi mostrare.

| Livello | Responsabilità | Dove |
|---|---|---|
| CMS (JSON/DB) | **Quali** blocchi e in quale ordine | `Page.blocks` / `Page.content_blocks` |
| Blade | **Come** renderizzare ogni blocco | `views/components/blocks/` |
| CSS | **Come** appare ogni blocco | `segnalazione-parity.css` |

### 2. Composable Pages

Un editor CMS può:
- Aggiungere/rimuovere il contacts card senza toccare codice
- Cambiare l'ordine dei blocchi
- Aggiungere blocchi nuovi senza deploy

### 3. DRY + Single Source

Un solo componente Blade: `components/blocks/contacts/default.blade.php`
Configurato nel JSON della pagina:

```json
{
  "blocks": [
    {
      "type": "contacts",
      "view": "pub_theme::components.blocks.contacts.default",
      "data": {
        "heading": "Contatta il comune",
        "items": [
          {"type": "faq", "url": "/faq"},
          {"type": "assistenza", "url": "/assistenza"},
          {"type": "phone", "value": "05 0505"}
        ]
      }
    }
  ]
}
```

### 4. Zen del CMS

> "La pagina è i suoi blocchi. Il CMS definisce i blocchi.
> La blade non inventa, non decide — solo rende."

### 5. Il Contatto Duplicato (Anti-Pattern)

Il contacts card è duplicato in **14+ file blade**:

```
segnalazione-01-privacy.blade.php
segnalazione-02-dati.blade.php
segnalazione-03-riepilogo.blade.php
segnalazione-04-conferma.blade.php
segnalazione-crea (ticket-create-wizard.blade.php)
homepage.blade.php
elenco.blade.php
rating/default.blade.php
...
```

**Soluzione**: Ogni pagina CMS carica i suoi blocchi dal JSON. La blade del wizard (`ticket-create-wizard.blade.php`) NON deve avere contatti hardcoded — il contacts card viene dal blocco CMS.

## Come Funziona

### Page Model

```php
// Page::getBlocksBySlug('tests.segnalazione-crea', 'content')
// → Legge Page.blocks JSON dal DB
// → Restituisce array di BlockData con type, view, data
```

### Blade Page

```blade
{{-- pages/tests/[slug].blade.php — o pages/[container0]/[slug].blade.php —}}
@php
    $blocks = \Modules\Cms\Models\Page::getBlocksBySlug($this->pageSlug, 'content');
@endphp

@foreach($blocks as $block)
    @include($block->view, ['data' => $block->data])
@endforeach
```

### Contatti nel JSON

Nel Filament admin panel, quando si configura la pagina:
1. Aggiungi blocco "Contacts"
2. Configura heading, items (FAQ, Assistenza, Telefono, Appuntamento)
3. Salva → il JSON va nel DB
4. La blade rende automaticamente

## Componente Riutilizzabile

Creato: `components/bootstrap-italia/contacts-card.blade.php`

Questo componente **esiste** per essere incluso dai blocchi CMS, NON per essere chiamato direttamente dalle blade di pagina.

## Files

- Componente: `Themes/Sixteen/resources/views/components/bootstrap-italia/contacts-card.blade.php`
- Block: `Themes/Sixteen/resources/views/components/blocks/contacts/default.blade.php`
- Page Model: `Modules/Cms/app/Models/Page.php`
- HasBlocks Trait: `Modules/Cms/app/Models/Traits/HasBlocks.php`

## Docs Links
- [CSS Scoping Rule](./CSS-SCOPING-RULE.md)
- [HTML Parity](./HTML-PARITY-RULE.md)
- [Page Routing](./PAGE_ROUTING_ARCHITECTURE.md)
- [XotBaseWizard Philosophy](../../../Modules/Fixcity/docs/XOTBASE-WIZARD-PHILOSOPHY.md)
