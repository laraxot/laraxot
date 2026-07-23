---
title: "PHPStan Modules — round di bug reali da collisione multi-agente — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, multi-agent, collision, second-brain]
---

# PHPStan Modules — round di bug reali da collisione multi-agente — 2026-07-06

## Contesto

Dopo che `./vendor/bin/phpstan analyse Modules` era già stato riportato a 0 errori in un giro precedente (vedi `phpstan-modules-progress-2026-07-06-pm.md`), un secondo run a cache pulita ha rivelato una manciata di **fatal error e bug reali**, non falsi positivi statici — introdotti da modifiche concorrenti di altri agenti che lavoravano sullo stesso albero in parallelo nello stesso arco di tempo.

## Bug trovati e corretti

1. **Namespace corrotto** — `Modules/Gdpr/app/Filament/Resources/TreatmentResource/Tables/TreatmentsTable.php` aveva `namespace Modules\base_quaeris_fila5\var\www\_bases\base_quaeris_fila5\laravel\Modules\Gdpr\...` (un path assoluto di un'altra installazione/progetto — `base_quaeris_fila5` — incollato al posto del namespace corretto). Fatal error "Cannot redeclare class". Corretto in `namespace Modules\Gdpr\Filament\Resources\TreatmentResource\Tables;`.
2. **Funzione globale duplicata** — `typedMock(string $class): MockInterface` dichiarata senza namespace sia in `Modules/Notify/tests/Support/helpers.php` sia in `Modules/User/tests/Support/helpers.php` (il commento in Notify diceva esplicitamente "stesso pattern di User"). Analizzando `Modules/` in un solo processo PHPStan le carica entrambe → fatal "Cannot redeclare function". Risolto con guardia `if (! function_exists('typedMock')) { ... }` su entrambe (già applicato in parallelo da un altro agente quando sono arrivato a controllare).
3. **Merge conflict irrisolto** — `Modules/Gdpr/tests/Feature/ConflictResolutionTest.php` conteneva ancora marcatori `<<<<<<< HEAD` / `=======` / `>>>>>>> 2ae5567 (.)` mai risolti da un merge precedente. Risolto tenendo la variante `Assert::assertInstanceOf(...)`, coerente con lo stile del resto del file.
4. **Narrowing di tipo fittizio** — `Modules/Cms/tests/Feature/HomepageFilamentBlocksArchitectureTest.php` usava un commento `/* @var array<string, mixed> $data */` (singolo asterisco): non è un PHPDoc valido, PHPStan lo ignora silenziosamente. Sostituito con narrowing reale (`Assert::assertIsArray` + loop con `Assert::assertIsString($key)` per garantire anche il tipo delle chiavi).

## Esito

`phpstan analyse Modules --memory-limit=-1` a cache pulita (`rm -rf /tmp/phpstan`): **[OK] No errors, 6595 file analizzati**.

## Lezione operativa (da portare nel wiki)

Con più agenti che editano lo stesso albero in parallelo, un run "verde" **non è stabile** finché non lo si riconferma dopo che tutti gli agenti attivi in quel momento hanno smesso di scrivere file. I fatal error da collisione (namespace mangled, funzioni globali duplicate, marcatori di merge non risolti) non li introduce l'analisi statica — li introduce la concorrenza stessa. Prima di dichiarare "0 errori" definitivo in un contesto multi-agente, vale la pena un secondo giro a distanza di qualche minuto.

Vedi anche `docs/wiki/log.md` (voce 2026-07-06) e `second-brain-qmd-cache-bug-2026-07-06.md` per il lavoro parallelo sul second brain nella stessa sessione.

— Claude (`claude-sonnet-5`)
