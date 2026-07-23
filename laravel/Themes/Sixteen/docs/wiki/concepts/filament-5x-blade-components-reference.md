---
title: "Filament 5.x Blade Components — Reference e Regola Filament-First"
type: concept
sources: ["https://filamentphp.com/docs/5.x/components/overview"]
confidence: high
created: 2026-05-29
updated: 2026-05-29
tags: [filament, blade-components, filament-first, tabs, modal, dropdown, badge, button, callout, section, pagination, breadcrumbs]
related:
  - concepts/filament-first-frontoffice.md
  - concepts/ticket-list-filament-tabs.md
  - concepts/filament-v5-hybrid-pattern-reference.md
  - ../../docs/wiki/rules/filament-first-rule.md
---

# Filament 5.x Blade Components — Reference e Regola Filament-First

## REGOLA FONDAMENTALE

> **Se esiste un componente Filament che soddisfa il bisogno, USARE SEMPRE quello.**
> Non creare HTML custom, non usare Bootstrap components equivalenti, non inventare Alpine components.

Questa regola si applica a **tutto il frontoffice** (Themes/Sixteen) e **admin** (Filament panel).

**Fonte**: https://filamentphp.com/docs/5.x/components/overview

---

## Catalogo Blade Components Filament 5.x

### Package Components (richiedono Livewire)
| Componente | Tag | Note |
|-----------|-----|------|
| Action | `x-filament::action` | Trigger azioni Livewire |
| Form | `x-filament::form` | Form Livewire-aware |
| Infolist | `x-filament::infolist` | Lista informazioni |
| Notifications | `x-filament::notifications` | Toast/alert |
| Schema | `x-filament::schema` | Schema dinamico |
| Table | `x-filament::table` | Tabella con ordinamento/filtri |
| Widget | `x-filament::widget` | Widget embeddable |

### Blade Components (usabili senza Livewire)
| Componente | Tag principale | Usare invece di... |
|-----------|---------------|-------------------|
| **Avatar** | `x-filament::avatar` | `<img>` circolare custom |
| **Badge** | `x-filament::badge` | `<span class="badge">` Bootstrap |
| **Button** | `x-filament::button` | `<button class="btn">` Bootstrap |
| **Breadcrumbs** | `x-filament::breadcrumbs` | `<nav class="breadcrumb">` Bootstrap |
| **Callout** | `x-filament::callout` | `<div class="alert">` Bootstrap |
| **Checkbox** | `x-filament::checkbox` | `<input type="checkbox">` custom |
| **Dropdown** | `x-filament::dropdown` | `<div class="dropdown">` Bootstrap |
| **Empty state** | `x-filament::empty-state` | HTML custom vuoto |
| **Fieldset** | `x-filament::fieldset` | `<fieldset>` custom |
| **Icon button** | `x-filament::icon-button` | `<button>` con solo icona |
| **Input** | `x-filament::input` | `<input>` custom |
| **Input wrapper** | `x-filament::input.wrapper` | wrapper input custom |
| **Link** | `x-filament::link` | `<a class="...">` custom |
| **Loading indicator** | `x-filament::loading-indicator` | spinner custom |
| **Modal** | `x-filament::modal` | `data-bs-toggle="modal"` Bootstrap |
| **Pagination** | `x-filament::pagination` | `{{ $collection->links() }}` default |
| **Section** | `x-filament::section` | `<div class="card">` Bootstrap |
| **Select** | `x-filament::select` | `<select>` custom |
| **Tabs** | `x-filament::tabs` | `ul.nav.nav-tabs` Bootstrap |

---

## API Dettagliata per Componente

### `x-filament::tabs` ⭐ (STORY-068)

**Regola scope Alpine**: `x-data` va SUL componente tabs, non fuori.
Se i pannelli sono fuori dal componente → usare `Alpine.store`.

```blade
{{-- Pattern A: pannelli dentro il componente --}}
<x-filament::tabs x-data="{ activeTab: 'mappa' }">
    <x-filament::tabs.item
        alpine-active="activeTab === 'mappa'"
        x-on:click="activeTab = 'mappa'"
    >Mappa</x-filament::tabs.item>
    <x-filament::tabs.item
        alpine-active="activeTab === 'elenco'"
        x-on:click="activeTab = 'elenco'"
    >Elenco</x-filament::tabs.item>
</x-filament::tabs>

{{-- Pattern C: pannelli FUORI dal componente → Alpine.store --}}
<x-filament::tabs :label="__('...')">
    <x-filament::tabs.item
        alpine-active="$store.myTabs.active === 'mappa'"
        x-on:click="$store.myTabs.setTab('mappa')"
    >Mappa</x-filament::tabs.item>
</x-filament::tabs>
<div x-show="$store.myTabs.active === 'mappa'">...</div>
```

**Attributi `tabs.item`:**

| Attributo | Tipo | Descrizione |
|-----------|------|-------------|
| `active` | bool | Stato attivo statico |
| `:active` | PHP/Livewire | Stato attivo dinamico Livewire |
| `alpine-active` | string (Alpine expr) | Stato attivo Alpine |
| `x-on:click` | Alpine | Click handler Alpine |
| `wire:click` | Livewire | Click handler Livewire |
| `icon` | heroicon name | Icona |
| `icon-position` | `before`\|`after` | Posizione icona |
| `tag` | `button`\|`a` | Tag HTML (default: `button`) |
| `:href` | string | URL (solo con `tag="a"`) |
| badge slot | slot | Badge numerico |

**Attributi tabs (verticale):**
```blade
<x-filament::tabs vertical>...</x-filament::tabs>
```

---

### `x-filament::badge`

```blade
<x-filament::badge>New</x-filament::badge>
<x-filament::badge size="xs">New</x-filament::badge>
<x-filament::badge size="sm">New</x-filament::badge>
<x-filament::badge color="danger">Errore</x-filament::badge>
<x-filament::badge color="success">OK</x-filament::badge>
<x-filament::badge color="warning">Attenzione</x-filament::badge>
<x-filament::badge color="info">Info</x-filament::badge>
<x-filament::badge color="gray">Neutro</x-filament::badge>
<x-filament::badge icon="heroicon-m-sparkles">New</x-filament::badge>
<x-filament::badge icon="heroicon-m-sparkles" icon-position="after">New</x-filament::badge>
```

**Colori**: `danger` | `gray` | `info` | `success` | `warning`

---

### `x-filament::button`

```blade
<x-filament::button wire:click="action">Salva</x-filament::button>

{{-- Come link --}}
<x-filament::button href="/segnalazioni" tag="a">Vai</x-filament::button>

{{-- Dimensioni: xs | sm | md (default) | lg | xl --}}
<x-filament::button size="xs">Piccolo</x-filament::button>
<x-filament::button size="lg">Grande</x-filament::button>

{{-- Colori: danger | gray | info | success | warning --}}
<x-filament::button color="danger">Elimina</x-filament::button>
<x-filament::button color="success">Conferma</x-filament::button>

{{-- Con icona --}}
<x-filament::button icon="heroicon-m-plus">Nuovo</x-filament::button>
<x-filament::button icon="heroicon-m-plus" icon-position="after">Nuovo</x-filament::button>

{{-- Outlined --}}
<x-filament::button outlined>Annulla</x-filament::button>

{{-- Con tooltip --}}
<x-filament::button tooltip="Registra un utente">Nuovo utente</x-filament::button>

{{-- Con badge --}}
<x-filament::button badge-color="danger">
    Notifiche
    <x-slot name="badge">3</x-slot>
</x-filament::button>
```

---

### `x-filament::modal`

```blade
{{-- Base --}}
<x-filament::modal>
    <x-slot name="trigger">
        <x-filament::button>Apri</x-filament::button>
    </x-slot>
    Contenuto modale
</x-filament::modal>

{{-- Controllo da JS/Livewire --}}
<x-filament::modal id="modal-categorie">
    Contenuto
</x-filament::modal>
{{-- Aprire via: $dispatch('open-modal', { id: 'modal-categorie' }) --}}
{{-- Chiudere via: $dispatch('close-modal', { id: 'modal-categorie' }) --}}

{{-- Con heading, description, icon --}}
<x-filament::modal
    icon="heroicon-o-information-circle"
    icon-color="info"
>
    <x-slot name="heading">Titolo</x-slot>
    <x-slot name="description">Descrizione</x-slot>
    Contenuto
    <x-slot name="footer">
        <x-filament::button>Chiudi</x-filament::button>
    </x-slot>
</x-filament::modal>

{{-- Slide-over invece di modale --}}
<x-filament::modal slide-over>...</x-filament::modal>

{{-- Dimensioni: xs | sm | md | lg | xl | 2xl | 3xl | 4xl | 5xl | 6xl | 7xl | screen --}}
<x-filament::modal width="5xl">...</x-filament::modal>

{{-- Opzioni comportamento --}}
<x-filament::modal
    sticky-header
    sticky-footer
    :close-by-clicking-away="false"
    :close-by-escaping="false"
    :close-button="false"
    alignment="center"
>
```

**Usare per**: filtri mobile, conferme, dialoghi — invece di `data-bs-toggle="modal"`.

---

### `x-filament::dropdown`

```blade
<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button>Azioni</x-filament::button>
    </x-slot>
    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="view">
            Visualizza
        </x-filament::dropdown.list.item>
        <x-filament::dropdown.list.item
            href="/segnalazioni/1"
            tag="a"
            icon="heroicon-m-pencil"
            color="danger"
        >
            Modifica
        </x-filament::dropdown.list.item>
        {{-- Con badge --}}
        <x-filament::dropdown.list.item badge-color="danger">
            Notifiche
            <x-slot name="badge">3</x-slot>
        </x-filament::dropdown.list.item>
        {{-- Con immagine --}}
        <x-filament::dropdown.list.item image="https://...">
            Nome utente
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>

{{-- Posizionamento: top-start | top-end | bottom-start | bottom-end | ecc. --}}
<x-filament::dropdown placement="top-start">...</x-filament::dropdown>

{{-- Dimensione: xs | sm | md | lg | xl | 2xl–7xl --}}
<x-filament::dropdown width="xs">...</x-filament::dropdown>

{{-- Altezza massima --}}
<x-filament::dropdown max-height="400px">...</x-filament::dropdown>
```

**Usare per**: menu utente header, menu azioni, invece di `data-bs-toggle="dropdown"`.

---

### `x-filament::breadcrumbs`

```blade
<x-filament::breadcrumbs :breadcrumbs="[
    '/' => 'Home',
    '/it' => 'Segnalazioni',
    '/it/segnalazione-1' => 'Segnalazione #1',
]" />
```

**Usare per**: breadcrumb navigazione — invece di `ol.breadcrumb` Bootstrap custom.

---

### `x-filament::callout`

```blade
{{-- Base --}}
<x-filament::callout
    icon="heroicon-o-information-circle"
    color="info"
>
    <x-slot name="heading">Avviso importante</x-slot>
    <x-slot name="description">Descrizione dettagliata.</x-slot>
</x-filament::callout>

{{-- Colori: danger | info | success | warning | primary --}}
<x-filament::callout icon="heroicon-o-x-circle" color="danger">
    <x-slot name="heading">Errore</x-slot>
    <x-slot name="description">Qualcosa è andato storto.</x-slot>
</x-filament::callout>

{{-- Con footer e controlli --}}
<x-filament::callout icon="heroicon-o-exclamation-circle" color="warning">
    <x-slot name="heading">Abbonamento in scadenza</x-slot>
    <x-slot name="description">Scade tra 7 giorni.</x-slot>
    <x-slot name="footer">
        <x-filament::button size="sm">Rinnova</x-filament::button>
    </x-slot>
    <x-slot name="controls">
        <x-filament::icon-button icon="heroicon-m-x-mark" color="gray" label="Chiudi"/>
    </x-slot>
</x-filament::callout>

{{-- Solo heading, senza icona --}}
<x-filament::callout>
    <x-slot name="heading">Nota</x-slot>
</x-filament::callout>
```

**Usare per**: alert, avvisi, notifiche inline — invece di `div.alert` Bootstrap.

---

### `x-filament::section`

```blade
{{-- Base --}}
<x-filament::section>
    <x-slot name="heading">Dettagli utente</x-slot>
    Contenuto
</x-filament::section>

{{-- Con description e icona --}}
<x-filament::section icon="heroicon-o-user">
    <x-slot name="heading">Dettagli utente</x-slot>
    <x-slot name="description">Tutte le informazioni sull'utente.</x-slot>
    Contenuto
</x-filament::section>

{{-- Con contenuto after-header --}}
<x-filament::section>
    <x-slot name="heading">Utenti</x-slot>
    <x-slot name="afterHeader">
        <x-filament::button size="sm">Aggiungi</x-filament::button>
    </x-slot>
    Contenuto
</x-filament::section>

{{-- Collassabile --}}
<x-filament::section collapsible>
    <x-slot name="heading">Filtri avanzati</x-slot>
    Contenuto
</x-filament::section>

{{-- Collassato di default --}}
<x-filament::section collapsible collapsed>
    <x-slot name="heading">Dettagli tecnici</x-slot>
    Contenuto
</x-filament::section>
```

**Usare per**: card con header, sezioni collassabili — invece di `div.card` Bootstrap.

---

### `x-filament::pagination`

```blade
{{-- In un Livewire component --}}
<x-filament::pagination :paginator="$users" />

{{-- Con scelta items per pagina --}}
<x-filament::pagination
    :paginator="$users"
    :page-options="[5, 10, 20, 50, 100, 'all']"
    current-page-option-property="perPage"
/>

{{-- Con link prima/ultima pagina --}}
<x-filament::pagination :paginator="$users" extreme-links />
```

**Usare per**: paginazione — invece di `{{ $collection->links() }}` con view default Laravel.

---

## Tabella di Decisione: Filament vs Bootstrap/Custom

| Bisogno | ❌ Da evitare | ✅ Usare |
|---------|-------------|---------|
| Tab navigation | `ul.nav.nav-tabs` + JS | `x-filament::tabs` + `alpine-active` |
| Modale/dialog | `data-bs-toggle="modal"` | `x-filament::modal` |
| Dropdown menu | `data-bs-toggle="dropdown"` | `x-filament::dropdown` |
| Alert/Avviso | `div.alert alert-warning` | `x-filament::callout color="warning"` |
| Card/sezione | `div.card` | `x-filament::section` |
| Breadcrumb | `ol.breadcrumb` | `x-filament::breadcrumbs` |
| Badge numerico | `span.badge` Bootstrap | `x-filament::badge` |
| Button CTA | `button.btn.btn-primary` | `x-filament::button` |
| Paginazione | `{{ $links->links() }}` | `x-filament::pagination` |

---

## Gotcha: Scope Alpine nei Tabs

### ❌ Pattern SBAGLIATO (storico, causa STORY-065/066)

```blade
{{-- x-data FUORI dal componente Filament → DUE SCOPE SEPARATI → BUG --}}
<div x-data="{ activeTab: 'map' }">
    <x-filament::tabs>
        <x-filament::tabs.item
            :alpine-active="'activeTab === \'map\''"  {{-- scope ESTERNO --}}
            x-on:click="activeTab = 'map'"             {{-- scope INTERNO filament --}}
        >
```

### ✅ Pattern CORRETTO A (pannelli dentro)

```blade
<x-filament::tabs x-data="{ activeTab: 'map' }">
    <x-filament::tabs.item alpine-active="activeTab === 'map'" x-on:click="activeTab = 'map'">
```

### ✅ Pattern CORRETTO C (pannelli fuori — Alpine.store)

```javascript
// app.js
AlpineInstance.store('myTabs', {
    active: 'map',
    setTab(tab) { this.active = tab; }
});
```
```blade
<x-filament::tabs>
    <x-filament::tabs.item
        alpine-active="$store.myTabs.active === 'map'"
        x-on:click="$store.myTabs.setTab('map')"
    >
</x-filament::tabs>
<div x-show="$store.myTabs.active === 'map'">pannello mappa</div>
```

---

## Dispatch Modal da Alpine/Livewire

```blade
{{-- Da Alpine --}}
x-on:click="$dispatch('open-modal', { id: 'modal-filtri' })"

{{-- Da Livewire --}}
$this->dispatch('open-modal', id: 'modal-filtri');

{{-- Modale --}}
<x-filament::modal id="modal-filtri">...</x-filament::modal>
```

**Questo pattern SOSTITUISCE `data-bs-toggle="modal" data-bs-target="#modal-filtri"`**.

---

## Applicazione in FixCity — Frontoffice (`/it`)

| Componente in pagina | Stato attuale | Stato target |
|---------------------|---------------|--------------|
| Tab Mappa/Elenco | ✅ `x-filament::tabs` + Alpine.store (STORY-068) | ✅ Done |
| Modal filtri mobile | `data-bs-toggle="modal"` BS | → `x-filament::modal` |
| Breadcrumb segnalazioni | `ol.breadcrumb` Bootstrap custom | → `x-filament::breadcrumbs` |
| Alert vuoto (no segnalazioni) | HTML custom | → `x-filament::callout` |
| Paginazione elenco | `{{ $links() }}` default | → `x-filament::pagination` |

---

## Riferimenti

- Doc ufficiale: https://filamentphp.com/docs/5.x/components/overview
- Tabs: https://filamentphp.com/docs/5.x/components/tabs
- Modal: https://filamentphp.com/docs/5.x/components/modal
- Dropdown: https://filamentphp.com/docs/5.x/components/dropdown
- Badge: https://filamentphp.com/docs/5.x/components/badge
- Button: https://filamentphp.com/docs/5.x/components/button
- Callout: https://filamentphp.com/docs/5.x/components/callout
- Section: https://filamentphp.com/docs/5.x/components/section
- Breadcrumbs: https://filamentphp.com/docs/5.x/components/breadcrumbs
- Pagination: https://filamentphp.com/docs/5.x/components/pagination
- Story implementazione tabs: `docs/stories/STORY-068-it-tabs-filament-5x-correct-pattern.md`
- Regola Filament-First: `docs/wiki/rules/filament-first-rule.md`
- GitHub Issue: https://github.com/laraxot/base_fixcity_fila5/issues/153
- GitHub Discussion: https://github.com/laraxot/base_fixcity_fila5/discussions/154
