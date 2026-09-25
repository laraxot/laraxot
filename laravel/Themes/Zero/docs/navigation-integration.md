---
title: "Contratto di navigazione del tema Zero"
type: guideline
theme: Zero
created: 2026-09-17
updated: 2026-09-17
tags: [navigazione, traduzioni, filament]
related:
  - ./ARCHITECTURE.md
---

# Contratto di navigazione del tema Zero

Zero è un tema infrastrutturale: non contiene traduzioni dominio e non
introduce fallback come `resource.navigation`. Le label e i gruppi arrivano
dai file di lingua del modulo proprietario; il tema si occupa solo della
presentazione responsive e accessibile.

Ogni nuova risorsa deve quindi correggere prima il file `lang/it` del modulo,
usare un'icona Heroicon valida e verificare la navigazione nel pannello.
