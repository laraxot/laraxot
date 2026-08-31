---
title: "PHPStan: FFMpeg Export save()"
module: "Media"
type: concept
tags: [phpstan, ffmpeg, export]
created: 2026-07-14
updated: 2026-07-15
qmd: "phpstan ffmpeg export save mediaexporter addfilter chain"
issues:
  - "https://github.com/laraxot/base_ptvx_fila5/issues/711"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/1"
related:
  - "./wiki/troubleshooting/phpstan-fixes.md"
  - "./actions/convert-video-by-media-convert.md"
---

# PHPStan: FFMpeg Export save()

## Contesto

`ConvertVideoByConvertDataAction` e `ConvertVideoByMediaConvertAction` usano `ProtoneMedia\LaravelFFMpeg`. PHPStan segnala `PHPFFMpeg::save()` undefined quando `addFilter()` è concatenato nella fluent chain.

## Causa

`MediaExporter` dichiara `@mixin PHPFFMpeg`. `addFilter()` esiste solo sul driver ed è inoltrato con `__call`; il PHPDoc del mixin restituisce `self` come `PHPFFMpeg`, perdendo il tipo `MediaExporter` per `save()`.

## Soluzione (preferita)

Non concatenare `addFilter()` dopo `inFormat()`:

```php
$export = FFMpeg::fromDisk($data->disk)
    ->open($data->file)
    ->export()
    ->onProgress(/* ... */)
    ->inFormat($formatInstance);

$export->addFilter('-preset', 'ultrafast');
$export->save($file_new);
```

`save()` è definito su `MediaExporter` (`pbmedia/laravel-ffmpeg`).

## `ResolveMediaExporterAction::execute(mixed)`

Resta `mixed`: è il type-guard sul ritorno della fluent FFmpeg (`__call` / mixin). Restringere a `MediaExporter` svuoterebbe l'action e sposterebbe `argument.type` sui chiamanti. Stesso bordo di `addFilter()`.

## Vietato

- `@phpstan-ignore` su `save()` se basta spezzare la catena
- Chiamare `save()` sul risultato di `->addFilter()` in un’unica espressione
