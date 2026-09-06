---
title: "UI — Il Design System Sacro"
description: "Sistema di componenti UI riutilizzabili e design system per interfacce utente coerenti"
module: "UI"
alias: "ui"
version: "1.0.0"
priority: 0
active: true
status: "core-foundation"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["Xot"]
extends: []
extended_by: 40
documentation_date: "2026-05-27"
---

# UI — Il Design System Sacro

## Scopo

UI è il sistema di componenti riutilizzabili e design system per interfacce utente coerenti. Fornisce i building block visivi che tutti i moduli condividono: componenti Tailwind, layout, temi, pattern di design per Filament v5. Garantisce che ogni modulo dell'ecosistema abbia la stessa esperienza visiva senza dover reinventare il wheel.

## Religione

- **"Il sistema di componenti è sacro"**: ogni modulo UI che estende Xot deve usare i componenti del sistema
- **"Tailwind è l'unico linguaggio visivo"**: nessun CSS custom outside Tailwind, nessun framework CSS alternativo
- **"Filament v5 è il patto admin"**: tutti i componenti admin devono essere costruiti sopra i pattern Filament
- **"XotBase come fondamento"**: `XotBaseResource`, `XotBasePage`, `XotBaseWidget` sono i punti di ingresso obbligatori
- **"Design tokens da config"**: `MetatagData` (title, logo, colors) è la fonte unica di verità per il branding

## Filosofia

UI crede che **la coerenza visiva sia un diritto dell'utente, non un optional**. Ogni componente condiviso è un patto: l'utente che naviga tra moduli diversi non dovrebbe mai percepire un cambio di paradigm visivo. Il sistema è progettato per l'**estensione controllata**: i moduli possono specializzare i componenti ma non possono rompere il contratto visivo.

## Politica

- **Design system vs custom components**: ogni modulo che crea un componente deve chiedersi "questo è riutilizzabile in altri moduli?" Se sì, va in UI.
- **Tailwind config condivisa**: `tailwind.config.js` è la configurazione di sistema
- **Filament v5 come standard**: l'admin panel non è un design pattern, è il framework di riferimento
- **Responsive by default**: ogni componente deve essere responsive senza configurazione aggiuntiva
- **Accessibility come politica**: WCAG 2.1 AA è il livello minimo accettabile

## Zen

> **"Semplicità vince sulla complessità. Il codice chiaro è più potente di mille righe di commenti."**

Lo Zen di UI è la **chiarezza visiva**. Ogni componente deve comunicare il suo scopo senza ambiguità. Se un utente deve chiedersi "cos'è questo?", il componente ha fallito.

## Perché esiste

L'ecosistema ha 40+ moduli che condividono l'interfaccia admin. Senza un design system, ogni modulo avrebbe creato i propri componenti, portando a frammentazione visiva e incoerenza. UI esiste per **centralizzare la decisione visiva** e rendere ogni modulo coerente con il resto.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun sistema di componenti React/Vue separato | Alta | Creare `UI/Components` come package JS separato con storybook |
| Manca tema dark/light mode nativo | Alta | Aggiungere `ThemeSwitch` component e configurazione `MetatagData` |
| Nessun component library per pubblico (frontend) | Alta | Creare `UI/PublicComponents` con componenti Folio/Volt esportabili |
| Manca design tokens per tipografia e spacing | Media | Definire `DesignTokens` con scala tipografica e spacing system |
| Nessun sistema di componenti iconografici | Media | Aggiungere `IconRegistry` con SVG sprites |
| Manca documentazione visuale dei componenti | Media | Creare `UI/ComponentCatalogue` con esempi interattivi |
| Nessun sistema di responsive preview | Bassa | Aggiungere tool per preview dispositivi |
| Manca componenti per dashboard avanzate | Bassa | Chart widgets avanzati (grafici, mappe, heatmap) |
| Nessun sistema di a11y testing automatizzato | Bassa | Aggiungere `pa11y` o `axe-core` nella pipeline CI |

---

*Documento generato secondo le convenzioni del progetto — modulo `UI` — data 2026-05-27*