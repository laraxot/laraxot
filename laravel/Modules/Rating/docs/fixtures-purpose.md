---
title: "fixtures purpose"
type: note
status: draft
updated: 2026-10-15
tags: [docs, rating]
qmd: "fixtures purpose rating"
---
Scopo fixtures: forniscono host concreti per i trait (HasRating) così PHPStan analizza il corpo del trait (senza consumatori reali il corpo è ignorato). Spostati in app/Fixtures/ per mantenere utilità senza violare la regola 'no Tests dirs' nei moduli.
