# Quality Tools Usage (Theme Sixteen)

Theme-specific guidance for PHPMD, PHP-CS-Fixer, Laravel Pint, Psalm, PHPQA, actionlint. Canonical reference: `Modules/Xot/docs/QUALITY_TOOLS.md`.

## Scope
- Analyze only `Themes/Sixteen` and related public assets.
- Prefer non-destructive runs first; review visual regressions after any change.

## Safe Commands (Report/Dry-Run)
```bash
# PHPMD (limit to PHP in theme helpers/providers if present)
vendor/bin/phpmd Themes/Sixteen text cleancode,codesize,design,naming,unusedcode --ignore-violations-on-exit --suffixes php

# Pint (test)
vendor/bin/pint --test --preset laravel --path Themes/Sixteen

# PHP-CS-Fixer (dry-run)
vendor/bin/php-cs-fixer fix Themes/Sixteen --dry-run --diff --using-cache=yes

# Psalm (informational)
vendor/bin/psalm --no-cache --no-diff --show-info=true --paths=Themes/Sixteen

# PHPQA (reports)
vendor/bin/phpqa --analyzedDirs Themes/Sixteen --report --output build/phpqa-sixteen --tools phpmd,phpcs,phpcpd --execution no-ansi
```

## Apply Changes (After Review)
```bash
vendor/bin/pint --path Themes/Sixteen
# or
vendor/bin/php-cs-fixer fix Themes/Sixteen --allow-risky=no
```

## Notes
- After changes, rebuild Vite and visually test key pages/components.
- Keep parity with `Modules/UI` components; update `Themes/Sixteen/docs/components.md`.
- Track suppressions with rationale and revisit dates.
