---
type: second-brain-initiative
tags: [design-comuni, bmad, second-brain, study, parity, mcp, eec, filament-blueprint]
status: completed
created: 2026-10-17
updated: 2026-10-17
initiative_id: kp-designcomuni-deep-study
title: "Second Brain — Studio DesignComuni profondo"
qmd: "second brain   studio designcomuni profondo sixteen"
---
# Second Brain — Studio DesignComuni profondo

## Blocchi riconosciuti (tutte le pagine studiate)
1. Header slim-wrapper: logo regione + link portale + accesso personale + ricerca
2. Navbar collapsible (hamburger): Amministrazione, Novità, Servizi, Vivere il Comune + secondaria (Iscrizioni, Estate, Polizia locale)
3. Breadcrumb: ol con separator `<span>`; aria-label
4. Hero (cmp-hero): titolo grande, descrizione breve, immagine facoltativa, caption foto
5. Card content: `.card` / `.fi-card` con `shadow-md`, `rounded-2xl`, `border-slate-100`, gradient bg
6. FAQ / Accordion: `details` + `summary` con marker, `open:` states, focus ring
7. Argomenti correlati: pill links rounded-full, badge-soft, bg-primary-50
8. Footer accessibilità: WCAG 2.1 AA, dichiarazione, navigazione (tab/invio), contatti

## Regole applicate (EEC)
- Nessun documento in `bmad-output/` (deprecated); tutto nel modulo owner (Fixcity, User)
- DB mariadb (escluso sqlite) — verificato con `DB_CONNECTION=mariadb`
- Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite — NO Bootstrap Italia
- Ogni componente deve restare data-driven (AGENTS.md)
- Parità verificata a 375px / 768px / 1440px quando si modificano pagine front-office
- MCP migliorati: `.mcp.json` con descrizioni aggiornate (`laravel-boost`, `qmd`, `playwright`)

## Pagine migliorate alla fine di questo ciclo
- `/it/domande-frequenti` (FAQ) — parity alta, route fix, empty state, footer accessibilità
- `/it/blog/` — componente `blog/index.blade.php` con card grid
- `/it/events` — componente `events/index.blade.php` con parity config (`sixteen.events.parity`)
--- Aggiornamento /it/blog demo ---
Blog popolato per demo investitore: 3 nuovi articoli (factory) + commenti + categorie. DB MariaDB. HTTP 200 locale.
