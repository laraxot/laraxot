---
title: "PHPStan L10 — pattern fix modulo Media"
type: troubleshooting
sources: ["build/phpstan/by_module_session3/Media.txt"]
confidence: verified
created: 2026-05-06
updated: 2026-07-15
tags: [phpstan, media, ffmpeg, intervention-image, filament]
qmd: "phpstan media l10 merge ffmpeg s3test temporary upload fix pattern"
issues:
  - "https://github.com/laraxot/base_ptvx_fila5/issues/711"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/1"
related:
  - "../../phpstan-ffmpeg-export.md"
  - "../../phpstan-fixes.md"
  - "../../../../docs/chat/phpstan-media-session3-findings.md"
---

# PHPStan L10 — pattern fix modulo Media

## Merge.php — Intervention Image v4

**Sintomo:** `@var` su variabili inesistenti, `method.nonObject` su `save()`/`width()`/`height()`.

**Causa:** API v3 (`read`, `create`, `place`) rimossa in `intervention/image` 4.x; PHPStan inferisce `mixed`.

**Fix:**

```php
$image1 = $manager->decodePath($path1);
$canvas = $manager->createImage($width, $height);
$canvas->insert($image1, 0, 0, Alignment::TOP_LEFT);
```

Per merge multiplo: `/** @var list<ImageInterface> $images */` e variabili assegnate nel loop (`$img`, `$final`).

## ConvertVideoBy*Action — MediaExporter vs PHPFFMpeg

**Sintomo:** `PHPFFMpeg::save()` undefined.

**Causa:** `addFilter()` è inoltrato via `@mixin` + `__call`; il return type del mixin è `PHPFFMpeg`, non `MediaExporter`.

**Fix:** catena fino a `inFormat()`, poi statement separati:

```php
$export = FFMpeg::fromDisk($data->disk)
    ->open($data->file)
    ->export()
    ->onProgress(/* ... */)
    ->inFormat($formatInstance);

$export->addFilter('-preset', 'ultrafast');
$export->save($file_new);
```

Vietato `@phpstan-ignore` se il tipo si recupera spezzando la catena.

## S3Test.php — proprietà `$form`

**Sintomo:** `property.notFound` su `$this->form`.

**Fix:** `XotBasePage` espone `getForm(string $name)`; usare `$this->getForm('form')?->fill()` / `?->getState()` + `Assert::isArray`.

## TemporaryUpload::findByMediaUuid

**Sintomo:** `where()` / `first()` su `mixed`.

**Fix:**

```php
Assert::string($mediaModelClass = config('media-library.media_model'));
Assert::subclassOf($mediaModelClass, Media::class);
/** @var class-string<Media> $mediaModelClass */
$media = $mediaModelClass::query()->where('uuid', $mediaUuid)->first();
```

## Verifica

```bash
cd laravel && php -d memory_limit=2048M ./vendor/bin/phpstan analyse Modules/Media/app --no-progress
```
