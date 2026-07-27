---
title: "SupportedLocale label mancante + hardcoded table name errato in test"
type: bugfix
module: Rating
tags: [phpstan, pest, translation, trait, enum, table-name]
created: 2026-07-27
updated: 2026-07-27
related:
  - ../../Xot/docs/testing/pest-globally-blocked-sqlite-connection-collision.md
  - ../../../../docs/chat/phpstan-modules-zero.md
---

# Rating — 3 fix in questa sessione

## 1. `HasRating` — trait.unused (PHPStan)

`Modules/Rating/app/Models/Traits/HasRating.php` è un trait libreria pensato
per essere composto in modelli di **altri** moduli (`use HasRating;`), ma
nessun modello nello scope analizzato lo consuma attualmente. Pattern vietato:
creare un modello/probe finto solo per "usarlo" (vedi
`Modules/Xot/docs/wiki/concepts/phpstan-trait-probes.md` — questa esatta
combinazione, "Probe Rating legacy", è già citata come incidente storico da
non ripetere).

Fix: `/** @phpstan-ignore trait.unused */` sopra `trait HasRating`.

## 2. `SupportedLocale::getLabel()` — traduzione mancante

`Modules/Rating/app/Enums/SupportedLocale.php` usa `EnumTrait` →
`TransTrait::transClass()`, che ritorna deliberatamente `'fix:'.$key` quando
la traduzione non esiste (marcatore di sviluppo intenzionale — vedi
implementazione in `Modules/Xot/app/Filament/Traits/TransTrait.php`, non un
bug). Il test asseriva la vecchia chiave grezza
(`'rating::supported_locale.values.it.label'`), non il comportamento corretto
atteso (una label vera).

Fix:
- Creato `Modules/Rating/lang/it/supported_locale.php` con `values.it.label`
  = "Italiano", `values.en.label` = "Inglese" (stessa struttura minimale già
  usata da `Modules/Rating/lang/it/enums.php` per altri enum del modulo).
- Aggiornato `Modules/Rating/tests/Unit/RatingTest.php` per asserire il valore
  tradotto reale (`'Italiano'`), non la chiave grezza.

Nota: solo `it/` esiste in questo modulo (nessun `en/`), coerente con il resto
del progetto (italiano primario).

## 3. Test con nome tabella hardcoded errato (`rating_morphs` vs `rating_morph`)

`RatingTest.php` (`can create rating morph`) faceva:
```php
DB::connection('rating')->table('rating_morphs')  // ❌ plurale, non esiste
```
Il modello `RatingMorph::getTable()` ritorna `'rating_morph'` (singolare) —
verificato via tinker (`(new RatingMorph())->getTable()`), la tabella
singolare esiste davvero sulla connessione `rating`. La stringa `'rating_morphs'`
nel test era una stringa letterale scollegata dal modello reale, mai
aggiornata quando il nome tabella è stato deciso/cambiato.

Per l'istruzione "se il test cerca qualcosa che non esiste, correggi il test,
non creare la cosa mancante": **non** creata una tabella `rating_morphs` fake.
Fix: il test ora usa `(new RatingMorph())->getTable()` invece di una stringa
hardcoded, cosi' non puo' più andare fuori sincrono col modello.

## Verifica

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Rating --memory-limit=-1   # 0 errori
bash tools/phpmd.sh Modules/Rating/tests/Unit/RatingTest.php text cleancode,codesize,design   # pulito
bash tools/phpmd.sh Modules/Rating/lang/it/supported_locale.php text cleancode,codesize,design  # pulito
./vendor/bin/pest Modules/Rating/tests/Unit/RatingTest.php   # 3 passed
```

PHPInsights segnala solo debito architetturale preesistente su `HasRating.php`
(uso di trait vietato dalla policy generale, complessità, Yoda comparisons,
riga lunga con SVG inline) — non introdotto da questi fix, non toccato
(refactor strutturale separato, fuori scope).

## Collegamenti

Il primo tentativo di eseguire Pest per verificare questi fix ha rivelato un
blocco **globale** di Pest per l'intero progetto (non specifico a Rating) —
vedi `Modules/Xot/docs/testing/pest-globally-blocked-sqlite-connection-collision.md`.
