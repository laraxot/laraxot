---
title: "GEMINI Translation Rules"
type: rule
tags: [gemini, translation, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "gemini-translation-rules gemini translation rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# GEMINI Translation Rules

Regole gestione traduzioni.

---

## Regola Critica

Tutti i file di traduzione per i moduli Laraxot (`Modules/{ModuleName}/lang/{locale}/{resource}.php`) DEVONO contenere le seguenti chiavi di primo livello:

### Chiavi Obbligatorie

- `navigation` (con sotto-chiavi: `label`, `plural_label`, `group`, `icon`, `sort`)
- `label`
- `plural_label`
- `fields` (con chiavi per ogni campo)
- `actions` (con chiavi per ogni azione)

### Esempio

```php
// Modules/User/lang/it/filters.php
return [
    'navigation' => [
        'label' => 'Utenti',
        'plural_label' => 'Utenti',
        'group' => 'Gestione',
    ],
    'fields' => [
        'name' => 'Nome',
        'email' => 'Email',
    ],
    // ...
];
```

### Perché Queste Chiavi?

Sono **fondamentali** per:
- Corretto funzionamento Filament
- Consistenza nell'interfaccia utente
- Navigazione del pannello admin

### Rischio

La loro **assenza o modifica non autorizzata** può causare errori e incoerenze.

---

## Translation Pattern

Tutte le traduzioni DEVONO avere **5 livelli**:

```
namespace::context.collection.element.type
```

### Esempio

```blade
✅ __('predict::home.hero.cta_learn.label')
❌ __('predict::fields.key')  // Missing type!
```

### Eccezione

`predict::messages.*` - valore diretto, NO `.label`

---

## 🔗 Link

- [Indice GEMINI](./gemini-split-index.md)
- [translation-management skill available](../../.opencode/skills/translation-management/SKILL.md)
- [gemini.md originale](../../gemini.md)
- [Index principale](./index.md)
