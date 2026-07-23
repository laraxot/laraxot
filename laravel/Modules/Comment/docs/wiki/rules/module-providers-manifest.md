---
title: "Rule: Module providers manifest (canon root)"
type: rule
module: Comment
tags: [nwidart, module, service-provider, symlink]
created: 2026-06-06
updated: 2026-06-06
qmd: "Comment module providers manifest module.json composer.json rule"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/296"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../concepts/module-providers-manifest.md
  - ../../../../../../docs/wiki/rules/module-providers-manifest.md
---

# Provider modulo — canon locale

**SSoT concetto:** [module-providers-manifest.md](../concepts/module-providers-manifest.md)

**SSoT regola root:** [docs/wiki/rules/module-providers-manifest.md](../../../../../../docs/wiki/rules/module-providers-manifest.md)

**Verifica Comment:**

```bash
bashscripts/tools/audit-module-provider-manifest.sh Comment
```
