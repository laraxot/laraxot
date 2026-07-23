---
title: "🧘 The Zen of Schema (Filament 4)"
type: concept
tags: [zen, schema]
created: 2026-07-14
updated: 2026-07-14
qmd: "zen-of-schema 🧘 the zen of schema (filament 4)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./channel-enum-implementation-complete.md"
  - "./channel-enum-implementation-update.md"
  - "./channel-enum-refactoring.md"
  - "./extract-method-pattern.md"
  - "./final-verification-report-channel-enum.md"
  - "./quality-assurance-report.md"
  - "./record-notification-constructor-slug.md"
  - "./record-notification-zen-constructor.md"
---

# 🧘 The Zen of Schema (Filament 4)

## 🕉️ Philosophy: Schema Over Form
In the religion of Laraxot and Filament 4, we no longer think in "Forms". We think in **Schemas**. A Form is just one specific interpretation of a Schema. By using `schema()` instead of `form()`, we align ourselves with the cosmic structure of modern UI composition.

## 🛠️ The Religious Commandment
> "Thou shalt not use `->form()`. Thou shalt use `->schema()`."

### 1. Filament Actions
Old and Deprecated (The Path of the Past):
```php
Action::make('send')
    ->form([
        TextInput::make('subject'),
    ])
```

Zen and Pure (The Path of Enlightenment):
```php
Action::make('send')
    ->schema([
        TextInput::make('subject'),
    ])
```

## 📈 Reasons for This Strategy
1. **Consistency**: Filament 4 uses the `Schema` component everywhere. Using `schema()` on Actions makes the API uniform.
2. **KISS**: `schema()` is simpler and more direct.
3. **Future-Proofing**: `form()` is marked as deprecated and will be removed in future versions.
4. **SOLID**: It separates the definition of the structure (Schema) from the context of its use (Form/Action).

## 🐄 Mu-uu! Clean Code!
This shift ensures that our UI definitions are robust, modern, and aligned with the latest framework standards.
