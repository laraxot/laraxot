---
title: "Spatie Permission table_names — immutabile (temi)"
type: rule
tags: [theme, permission, spatie, config, shared-components]
updated: 2026-07-27
related:
  - ../../Modules/User/docs/bugfix-permission-table-names-singular.md
  - ./spatie-permission-team-context.md
---

# `config/permission.php` → `table_names` (temi)

I temi non definiscono le tabelle pivot di Spatie Permission, ma spesso
consumano `$user->roles`/`$user->hasRole()` in Blade/Livewire (menu, guardie di
visibilità). Se una query Spatie fallisce con "table doesn't exist"
(`model_has_roles`, `model_has_permissions`, `role_has_permissions`), **non è un
bug del tema** e **non si tocca mai** `laravel/config/permission.php` né i suoi
overlay tenant (`config/{tenant}/permission.php`) per "correggerlo" — quei valori
sono una scelta deliberata dell'utente (verificata via `git show` sul commit
iniziale del progetto).

Il fix è sempre sullo **schema**: la tabella fisica deve essere allineata al
valore già in config, tramite una migrazione il cui `$model_class` punta al
model pivot (`ModelHasRole`, `ModelHasPermission`, `RoleHasPermission`) la cui
`getTable()` legge la config dinamicamente — mai un nome tabella letterale.

Canon completo + cronologia dell'incidente:
[User — bugfix-permission-table-names-singular](../../Modules/User/docs/bugfix-permission-table-names-singular.md)
