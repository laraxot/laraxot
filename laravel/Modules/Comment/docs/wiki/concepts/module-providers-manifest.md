---
title: "Module providers — module.json + composer.json (no register manuale)"
type: concept
module: Comment
tags: [nwidart, module, service-provider, module-json, composer-json, dry, kiss]
created: 2026-06-06
updated: 2026-06-06
qmd: "module providers module.json composer.json no manual app register ServiceProvider nwidart laraxot"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/296"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../../../docs/wiki/bmad/architecture-module-providers-manifest.md
  - ../../../../Themes/docs/shared-components/service-provider-aration-zen.md
  - ../../../../../docs/wiki/memories/module-providers-manifest-not-manual-register.md
  - native-comments-architecture.md
---

# Provider modulo — manifest, non `$this->app->register()`

## Regola (religione)

In un modulo **nwidart**, ogni `ServiceProvider` dedicato va dichiarato in:

1. `module.json` → array `providers` (runtime app Laraxot)
2. `composer.json` → `extra.laravel.providers` (package discover / testbench)

**Vietato** nel `*ServiceProvider` principale:

```php
// ❌ ANTI-PATTERN
public function register(): void
{
    parent::register();
    $this->app->register(CommentEngineServiceProvider::class);
}
```

## Perché

| Motivo | Dettaglio |
|--------|-----------|
| **SSoT** | `module.json` è il manifest del modulo — abilitazione, ordine, visibilità |
| **DRY** | Doppia registrazione (manifest + codice) = bug o boot doppio |
| **KISS** | Un provider = una voce nel manifest |
| **Modular monolith** | Il modulo si descrive da solo, senza orchestrazione nascosta |

## Eccezione consentita (XotBase)

`XotBaseServiceProvider` registra **solo** sotto-provider interni fissi del framework modulo:

- `RouteServiceProvider`
- `EventServiceProvider`

Non sono nel `module.json` per convenzione Xot — **non** estendere questo pattern ad altri provider di dominio.

## Esempio Comment (corretto)

```json
// module.json
"providers": [
    "Modules\\Comment\\Providers\\CommentServiceProvider",
    "Modules\\Comment\\Providers\\CommentEngineServiceProvider",
    "Modules\\Comment\\Providers\\Filament\\AdminPanelProvider"
]
```

```php
// CommentServiceProvider.php — solo name + XotBase, niente register() custom
class CommentServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Comment';
}
```

`CommentEngineServiceProvider`: route asset `comment::assets.*`, Livewire (fasi STORY-158).

## Checklist nuovo provider

- [ ] Classe in `app/Providers/`
- [ ] Aggiunto a `module.json` `providers`
- [ ] Aggiunto a `composer.json` `extra.laravel.providers`
- [ ] **Non** `$this->app->register()` nel provider padre
- [ ] Doc one-liner in `docs/wiki/log.md`

## Backlink

- Canon User: [service-provider-architecture.md](../../../User/docs/service-provider-architecture.md)
- Zen: [service-provider-aration-zen.md](../../../../Themes/docs/shared-components/service-provider-aration-zen.md)
