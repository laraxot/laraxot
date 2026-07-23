---
title: "Gestione coerente di roles, permissions e guard_name"
type: concept
tags: [roles, permissions]
created: 2026-07-14
updated: 2026-07-14
qmd: "roles-permissions-1 gestione coerente di roles, permissions e guard_name"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

# Gestione coerente di roles, permissions e guard_name

## Regola fondamentale
Tutti i ruoli e permessi devono avere `guard_name = 'web'` (o quello specificato dal modello utente). Il modello utente deve dichiarare esplicitamente:
```php
protected $guard_name = 'web';
```

## Motivazione
La coerenza del guard_name è essenziale per evitare errori di runtime, escalation di privilegi e problemi di sicurezza (es. `GuardDoesNotMatch`).

## Esempio pratico
- Tabella `roles` e `permissions`: tutti i record devono avere `guard_name = 'web'`.
- Modello utente:
```php
class BaseUser extends Authenticatable
{
    use HasRoles;
    protected $guard_name = 'web';
    // ...
}
```

## Query SQL di correzione
```sql
UPDATE roles SET guard_name = 'web' WHERE guard_name = '' OR guard_name IS NULL;
UPDATE permissions SET guard_name = 'web' WHERE guard_name = '' OR guard_name IS NULL;
```

## Collegamento regole generali
Vedi anche: ../../Xot/docs/roles-permissions.md
