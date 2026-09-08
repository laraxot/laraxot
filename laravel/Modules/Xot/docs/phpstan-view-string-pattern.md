---
title: "PHPStan view-string — pattern Filament"
module: "xot"
type: reference
status: approved
tags: [phpstan, view-string, filament, larastan]
created: 2026-08-19
updated: 2026-08-19
qmd: "phpstan view-string filament ViewColumn View make property defaultValue media namespace constructor LoginWidget"
related:
  - "./actions/arr-namespace-convention.md"
  - "../../../../docs/chat/quality-gates-esecuzione-e-correzioni.md"
---

# PHPStan `view-string` — pattern Filament

Larastan tipizza `$view` e `View::make()` con `view-string`: literal noti dalle blade
scansionate. Viste `xot::`/`notify::` spesso passano; viste `media::` o moduli nuovi no.

## ViewColumn / View::make (argomento)

```php
/** @var view-string $eventPropertiesView */
$eventPropertiesView = 'activity::filament.tables.columns.event-properties';

ViewColumn::make('event_properties')->view($eventPropertiesView);
```

Stesso pattern in `MailTemplateForm`: variabile `$paramsBadgesView` prima di `View::make()`.

## Proprietà `$view` su Widget

**Se il literal è nella union Larastan** — docblock inline:

```php
/** @var view-string */
protected string $view = 'xot::filament.widgets.env';
```

**Se il literal NON è nella union** (es. `media::`) — costruttore:

```php
public function __construct()
{
    /** @var view-string $view */
    $view = 'media::filament.widgets.convert';
    $this->view = $view;

    parent::__construct();
}
```

Vedi `LoginWidget`, `ConvertWidget`.

## Entry Infolist

Per viste modulo non scansionate: **non** annotare `@var view-string` sul default —
restare su `protected string $view = 'media::...'` come `FileContentEntry`.

## Cosa non fare

- `@phpstan-ignore` per silenziare
- cast `(view-string)` artificiale
- allargare il tipo a `string` sul metodo Filament
