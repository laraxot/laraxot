---
id: "kp-design-comuni-homepage-improvement"
type: "epic-story"
title: "Homepage improvement using Design Comuni as reference"
description: "Migliorare la homepage del tema Sixteen prendendo spunto dal design system Design Comuni per i comuni italiani, mantenendo la struttura JSON-driven già implementata."
frontmatter:
  github_issue: "#54"
  github_discussion: "#54"
links:
  github_issue: "#54"
  github_discussion: "#54"
---
## Obiettivo
Migliorare l'aspetto visivo e l'esperienza utente della homepage di FixCity (tema Sixteen) ispirandosi al design system ufficiale Design Comuni, mantenendo la separazione tra contenuto (JSON) e presentazione (Blade/Livewire).

## Stato attuale
- La tema Sixteen è già migrata a struttura JSON-driven per la homepage (`home.json`).
- La homepage attuale presenta alcuni errori di runtime (icone mancanti, viste non trovate, facade LaravelLocalization non disponibile).
- Il design attuale è basato su un template Blade precedente (`resources/views/pages/index.blade.php.old`).

## Riferimento Design Comuni
- Repository: https://github.com/italia/design-comuni-pagine-statiche
- Homepage template: `src/pages/index.hbs` (analizzato e indicizzato in context-mode)
- Caratteristiche principali:
  * Utilizzo di Tailwind CSS tramite `@apply` (non CDN Bootstrap Italia)
  * Layout responsive con container e griglia
  * Componenti universali riutilizzabili (cmp-hero, cmp-card, etc.)
  * Sezioni chiaramente definite (Generali, Amministrazione, Novità, ecc.)
  * Accessibilità WCAG 2.1 AA
  * Uso di Alpine.js per interazioni leggere

## Piano di azione
1. Analizzare in dettaglio il template `src/pages/index.hbs` di Design Comuni per estrarre:
   - Struttura delle sezioni
   - Classi Tailwind utilizzate
   - Componenti universali
   - Pattern di layout
2. Confrontare con la struttura attuale di `home.json` e identificare i mapping necessari.
3. Aggiornare il file `home.json` per includere eventuali nuovi campi necessari al nuovo design (mantenendo la retrocompatibilità).
4. Creare o aggiornare i componenti Blade/Livewire necessari (utilizzando la struttura universale, non componenti specifici alla pagina).
5. Sostituire il template Blade attuale (`resources/views/pages/index.blade.php`) con una versione che utilizzi i componenti universali e i dati da `home.json`.
6. Assicurarsi che il design sia conforme a WCAG 2.1 AA (contrasto, stati fokusable, ecc.).
7. Testare la homepage in diverse lingue (italiano, inglese) grazie al sistema di localizzazione già presente.

## Vincoli
- Non utilizzare servizi o controller (logica nelle Actions o nei componenti).
- Non modificare la struttura delle migrazioni in modo non forward-only.
- Mantenere la possibilità di eseguire il tema in modalità multi-tenant e multilingua.
- Utilizzare esclusivamente i token di design già presenti nel tema (o estenderli in modo coerente con Design Comuni se necessario).
- Non introdurre dipendenze non necessarie (usare ciò che è già disponibile nel progetto: Tailwind, Alpine.js, Livewire).

## Metriche di successo
- La homepage si carica senza errori di runtime (Blade, faccades, viste).
- Il design rispecchia chiaramente l'ispirazione da Design Comuni (layout, tipografia, colori, spaziature).
- Tutti gli elementi interattivi hanno gli stati necessari (default, hover, focus-visible, active, disabled).
- Il passaggio è conforme alle linee guida di accessibilità WCAG 2.1 AA.
- Il contenuto rimane gestibile tramite `home.json` senza modifiche al codice per aggiungere nuovi testi o modificare quelli esistenti.