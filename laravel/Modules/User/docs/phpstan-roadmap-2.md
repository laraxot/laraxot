---
title: "PHPStan Roadmap: User Module"
type: concept
tags: [phpstan, roadmap]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-roadmap-2 phpstan roadmap: user module"
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

# PHPStan Roadmap: User Module

**Date**: 2026-01-12
**Errors**: 13

## 1. Filament Resource Typing
**Files**:
- `app/Filament/Clusters/Passport/Resources/OauthClientResource.php`
- `app/Filament/Resources/ClientResource.php`

**Issue**: `getModel()` returns `string` or `class-string`, but contract expects `class-string<Model>`.
**Plan**: Ensure explicit casting or strict return type in `getModel()`. Verify `XotBaseResource` inheritance.

## 2. API Resource properties
**Files**:
- `app/Http/Resources/ClientResource.php`

**Issue**: Access to undefined property `$this->owner`.
**Plan**: `JsonResource` proxies to the underlying model, but PHPStan doesn't know the model type. Add `@mixin` or `@property` PHPDoc to the Resource class.

## 3. PHPDoc Namespace Issues
**Files**:
- `app/Models/Role.php`
- `app/Models/Traits/HasTeams.php`

**Issue**: Unknown classes in PHPDoc (e.g., `Modules\User\Models\Carbon`).
**Plan**:
- Check imports in `Role.php` and `HasTeams.php`.
- The PHPDoc likely lacks FQCN or correct `use` statements.
- `HasTeams.php`: `pluck()` on unknown class `Collection`. Likely missing `use Illuminate\Support\Collection`.

## Execution Order
1. Fix PHPDoc Namespace issues (Role, HasTeams).
2. Fix API Resource properties.
3. Fix Filament Resource typing.
