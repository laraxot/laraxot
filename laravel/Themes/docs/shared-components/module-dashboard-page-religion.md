---
title: "Moduli Laraxot — app/Filament/Pages/Dashboard.php"
type: concept
tags: [theme, module, filament, panel, dashboard, cross-reference]
updated: 2026-07-27
related:
  - ../../../Modules/Xot/docs/wiki/concepts/module-dashboard-page-mandatory.md
  - ./module-admin-panel-provider-religion.md
  - ./module-config-php-religion.md
  - ./runtime-config-religion-hub.md
---

# app/Filament/Pages/Dashboard.php (moduli)

## Perché un tema deve conoscerlo

Wave 1: l'operatore field-service usa i **panel Filament dei moduli** (`/workorder/admin`, …),
non un backoffice nel tema Sixteen. Se `{modulo}/admin` risponde 404 o pagina bianca mentre
le singole Resource funzionano, la causa tipica è **manca `Dashboard.php`** — non un bug CSS del tema.

## Cosa fa

`XotBasePanelProvider` monta ogni modulo su `{modulo}/admin` e scopre le Page in
`app/Filament/Pages/`. Filament usa la classe che estende `Filament\Pages\Dashboard` come
**home del panel**. Pattern minimo:

```php
class Dashboard extends XotBaseDashboard
{
}
```

Mai `Filament\Pages\Dashboard` diretto, mai `XotBasePage` con view vuota.

## Trinità panel (checklist agente)

| File modulo | Scopo |
|-------------|--------|
| `config/config.php` | Nome/icona nel menu |
| `app/Providers/Filament/AdminPanelProvider.php` | Registra il panel |
| `app/Filament/Pages/Dashboard.php` | Landing `{modulo}/admin` |

I **temi** non registrano panel né Dashboard — solo i moduli.

## Audit

```bash
bash bashscripts/tools/audit-module-dashboard-page.sh
```

Canon tecnico: [module-dashboard-page-mandatory.md](../../Modules/Xot/docs/wiki/concepts/module-dashboard-page-mandatory.md)
