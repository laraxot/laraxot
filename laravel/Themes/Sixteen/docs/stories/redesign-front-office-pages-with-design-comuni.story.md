---
id: "kp-redesign-front-office-pages-design-comuni"
type: "epic-story"
title: "Redesign all front office pages using Design Comuni design system"
description: "Ridisegnare tutte le pagine front office del tema Sixteen prendendo come riferimento il design system Design Comuni per i comuni italiani, mantenendo la separazione tra contenuto (JSON) e presentazione (Blade/Livewire)."
frontmatter:
  github_issue: "#55"
  github_discussion: "#55"
links:
  github_issue: "#55"
  github_discussion: "#55"
---
## Obiettivo
Ridisegnare tutte le pagine front office del tema Sixteen (homepage, pagine di sezione, pagine di contenuto, FAQ, sitemap, ecc.) ispirandosi al design system ufficiale Design Comuni, mantenendo la struttura JSON-driven dove applicabile e utilizzando componenti universali riutilizzabili.

## Stato attuale
- Il tema Sixteen contiene diverse pagine front office sotto `resources/views/pages/` e `resources/views/components/pages/`.
- Alcune pagine utilizzano ancora template Blade tradizionali con logica e presentazione mescolate.
- La homepage è già parzialmente migrata a struttura JSON-driven (`home.json`) ma presenta errori di runtime.
- Il design attuale non segue coerentemente un design system e presenta elementi di stile ad-hoc.

## Riferimento Design Comuni
- Repository: https://github.com/italia/design-comuni-pagine-statiche
- Analisi effettuata: template `src/pages/index.hbs` (homepage) e struttura generale del design system.
- Caratteristiche principali da adottare:
  * Utilizzo di Tailwind CSS tramite `@apply` (non CDN Bootstrap Italia)
  * Componenti universali riutilizzabili (cmp-hero, cmp-feature-card, cmp-stat-card, cmp-cta-section, cmp-testimonial, cmp-button, etc.)
  * Layout basato su grid system e spacing coerente (8px grid)
  * Tipografia definita con classi Tailwind (text sizes, weights, leading)
  * Palette di colori derivata da Bootstrap Italia (blue, orange, yellow, green, purple, gray)
  * Accessibilità WCAG 2.1 AA (contrasto, stati fokusable, transizioni rispettando prefers-reduced-motion)
  * Utilizzo di Alpine.js per interazioni leggere dove necessario

## Pagina da redesignare (elenco non esaustivo)
1. Homepage: `resources/views/pages/index.blade.php`
2. Pagine di sezione: `resources/views/pages/[container0]/index.blade.php`
3. Pagine di contenuto: `resources/views/pages/[container0]/[slug0]/index.blade.php`
4. Pagine annidate: `resources/views/pages/[container0]/[slug0]/[container1]/index.blade.php`
5. FAQ: `resources/views/components/pages/faq.blade.php`
6. Sitemap: `resources/views/components/pages/sitemap.blade.php`
7. Altre pagine componenti sotto `resources/views/components/pages/`

## Piano di azione
1. **Analisi completa**: Esaminare tutte le pagine front office esistenti per capire struttura, dati necessari, e componenti attualmente utilizzati.
2. **Definizione componenti universali**: Basandosi su Design Comuni, definire un set di componenti universali (Livewire Volt o Blade components) che possano essere riutilizzati in tutte le pagine.
3. **Aggiornamento struttura dati**: Dove necessario, estendere o modificare le strutture JSON (come `home.json`) per supportare i nuovi componenti, mantenendo retrocompatibilità.
4. **Creazione componenti**: Implementare i componenti universali utilizzando Tailwind CSS e Alpine.js, seguendo le linee guida di Design Comuni.
5. **Refactoring pagine**: Sostituire le pagine esistenti con versioni che utilizzino i componenti universali e i dati da JSON (dove applicabile) o da variabili passare al componente.
6. **Garanzia di accessibilità**: Verificare che tutte le pagine rispettino WCAG 2.1 AA (contrasto testo/sfondo, stati fokusable, label per campi form, transizioni rispettando prefers-reduced-motion).
7. **Testing multi-tenant e multilingua**: Assicurarsi che il redesign funzioni in tutti i contesti (diversi domini, lingue) grazie al sistema di localizzazione e al routing container0.
8. **Documentazione**: Aggiornare la documentazione del tema per spiegare l'uso dei nuovi componenti e la struttura dati.

## Vincoli
- Non utilizzare servizi o controller (logica nelle Actions o nei componenti).
- Non modificare la struttura delle migrazioni in modo non forward-only.
- Mantenere la possibilità di eseguire il tema in modalità multi-tenant e multilingua.
- Utilizzare esclusivamente i token di design già presenti nel tema (o estenderli in modo coerente con Design Comuni se necessario, documentando le estensioni).
- Non introdurre dipendenze non necessarie (usare ciò che è già disponibile nel progetto: Tailwind, Alpine.js, Livewire, Blade).
- Rispettare la convenzione "no services" e "no controllers" del progetto.

## Metriche di successo
- Tutte le pagine front office si caricano senza errori di runtime (Blade, faccades, viste, componenti).
- Il design rispecchia chiaramente l'ispirazione da Design Comuni (layout, tipografia, colori, spaziature, componenti).
- Tutti gli elementi interattivi hanno gli stati necessari (default, hover, focus-visible, active, disabled) e rispettano prefers-reduced-motion.
- Il passaggio è conforme alle linee guida di accessibilità WCAG 2.1 AA.
- Il contenuto rimane gestibile tramite strutture dati (JSON o variabili passare al componente) senza modifiche al codice per aggiungere nuovi testi o modificare quelli esistenti.
- Le pagine sono responsive e funzionano correttamente su dispositivi mobili, tablet e desktop.