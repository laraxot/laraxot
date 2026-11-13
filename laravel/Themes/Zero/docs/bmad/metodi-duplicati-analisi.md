---
theme: Zero
topic: metodi-duplicati-analisi
tags: [metodi-duplicati, refactoring]
canonical: ../../bmad/metodi-duplicati-analisi.md
id: zero-docs-bmad-metodi-duplicati-analisi
slug: metodi-duplicati-analisi
title: "Metodi Duplicati - Analisi Tema Zero"
description: "Metodi dominio duplicati che coinvolgono il tema Zero - 1 metodo trovato."
document_type: guide
type: guide
category: documentation
status: active
created: "2026-10-06"
updated: "2026-10-08"
qmd: "metodi duplicati - analisi tema zero"
issues: []
discussions: []
---

# Metodi Duplicati — Analisi Tema Zero

_Metodi dominio duplicati che coinvolgono il tema **Zero** — 1 metodo trovato._

## Metodo: `curl_postfields_flatten` (3 occorrenze, 3 in Zero)

- `./laravel/Themes/Zero/extras/add_multiple_contact_ATS_manager_quaeris_it.php`
- `./laravel/Themes/Zero/extras/add_multi_contact_quaerisf3_local.php`
- `./laravel/Themes/Zero/extras/add_multiple_contact_VivaServizi_manager_quaeris_it.php`

[Riflessione: Duplicato interno al modulo App — valutare estrazione in trait di modulo o classe base]

---

## Riflessioni per Zero

Il tema **Zero** è coinvolto in 1 metodo duplicato.

Il metodo `curl_postfields_flatten` è duplicato in 3 file `extras/` tutti interni al tema Zero, senza coinvolgere altri moduli. Si tratta di un duplicato puramente locale.

Si raccomanda di valutare il refactoring dei pattern comuni che interessano questo tema,
preferendo l'estrazione in trait condivisi o servizi centralizzati nel modulo Xot.