---
title: "Testing in UI"
type: concept
tags: [ui, testing, pest, phpstan, sixteen]
created: 2026-06-05
updated: 2026-06-13
qmd: "UI module testing Pest PHPStan Sixteen components mockService createStub"
issues:
discussions:
related:
  - "./auth-register-focus-loss-overlay.md"
  - "./block-rendering-and-optional-services.md"
  - "./claude-audit-static.md"
  - "./code-redundancy-ui.md"
  - "./context-overflow-prevention.md"
  - "./enum-select-best-practices.md"
  - "./enum-select-component.md"
  - "./enum-select-contract-and-false-friends.md"
---

# Testing in UI

## Pest PHP

`uses(\Modules\UI\Tests\TestCase::class)`. Assert con `PHPUnit\Framework\Assert::assert*` (no catene miste Pest/Assert).

## Test che toccano il tema Sixteen

`ComponentFilesExistTest` valida path sotto `Themes/Sixteen/resources/views/components/`.

Contratto: [theme-component-test-contract](../../../../Themes/Sixteen/docs/wiki/concepts/theme-component-test-contract.md)

## PHPStan (2026-06-13)

| Fix | File |
|-----|------|
| Skip aggregato legacy components | `ComponentFilesExistTest.php` |
| `createStub` + `willReturn(null)` | `BaseCalendarWidgetTest.php` |
| `assertInstanceOf` diretto su `view()` | `ComponentTest.php` |

## Quality gate

```bash
cd laravel
./vendor/bin/pest Modules/UI/tests
./vendor/bin/phpstan analyse Modules/UI
```

## Completamento

- [ ] Copertura widget Filament calendar con action reale (non solo stub)
- [ ] Allineare test enum/select a [enum-select-contract-and-false-friends](./enum-select-contract-and-false-friends.md)
