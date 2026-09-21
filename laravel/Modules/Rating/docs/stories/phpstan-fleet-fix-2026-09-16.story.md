---
id: Rating/phpstan-fleet-fix-2026-09-16
title: "PHPStan fleet fix 2026-09-16 (Rating)"
status: done
module: Rating
priority: P1
updated: 2026-09-16
---

# Claim

Sessione base-ptvx-fila5-0d — swarm PHPStan fleet, coordinato con codex-2026-09-16 (bashscripts/docs/bmad/stories/phpstan-fleet-random-swarm-2026-09-16.story.md) e peer base-ptvx-fila5-c5.

# Esito

Errori PHPStan noti (evidence `laravel/build/phpstan-Rating.json`, 5 errori su 2 file):
già risolti nel working tree da un fork precedente di questa stessa sessione — dettaglio
completo, prima/dopo e verifica riga per riga in
`Modules/Rating/docs/bmad/stories/5.157-phpstan-rating-pivot-get-test.story.md` e
`Modules/Rating/docs/coverage.md` (sezione "16 settembre 2026").

- `app/Models/BaseRating.php`: `@property-read BaseRatingMorph $pivot` (narrowing tipizzato,
  no `mixed`, no ignore).
- `tests/Unit/HasRatingsTraitOtherOptionTest.php`: stub `Get` sostituito da classe anonima
  `extends Get` (tipo di ritorno reale, no `MockInterface`).

`cd laravel && ./vendor/bin/phpstan analyse Modules/Rating --no-progress --memory-limit=-1`
→ **`[OK] No errors`** (prima: 5 file-errors). `phpstan.neon` non toccato.

PHPMD (`tools/phpmd.sh Modules/Rating/app`) e PHP Insights
(`tools/phpinsights.sh analyse Modules/Rating/app`): girati, nessun errore nuovo, solo
findings di stile preesistenti fuori scope + un crash PDepend preesistente e non correlato
su `Filament/*/Widgets/StatsOverview.php` (classe anonima/DNF, vedi
`feedback-phpmd-phar-dies-on-dnf-types.md`).

Pest: skip ambientale, `10.100.200.53:3306` irraggiungibile (`nc -z` negativo, run con
timeout 120s senza output — bootstrap pende sul DB invece di fallire subito).
