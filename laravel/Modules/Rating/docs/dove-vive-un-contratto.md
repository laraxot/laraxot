---
title: "Dove vive un contratto: app/Models/Contracts/ o app/Contracts/"
description: "Un contratto che solo un model puo' implementare sta in app/Models/Contracts/. Non e' una preferenza: e' gia' deciso dal contratto stesso, che lo dichiara con @phpstan-require-extends Model."
type: concept
document_type: concept
category: architecture
status: stable
version: 1.0.0
language: it-IT
module: Rating
scope: [repository, modules]
audience: [ai-agents, developers, architects]
tags: [contratti, namespace, convenzione, models, phpstan-require-extends, duplicazione]
created: 2026-09-09
updated: 2026-09-09
qmd: "contratto contract app/Models/Contracts app/Contracts require-extends Model dove vive namespace convenzione RatingContract RatingsFormCallerContract duplicato"
issues:
  - "https://github.com/provtv/module_rating_fila5/issues/27"
discussions:
  - "https://github.com/provtv/module_rating_fila5/discussions/28"
related:
  - ../app/Models/Contracts/RatingContract.php
  - ../app/Contracts/RatingsFormCallerContract.php
  - ./criteri-a-scelta-multipla.md
  - ./stories/5.92.get-ratings-form-schema-nel-trait.story.md
maintainer: Laraxot
license: project-internal
---

# Dove vive un contratto

## La regola, in una riga

**Un contratto che solo un model puo' implementare sta in `app/Models/Contracts/`.
Tutti gli altri in `app/Contracts/`.**

## Il perche' — e non e' l'estetica della cartella

Il contratto **dichiara da se'** dove va. Se porta

```php
@phpstan-require-extends Model
```

allora nessuna classe che non sia un model puo' implementarlo: la domanda «dove lo metto»
e' gia' chiusa dal contratto, e metterlo altrove significa scrivere una cosa e archiviarla
come un'altra. Non serve una convenzione da ricordare — serve leggere il file.

Il contrario vale ugualmente: `RatingsFormCallerContract` vive in `app/Contracts/` perche'
chi lo implementa e' una **Page di Filament**, non un model. Metterlo sotto `Models/`
direbbe al lettore di cercare un model che non esiste.

## La logica

Il namespace rispecchia la cartella, e la cartella e' la prima cosa che un umano legge di
un file. `Modules\Rating\Models\Contracts\RatingContract` dice tre cose prima ancora di
aprire il file: e' del modulo Rating, riguarda i model, e' un contratto. Se la seconda e'
falsa, il nome mente — e un nome che mente costa piu' di un file nel posto sbagliato,
perche' viene copiato.

## La convenzione, misurata (2026-09-09)

| | |
|---|---:|
| contratti in `app/Models/Contracts/` | 9 |
| contratti in `app/Contracts/` | 74 |
| **stesso nome in tutte e due le cartelle** | **3** |
| in `app/Contracts/` ma con `@phpstan-require-extends` | 39 |

I nove sotto `Models/Contracts/` sono la convenzione applicata:
`SchedaContract`, `CriteriEsclusioneContract`, `StabiDirigenteContract` (Ptv),
`EnteMatrFieldsContract`, `DateRangeFieldsContract` (Sigma),
`HasRatingContract`, `RatingContract` (Rating),
`HasTranslationsContract` (Lang), `ActivityRecorderContract` (Activity).

### I tre duplicati sono difetti veri, e due sono nello stesso modulo

```
Modules/Activity/app/Contracts/ActivityRecorderContract.php
Modules/Activity/app/Models/Contracts/ActivityRecorderContract.php

Modules/Ptv/app/Contracts/StabiDirigenteContract.php
Modules/Ptv/app/Models/Contracts/StabiDirigenteContract.php
```

Due interfacce con lo stesso nome nello stesso modulo: chi importa sceglie a caso, e le due
divergono. Vanno unificate, ognuna nella story del proprio modulo.

Il terzo — `Ptv\Models\Contracts\SchedaContract` contro `Sigma\Contracts\SchedaContract` —
sono moduli diversi e potrebbero essere due concetti diversi: va guardato, non risolto per
analogia.

### I 39 sono debito che NON si sana con uno script

`app/Contracts/` contiene 39 file con `@phpstan-require-extends`, e fra questi c'e'
`Xot\Contracts\UserContract`, che ha centinaia di consumatori in tutto il monorepo.
Spostarli sarebbe un rename di namespace di massa: costo enorme, guadagno nullo, e il repo
vieta le riscritture di massa proprio per questo.

**La regola vale per i contratti nuovi.** I 39 restano dove sono finche' qualcuno non
tocca quel file per altri motivi.

## La filosofia

Un contratto e' una **promessa ristretta**: dice il minimo che serve a chi chiama, e nasconde
tutto il resto. `RatingContract` esiste perche' sei moduli hanno la loro `Models\Rating`, su
**connessioni diverse**: chi costruisce un campo di form non deve sapere quale ha in mano.

Da questo discende dove vive: se la promessa e' «sono un model con queste proprieta'», la
promessa e' *sui model*, e sta con loro.

## La politica

Chi crea un contratto decide per tutti quelli che lo importeranno. Quindi:

- **si cerca prima** se esiste gia' — il 2026-09-09 due sessioni hanno creato due
  `RatingContract` a 52 secondi di distanza, uno in ciascuna cartella;
- **si fonde, non si scarta**: quello «perdente» aveva due cose vere che all'altro
  mancavano (`getLabel()` e l'estensione di `HasRecursiveRelationshipsContract`), ed e'
  finito nel contratto unico;
- **si dichiara nella story** del modulo owner, con issue e discussion nella repo del file.

## La religione

`@phpstan-require-extends Model` non e' un'annotazione decorativa: e' la **prova** che il
contratto e' di un model. Se c'e', la cartella e' `Models/Contracts/`. Se non c'e' ed e'
impossibile implementarlo senza essere un model, l'annotazione manca e va aggiunta — non e'
un caso limite, e' un contratto scritto male.

## Lo zen

La cartella giusta e' quella dove chi cerca guarda per primo. Non si decide chiedendosi
«dove lo metto», ma «dove lo cerchera' chi non sa che esiste».

## Come si controlla

```bash
cd laravel
# contratti di model finiti nella cartella sbagliata
grep -rln 'require-extends' --include='*.php' Modules/*/app/Contracts/

# stesso nome nelle due cartelle, dentro lo stesso modulo
for m in Modules/*/; do
  comm -12 \
    <(ls "$m"app/Contracts/*.php 2>/dev/null | xargs -r -n1 basename | sort) \
    <(ls "$m"app/Models/Contracts/*.php 2>/dev/null | xargs -r -n1 basename | sort)
done
```
