---
title: "Specificare il template del contratto Rating"
type: story
module: Rating
epic: quality-gates
story_id: "rating-contract-template-binding"
status: done
track: phpstan-remediation
created: 2026-11-04
updated: 2026-11-04
tags: [phpstan, generics, eloquent, contract]
qmd: "RatingContract HasRecursiveRelationshipsContract TModel generics missingType PHPStan"
references:
  - "../../../app/Models/Contracts/RatingContract.php"
  - "../../../../Xot/docs/bmad/stories/global-phpstan-remediation.story.md"
---

# Specificare il template del contratto Rating

## Scopo

Il contratto Rating resta compatibile con le relazioni ricorsive Eloquent e dichiara il tipo
modello che realizza le relazioni. In questo uso l'interfaccia base è parametrizzata sul
contratto generale `Model`.

## Acceptance criteria

- [x] `RatingContract` lega esplicitamente `HasRecursiveRelationshipsContract` a `Model`.
- [x] La firma resta compatibile con i modelli concreti dei moduli consumer.
- [x] PHPStan globale termina exit 0; nessun suppression è introdotto.

## Causa radice e correzione

PHPStan segnalava `missingType.generics`: `RatingContract` estendeva
`HasRecursiveRelationshipsContract` senza specificare `TModel`. Il docblock ora dichiara
`@extends HasRecursiveRelationshipsContract<Model>`, perché il contratto viene implementato
da modelli Eloquent concreti appartenenti a più moduli.

## Verifica

- `cd laravel && ./vendor/bin/phpstan analyse Modules` → exit 0, 10.400 file analizzati,
  zero errori nel report JSON del gate integrato.
