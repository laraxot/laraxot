---
title: "architecture testing env"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "architecture testing env"
issues: []
discussions: []
status: draft
---

# Testing Environment

## Regola

Il file `.env.testing` deve sempre esistere nel progetto Laravel.

## Principio

- È identico a `.env`
- Cambiano solo le variabili `DB_DATABASE*`
- Ogni valore DB viene concatenato con `_test`

## Perché

- Isola i test dal database di sviluppo
- Evita contaminazione dei dati
- Permette test ripetibili e sicuri

## Esempio

- `DB_DATABASE=fixcity_data` → `DB_DATABASE=fixcity_data_test`
- `DB_DATABASE_USER=fixcity_user` → `DB_DATABASE_USER=fixcity_user_test`
- `DB_PASSWORD_USER=marco` → `DB_PASSWORD_USER=marco_test`

## Filosofia

La separazione degli ambienti è una forma di disciplina: il test deve essere utile senza essere invasivo.

## Stato locale (verificato)

I database MariaDB `fixcity_user_test` e `fixcity_data_test` devono esistere con
schema allineato ai DB di sviluppo (struttura). Fixture SQLite gemelle sotto
`laravel/database/fixcity_*_test.sqlite`. Story: [test-db-fixcity-user-test-ready.story.md](../../../Modules/Fixcity/docs/stories/test-db-fixcity-user-test-ready.story.md).
