---
title: "Pattern view() — Variabile view-string + Parametri Espliciti"
type: pattern
tags: [view, pattern]
created: 2026-07-14
updated: 2026-07-14
qmd: "view-pattern pattern view() — variabile view-string + parametri espliciti"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Pattern view() — Variabile view-string + Parametri Espliciti

**Regola**: Per ogni chiamata a view() usare variabile tipizzata view-string e parametri espliciti.

## Pattern Preferito

```php
/** @phpstan-var view-string $viewName */
$viewName = 'pub_theme::components.layouts.guest';
$viewParams = [];

return view($viewName, $viewParams);
```

## Logica

1. **Type safety**: view-string è più preciso di (string) — PHPStan sa che è un path Blade valido
2. **Estensibilità**: Stessa struttura con 0 o N parametri — aggiungere dati = popolare $viewParams
3. **Consistenza**: Sempre view($viewName, $viewParams) — nessun default implicito
4. **Manutenibilità**: View name e params in un blocco, facile modificare

## Con parametri

```php
/** @phpstan-var view-string $viewName */
$viewName = 'fixcity::components.blocks.ticket-list';
$viewParams = [
    'tickets' => $this->tickets,
    'status' => $this->status,
];

return view($viewName, $viewParams);
```

## Da evitare

```php
// ❌ Stringa inline, parametri impliciti
return view('pub_theme::components.layouts.guest');

// ❌ Cast (string) perde semantica view-string
return view((string) $view);
```

## Collegamenti

- [phpstan_critical_rules](../.cursor/rules/phpstan_critical_rules.md)
- [AGENTS.md](../AGENTS.md)
