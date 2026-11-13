---
title: "Metodi duplicati — Zero"
type: guide
tags: ['theme', 'duplicate-methods']
created: 2026-07-14
updated: 2026-09-17
qmd: "metodi duplicati zero"
related:
  - "./00-index.md"
  - "./duplicate-methods-report.md"
id: zero-docs-bmad-duplicate-methods
slug: duplicate-methods
description: "Analisi sintetica dei metodi PHP con lo stesso nome all'interno di questo tema."
document_type: guide
category: static-analysis
status: active
issues: []
discussions: []
---

# Metodi duplicati — Zero

Analisi sintetica dei metodi PHP con lo stesso nome all'interno di questo tema.

> **Nota 2026-09-17**: questo file consolida `duplicate_methods.md`, `metodi-duplicati-analisi.md`,
> `METODI-DUPLICATI-ANALISI.md`, `METODI_DUPLICATI_ANALISI.md` (tutti contenuto identico o stub di
> redirect) rimossi in questa pass di dedup. Vedi anche `duplicate-methods-report.md` per l'analisi
> cross-modulo/tema più ampia e `wiki/concepts/duplicate-method-bodies.md` per l'analisi sui *corpi*
> di metodo duplicati (angolo diverso: implementazione, non solo nome).

- File PHP analizzati (alla data 2026-06-15): **33**
- Metodi duplicati trovati (alla data 2026-06-15): **1**

## Metodi duplicati (stato storico 2026-06-15)
Analisi sintetica dei metodi PHP con lo stesso nome all’interno di questo ambito.

- File PHP analizzati: **33**
- Metodi duplicati trovati: **1**

## Metodi duplicati
Analisi sintetica dei metodi PHP con lo stesso nome all'interno di questo tema.

> **Nota 2026-09-17**: questo file consolida `duplicate_methods.md`, `metodi-duplicati-analisi.md`,
> `METODI-DUPLICATI-ANALISI.md`, `METODI_DUPLICATI_ANALISI.md` (tutti contenuto identico o stub di
> redirect) rimossi in questa pass di dedup. Vedi anche `duplicate-methods-report.md` per l'analisi
> cross-modulo/tema più ampia e `wiki/concepts/duplicate-method-bodies.md` per l'analisi sui *corpi*
> di metodo duplicati (angolo diverso: implementazione, non solo nome).

- File PHP analizzati (alla data 2026-06-15): **33**
- Metodi duplicati trovati (alla data 2026-06-15): **1**

## Metodi duplicati (stato storico 2026-06-15)

| Metodo | Occorrenze | Note |
|--------|----------|------|
| `curl_postfields_flatten` | 3 | candidato a trait/helper |

> **Verificato 2026-09-17**: la directory `laravel/Themes/Zero/extras/` che conteneva i 3 file con
> `curl_postfields_flatten` **non esiste più** nel codebase attuale (confermato con `grep -r
> curl_postfields_flatten` su tutto il repo: zero risultati). Il finding è quindi **risolto/superato
> dal codice** — i file `extras/*.php` sono stati rimossi o rifattorizzati da allora. Non risultano
> altri metodi duplicati specifici del tema Zero al momento di questa verifica; per un quadro
> aggiornato rieseguire lo script di analisi citato in `duplicate-methods-report.md`.

## Riflessioni

- I duplicati con nomi generici (`__construct`, `up`, `down`, `definition`) sono spesso inevitabili, ma vanno monitorati.
- Quando un metodo compare in più classi con firme simili, conviene valutare un trait o una classe base condivisa.
- Se il metodo ha firme diverse, meglio evitare l'ereditarietà implicita e preferire un service/helper dedicato.
- Per i metodi di tipo accessor/mutator, la duplicazione è spesso legata a pattern Eloquent ricorrenti.

> Documento generato il 2026-06-15 da Claude Code; verificato e aggiornato il 2026-09-17 (docs-improvement pass, E-DOCS-20.Zero).
- Se il metodo ha firme diverse, meglio evitare l’ereditarietà implicita e preferire un service/helper dedicato.
- Per i metodi di tipo accessor/mutator, la duplicazione è spesso legata a pattern Eloquent ricorrenti.

> Documento generato il 2026-06-15 da Claude Code.
- Se il metodo ha firme diverse, meglio evitare l'ereditarietà implicita e preferire un service/helper dedicato.
- Per i metodi di tipo accessor/mutator, la duplicazione è spesso legata a pattern Eloquent ricorrenti.

> Documento generato il 2026-06-15 da Claude Code; verificato e aggiornato il 2026-09-17 (docs-improvement pass, E-DOCS-20.Zero).
