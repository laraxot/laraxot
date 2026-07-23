# 🚀 Replikate Quick Start Guide

> **Guida rapida per iniziare a replicare pagine Design Comuni**

## 📚 Documenti Principali

| Documento | Scopo | Percorso |
|-----------|-------|----------|
| **Replikate.txt** | Guida completa | `prompts/replikate.txt` |
| **Design Comuni Index** | Panoramica progetto | `design-comuni/00-index.md` |
| **Pages Index** | Pagine da replicare | `design-comuni/pages/00-index.md` |
| **Blocks System** | Blocchi universali | `design-comuni/blocks/00-index.md` |
| **Homepage Docs** | Esempio completo | `design-comuni/pages/homepage.md` |

---

## 🎯 Percorsi di Apprendimento

### Per Sviluppatori Nuovi

```
1. Leggi Replikate.txt (30 min)
   ↓
2. Studia Design Comuni Index (15 min)
   ↓
3. Esamina Homepage Documentation (20 min)
   ↓
4. Crea prima pagina (60 min)
   ↓
5. Studia Blocks System (30 min)
   ↓
6. Crea primo blocco (30 min)
```

**Tempo totale:** ~3 ore

---

### Per Agenti AI

```
Context: laravel/Themes/Sixteen/docs/design-comuni/
Reference: prompts/replikate.txt
Task: Replicate Design Comuni page
Workflow:
  1. Leggi 00-index.md
  2. Studia pages/homepage.md (esempio)
  3. Crea JSON content
  4. Implementa blocchi
  5. Verifica con screenshot
```

**Tempo stimato:** 5-10 minuti

---

## 📖 Letture Essenziali

### Priorità Alta (Must Read)

1. **Replikate.txt** - Regole assolute, architettura, esempi
   - Sezione 1: Architettura del Sistema
   - Sezione 2: Vite Build System
   - Sezione 4: Page Routing con Folio + Volt
   - Sezione 5: Block System
   - Sezione 10: Regole Assolute

2. **Homepage Documentation** - Esempio completo
   - Struttura pagina
   - 6 blocchi con JSON
   - Checklist implementazione

3. **Blocks System** - Blocchi universali
   - 6 tipologie di blocchi
   - Esempi JSON per ogni blocco
   - Checklist creazione

---

### Priorità Media (Should Read)

4. **Design Comuni Index** - Panoramica
   - Quick start
   - Pagine da replicare
   - Risorse

5. **Pages Index** - Altre pagine
   - Argomenti
   - Appuntamento 06
   - Amministrazione
   - Documenti Dati

---

### Priorità Bassa (Nice to Read)

6. **REPLIKATE_COMPLETE.md** - Riepilogo task
7. **REPLIKATE_IMPROVEMENT_SUMMARY.md** - Dettagli miglioramento

---

## 🔍 Ricerca Rapida

### Trovare Informazione

| Ho bisogno di... | Cerca in... |
|------------------|-------------|
| Architettura sistema | Replikate.txt Sezione 1 |
| Errore Vite manifest | Replikate.txt Sezione 2 |
| Creare pagina | Pages Index + Homepage docs |
| Creare blocco | Blocks System |
| Theme detection | Replikate.txt Sezione 3 |
| Header/Footer | Replikate.txt Sezione 6 |
| Screenshot & analisi | Quality Assurance (Sez. 8) |
| Documentazione standards | Documentation Standards (Sez. 9) |

---

## 🛠️ Comandi Utili

```bash
# Build tema
cd laravel/Themes/Sixteen
npm run build
npm run copy

# Pulizia cache
php artisan optimize:clear

# Verifica PHPStan
composer phpstan

# Formattazione
composer pint
```

---

## ✅ Checklist Rapida

### Creare Pagina

```markdown
- [ ] Letto Homepage documentation
- [ ] Creato JSON: config/local/fixcity/database/content/pages/tests.<slug>.json
- [ ] Verificato nodo "slug": "tests.<slug>"
- [ ] Definiti blocchi universali (NON pagina-specifici)
- [ ] Build eseguito: npm run build && npm run copy
- [ ] Pagina verificata: http://fixcity.local/it/tests/<slug>
```

### Creare Blocco

```markdown
- [ ] Identificato tipo universale (hero, card-grid, etc.)
- [ ] Creato file: components/blocks/<tipo>/<blade>.blade.php
- [ ] Aggiunto styling: style-apply.css
- [ ] Documentato: blocks/<tipo>/<blade>.md
- [ ] Testato con JSON
```

---

## 📎 Risorse Esterne

### Design Comuni
- [GitHub](https://github.com/italia/design-comuni-pagine-statiche)
- [Demo](https://italia.github.io/design-comuni-pagine-statiche/)

### Laravel
- [Folio Docs](https://laravel.com/docs/folio)
- [Volt Docs](https://livewire.laravel.com/docs/volt)

### UI/UX
- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind UI Blocks](https://tailwindcss.com/plus/ui-blocks)
- [DaisyUI](https://daisyui.com/components/)

---

## 🎯 Prossimi Passi

### Subito (Oggi)

1. ✅ Leggi Replikate.txt (Sezioni 1-6)
2. ✅ Studia Homepage documentation
3. ✅ Crea prima pagina di test

### Questa Settimana

4. Implementa 2-3 blocchi universali
5. Documenta con screenshot
6. Verifica con checklist

### Questo Mese

7. Replica tutte le 5 pagine principali
8. Crea libreria di 10+ blocchi
9. Documentazione completa

---

## 💡 Consigli

### Per Sviluppatori

> **Inizia semplice:** Replica prima la homepage, è l'esempio più completo.

> **Usa JSON:** Tutto il contenuto deve essere in JSON, mai hardcoded in Blade.

> **Blocchi universali:** Crea blocchi riutilizzabili, NON specifici per pagina.

> **Documenta tutto:** Screenshot + analisi per ogni pagina.

### Per Agenti AI

> **Leggi contesto:** `design-comuni/00-index.md` per panoramica

> **Studia esempio:** `pages/homepage.md` è il template per tutte le pagine

> **Verifica sempre:** Usa checklist prima di considerare task completato

> **Documenta:** Ogni modifica deve avere documentazione bidirezionale

---

## 🚨 Errori Comuni da Evitare

### ❌ SBAGLIATO

```blade
<!-- File specifici -->
homepage.blade.php
amministrazione.blade.php

<!-- Namespace sbagliato -->
<x-sixteen::blocks.*>
<x-pub_theme::layouts.design-comuni>

<!-- Blocchi pagina-specifici -->
pub_theme::components.blocks.tests.argomenti.topics-grid

<!-- Vite senza secondo parametro -->
@vite(['resources/css/app.css'])

<!-- Import Bootstrap -->
@import url('bootstrap-italia.min.css')

<!-- Header/Footer in [slug].blade.php -->
<x-layouts.app>
    <div class="skiplink">...</div>
    <x-section slug="header" />
    <main>...</main>
    <x-section slug="footer" />
</x-layouts.app>
```

### ✅ CORRETTO

```blade
<!-- File dinamico -->
[slug].blade.php

<!-- Namespace corretto -->
<x-pub_theme::components.blocks.*>
<x-section slug="header" />

<!-- Blocchi universali -->
pub_theme::components.blocks.hero.newscard

<!-- Vite con secondo parametro -->
@vite(['resources/css/app.css'], 'themes/Sixteen')

<!-- Tailwind @apply -->
.classe {
    @apply bg-primary-600 text-white;
}

<!-- Layout hierarchy: [slug] extends app extends main -->
<x-layouts.app>
    @volt('tests.view')
        {{-- SOLO contenuto specifico --}}
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

---

## 📊 Coverage Map

| Pagina | HTML | Stili | Blocchi | Docs | Stato |
|--------|------|-------|---------|------|-------|
| Homepage | ⏳ | ⏳ | ⏳ | ✅ | 🔄 |
| Argomenti | ⏳ | ⏳ | ⏳ | ⏳ | 🔄 |
| Appuntamento 06 | ⏳ | ⏳ | ⏳ | ⏳ | ⏳ |
| Amministrazione | ⏳ | ⏳ | ⏳ | ⏳ | ⏳ |
| Documenti Dati | ⏳ | ⏳ | ⏳ | ⏳ | ⏳ |

**Legenda:** ✅ Completo | 🔄 In Progress | ⏳ Pending

---

## 🎓 Supporto

### Documentazione Interna
- [Main Index](../00-index.md)
- [Design Comuni Index](./00-index.md)
- [Replikate.txt](./prompts/replikate.txt)

### Risorse Esterne
- [Design Comuni GitHub](https://github.com/italia/design-comuni-pagine-statiche)
- [Laravel Folio](https://laravel.com/docs/folio)
- [Livewire Volt](https://livewire.laravel.com/docs/volt)

### Agent Collaboration
- Multi-agent workflow documentato in Replikate.txt Sezione 10
- Context sharing tramite documentazione bidirezionale

---

**Ultimo Aggiornamento:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Pronto all'uso

**"Inizia dalla homepage, studia l'esempio, replica il pattern."**
