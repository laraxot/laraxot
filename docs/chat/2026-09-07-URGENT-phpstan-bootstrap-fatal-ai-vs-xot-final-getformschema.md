---
title: "URGENT: PHPStan bootstrap fatal repo-wide — AI/AiActionProposalResource overrides now-final XotBaseResource::getFormSchema()"
type: coordination
tags: [phpstan, blocking, xot, ai, bootstrap, urgent]
created: 2026-09-07T00:00:00Z
agent: claude (sonnet 5) — this session
status: blocking
blocking: true
---

# URGENT: PHPStan crasha per chiunque, non solo per chi tocca AI/Xot

Lanciando `./vendor/bin/phpstan analyse Modules/Signature --no-progress
--memory-limit=-1` (target: solo Signature) si ottiene un **PHP Fatal error**
durante il bootstrap, non un errore di analisi:

```
PHP Fatal error:  Cannot override final method
Modules\Xot\Filament\Resources\XotBaseResource::getFormSchema() in
Modules/AI/app/Filament/Resources/AiActionProposalResource.php on line 23
```

Causa: nel working tree condiviso in questo momento,
`Modules/Xot/app/Filament/Resources/XotBaseResource.php` (modificato, non
committato) ha gia' `getFormSchema()` dichiarato `final` (riga 112), ma
`Modules/AI/app/Filament/Resources/AiActionProposalResource.php` (anch'esso
modificato, non committato, mid-refactor: il file `Schemas/AiActionProposalForm.php`
gemello esiste gia', ma l'override nella Resource non e' stato ancora
rimosso) lo overrida ancora.

Effetto: **qualsiasi `phpstan analyse`, anche scoped a un singolo modulo non
correlato, fatalizza in fase di bootstrap** perche' il manifest dei moduli
carica tutte le Service Provider/Resource. Blocca l'intera campagna
quality-gates per chiunque, non solo per chi lavora su AI o Xot.

Non e' compito mio: non tocco ne' Xot ne' AI (altri agenti hanno WIP live
sugli stessi file, si vede da `git status` che sono entrambi mid-edit sullo
stesso pattern Schemas/Tables). Chi possiede quei due WIP: completare la
rimozione dell'override in AI (o rendere `getFormSchema()` non-final
temporaneamente, ma solo se e' la propria story) sblocca tutti.

Pattern gia' noto in second brain: `project_xot_bootstrap_break_xotbaseresourceform_2026_09_07` —
questa e' una nuova occorrenza specifica (file AI), non identica alla precedente.
