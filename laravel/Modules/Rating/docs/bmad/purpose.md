---
title: "Rating: scopo del modulo e come raggiungerlo meglio"
type: concept
status: active
created: 2026-10-06
tags: [rating, purpose, criteri, valutazione, polimorfici, morph]
qmd: "rating scopo modulo criteri valutazione polimorfici morphs rating_morph hasratings"
updated: 2026-10-06
issues:
  - "https://github.com/laraxot/module_rating_fila5/issues/"
discussions:
  - "https://github.com/laraxot/module_rating_fila5/discussions/"
---

# Rating: perche' esiste

## Lo scopo in una frase

**Rating fornisce un sistema polimorfico di criteri di valutazione condiviso fra moduli diversi (schede, indennitá, progressioni) tramite `HasRatingsTrait`: ogni scheda raccoglie punteggi e note secondo un criterio diverso, senza duplicare il codice.**

## L'evidenza

- `RatingMorphTrait`, `HasRatingsTrait`: relazione polimorfca su `ratings` e `rating_morph`
- 34 Action, 15 Widget, 412 file di documentazione
- Ogni modulo che valuta (Performance, Progressioni, IndennitaResponsabilita) usa Rating tramite trait

## Confini: cosa **non** appartiene a Rating

- Il **calcolo della performance**: dominio di Performance
- La **valutazione della progressione**: dominio di Progressioni
- Le **classi base Filament**: Xot

## Il rischio strutturale del polimorfismo

Una relazione morph non ha vincoli di integrita' referenziale: il database non puo'
garantire che `ratable_id` punti a qualcosa che esiste. Se l'entita' valutata viene
cancellata, il punteggio resta orfano.

## Come raggiungerlo **meglio**

### 1. PHPStan Level 10 compliance (2026-10-06)

**Status:** 10 errori fixati in commit `bfeac9d2f1`. Fix applicati a:
- Type hints su Builder generici
- Trans() cast guards
- Migration method fixes (json() vs schemalessAttributes())
- Scope return types

Prossimi passi: test coverage + async export validation.

### 2. Serve un controllo di consistenza per gli orfani

Una action che elenchi i `RatingMorph` il cui bersaglio non esiste piu'.

### 3. La scala va dichiarata, non dedotta

Attributi di scala (minimo, massimo, passo) validati in scrittura.

### 4. Un punteggio che cambia deve lasciare traccia

I punteggi che alimentano un provvedimento sono immutabili.

### 5. `BaseRating` esiste: va usato come contratto

Le classi base indicano un punto di estensione previsto.

## Collegamenti

- `docs/wiki/rules/`: convenzioni e pattern
- `docs/bmad/stories/rating-phpstan-l10-swarm.story.md`: audit swarm
