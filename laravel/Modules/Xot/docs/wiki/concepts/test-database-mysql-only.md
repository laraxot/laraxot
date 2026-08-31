---
title: test database mysql only
description: Perché i test Laraxot usano MySQL repliche *_test e non SQLite come motore sostitutivo.
document_type: concept
module: Xot
status: active
language: it-IT
updated_at: 2026-08-19
related:
  - ../../../../../../docs/bmad/stories/3.5.xot-mysql-test-engine.story.md
  - ../../../../../../bmad-output/architecture.md
  - ./phpstan-pest-bridge-discipline.md
  - ../../../tests/XotBasePest.php
tags: [pest, mysql, testing, database]
---

# Test database — MySQL only (ADR-013)

## Religione

I test **con database** usano **MySQL** (repliche `*_test`), stesso dialetto di `.env` / `.env.testing`. **SQLite non sostituisce** MySQL in `XotBaseTestCase`.

Fonte: header [`XotBasePest.php`](../../../tests/XotBasePest.php).

## Perché

- Query e tipi colonne differiscono tra sqlite e mysql
- Connessioni **nominate** (`ptv`, `sigma`, `notify`, …) non sono tutte mappabili su un file sqlite con schema completo
- Remap sqlite in `refreshApplication()` produce `no such table` su suite reali

## Ambiente irraggiungibile

Se `DB_HOST` non risponde:

```bash
nc -z -w3 "$(grep -m1 '^DB_HOST=' .env.testing | cut -d= -f2)" 3306
```

Pest **exit 3** è corretto — blocco d'ambiente, non bug test.

## Coverage senza DB

Quando MySQL è down, misurare coverage con unit test su:

- Enum, DataObject, Action pura, Policy, schema Filament statico

Pattern: [`Modules/Rating/phpunit.xml`](../../Rating/phpunit.xml) + story 3.1.

## Story implementazione

[`docs/bmad/stories/3.5.xot-mysql-test-engine.story.md`](../../../../../../docs/bmad/stories/3.5.xot-mysql-test-engine.story.md)

## Collegamenti

- [pest-eseguibile-offline-sqlite.md](../../../../../../docs/chat/pest-eseguibile-offline-sqlite.md)
- [Architect handoff](../../../../../../docs/chat/architect-handoff-quality-testing-2026-08-19.md)
