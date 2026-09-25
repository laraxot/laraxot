---
title: "Composer update e PHPStan — blocker da merge markers e configurazione"
type: chat-handoff
tags: [composer, phpstan, bmad, second-brain, merge-conflicts]
created: 2026-09-25
updated: 2026-09-25
status: blocked
story: laravel/Modules/Xot/docs/stories/2.1.fix-hasxotfactory-merge-conflict-regression.story.md
---

# Composer update -W + PHPStan Modules — 2026-09-25

## Richiesta

Eseguire `cd laravel && composer update -W`, correggere le segnalazioni e poi
`./vendor/bin/phpstan analyse Modules`.

## Esito verificato

- `composer update -W` risolve le dipendenze (output: 220 installazioni, 4
  aggiornamenti), ma gli script post-autoload non completano `artisan
  package:discover`: dopo la rimozione mirata di diversi marker PHP, il bootstrap
  termina su `Modules/Xot/app/Models/XotBaseModel.php:70` con un marker Git.
- `php artisan package:discover --ansi` riproduce lo stesso blocker.
- `./vendor/bin/phpstan analyse Modules --memory-limit=8G` non avvia l'analisi:
  `laravel/phpstan.neon` non è Neon valido (riga 12, indentazione; `ignoreErrors:`
  è privo di contenuto). Il file era già modificato nel working tree e la policy
  `phpstan-neon-immutable` riserva ogni modifica a una persona umana.
- Restano warning Composer PSR-4 e classi FontLib duplicate; non sono stati
  corretti in massa perché coinvolgono un working tree con oltre 3.000 path già
  modificati e le rinominazioni richiedono audit semantico per modulo.
- Healthcheck/QMD wrapper indicati da `AGENTS.md` non esistono in questo checkout;
  Second Brain consultato manualmente tramite Trigger Map e owner docs.

## Fix applicati

- Risolti marker di conflitto nei file Xot caricati durante Composer/Artisan,
  preservando la variante `HEAD` quando il conflitto era solo formattazione o
  contenuto vuoto; `AdminPanelProvider` ha mantenuto anche la registrazione CSS
  presente nel working tree.
- Ripristinato il contratto generico di `HasXotFactory` secondo la story Xot 2.1
  e implementato `hasPermissionToOrCreate()` nel trait User, come già descritto
  dalla story User del 2026-09-21.
- `php -l` sui file PHP modificati e validazione JSON di `Modules/Xot/composer.json`
  passano.

## Bloccanti per ripresa

1. Un owner deve riparare `laravel/phpstan.neon`; l'agente non è autorizzato a
   modificarlo.
2. La story Xot 2.1 dichiara `XotBaseModel.php` fuori scope e segnala proprio il
   conflitto in quel file come blocker PHPStan. Serve sequenziare la story marker
   sweep/companion prima di richiedere PHPStan repo-wide.
3. Dopo i due prerequisiti, rieseguire Composer e PHPStan e affrontare i warning
   residui uno per volta con le story owner.
