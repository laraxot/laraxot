# 🧠 ZEN NAKED PAGE PHILOSOPHY

**Data**: 2026-03-23  
**Stato**: ✅ OBBLIGATORIO  
**Priorità**: CRITICAL

---

## 🎯 IL CONCETTO DI "NAKED PAGE"

La filosofia **Zen Naked Page** impone che i file di routing del theme (Folio pages) siano il più possibile privi di styling hardcoded.

### Perché "Naked"?

1. **Delega al CMS**: Il controllo del layout (max-width, padding, background) deve essere delegato ai blocchi del CMS (`x-page` e i suoi `content_blocks`).
2. **Flessibilità Cinematic**: Se una pagina ha bisogno di una sezione Hero a tutta larghezza (cinematic) seguita da una sezione contenuta, un wrapper `max-w-7xl` nel file di routing impedirebbe l'espansione della Hero.
3. **Separazione delle Responsabilità**:
    - **Folio Page**: Responsabile solo del routing e della risoluzione dei parametri (`container0`, `slug0`).
    - **Layout (`x-layouts.app`)**: Responsabile della struttura HTML di base, SEO e background globale (`bg-slate-950`).
    - **CMS Blocks**: Responsabili del contenuto, del layout interno e dello spacing specifico.

---

## 📜 REGOLE DI IMPLEMENTAZIONE

### 1. Wrapper Minimale

Le pagine in `resources/views/pages/[container0]/index.blade.php` devono contenere solo un wrapper `div` semantico senza classi di styling.

**SBAGLIATO**:
```blade
<div class="min-h-screen bg-slate-950">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
</div>
```

**CORRETTO**:
```blade
<div>
    <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
</div>
```

### 2. Ereditarietà del Background

Il background `bg-slate-950` è già definito in `x-layouts.app` (tramite `x-layouts.main`). Non deve essere ripetuto nelle singole pagine per evitare ridondanze e semplificare eventuali cambi di tema globale.

### 3. Gestione dello Spacing

Lo spacing (padding, margini) deve essere gestito all'interno dei componenti Blade richiamati dal CMS. Questo permette di avere:
- Blocchi con background diversi.
- Blocchi "full-bleed" (a tutta larghezza).
- Blocchi con padding personalizzato.

---

## 🧘 ZEN ZEN ZEN

> "Meno codice nel routing, più libertà nel contenuto."

---

**Ultimo Aggiornamento**: 2026-03-23  
**Visto da**: Super Mucca 🐄✨
