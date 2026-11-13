---
type: design-contract
github_issue: #101
decision: D17
title: "D17 — Header 'Accedi all'area personale': logica, normativa, scopo, professionale"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d17   header 'accedi all'area personale': logica, normativa, scopo, professionale sixteen"
---
# D17 — Header "Accedi all'area personale": logica, normativa, scopo, professionale

**Logica**: `Accedi all'area personale` in header. Se non loggato (`guest`) → `route('login')`. Se loggato (`auth()->check()`) → visualizza profilo con nome reale dal DB (`first_name`/`last_name` o `name`), non "Mario Rossi" ma es. "Marco Sottana" (email: `marco.sottana@gmail.com`).

**Normativa**: GDPR 2016/679 art. 13 (dati personali: nome, email); CAD D.Lgs 82/2005 (accesso digitale); AGID (design PA, accessibilità WCAG 2.1 AA, multilingua); Design Comuni v2.4.0.

**Scopo**: l'area personale (tab Scrivania/Messaggi/Attività/Servizi) mostra: profilo utente (nome, CF), segnalazioni (`AN4059281`), graduatorie (scuola infanzia), messaggi, pagamenti, permessi. Non è un mock — usa dati reali.

**Multilingua**: `app()->getLocale()` (`it`/`en`). Header JSON (`sections/header.json`) include varianti `it`/`en`. `__()` per traduzioni.
**Mobile**: responsive (`nav-primary` collapsa su mobile); tab visibili come menu tendina; componente accessibile (focus-visible, prefers-reduced-motion).
**Professionale**: contratti non classi astratte (`RatingContract`); PHPStan livello max (0 error); BMAD document (D11-D17); migration timestamp-only; no root `.txt`/`.md` in modules/themes.

**Riferimento visivo**: `segnalazione-area-personale.html` (15 sec, 3.6KB, nome "Giulia Rossi", CF); `graduatoria-area-personale.html` (15 sec, 4.2KB, "Giulia Bianchi"). Nome reale, non generico.
