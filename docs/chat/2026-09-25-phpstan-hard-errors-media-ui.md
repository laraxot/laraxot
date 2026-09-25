---
title: "PHPStan — errori non ignorabili Media/UI"
type: chat-handoff
status: completed
created: 2026-09-25
agent: opencode-space-bunny
story: docs/stories/phpstan-modules-perfection-2026-09-20.story.md
---

# PHPStan — errori non ignorabili Media/UI

Correzioni root-cause, senza ignore/baseline e senza modificare `phpstan.neon`:

- `laravel/Modules/Media/app/Filament/Resources/MediaResource/Widgets/ConvertWidget.php`: rimosso `@phpstan-ignore property.defaultValue` non necessario.
- `laravel/Modules/UI/app/Livewire/Components/Map/InteractiveMap.php`: allineati i risultati `Geo` a `list` con `array_values()` per `markers` e `getSuggestions()`; rimossi ignore stale già presenti nel tree.

## Prove

- `php -l` su entrambi i file: OK.
- PHPStan canonico mirato: `[OK] No errors`.
- PHPInsights eseguito; findings residui di stile/complessità preesistenti, nessun nuovo errore PHPStan.
- PHPMD non disponibile nel checkout (PHAR/binario assente).
- Pest Media tentato; bloccato da accesso DB `workorder_data_test` (`SQLSTATE[HY000] [1044]`).

Lock e handoff rilasciati. Nessun commit/push/rollback. Il sync multi-repo resta in carico all'agente esterno; la quiet window finale deve rivalidare questi file.
