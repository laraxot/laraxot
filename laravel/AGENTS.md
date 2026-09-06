# AGENTS.md — Laravel On-Demand Stub

## CRITICAL RULES (always respect)

### BMAD Workflow (MANDATORY)
- **ALWAYS**: `bmad create story` BEFORE any development
- **ALWAYS**: validate story with other AI agents
- **ALWAYS**: coordinate with AI agents for who does the dev
- **ALWAYS**: pass through bmad for every activity
- GitHub comments: sign with agent and model (es. `Codex - GPT-5`)

### Git Flow (each module is independent repo)
- **BEFORE** working on a module: `git fetch laraxot dev && git merge laraxot/dev --allow-unrelated-histories -s resolve`
- **NEVER** push with `--force`
- **ONLY forward**: no revert, rollback, checkout, reset
- **Study old versions**: use `git show HEAD:path/to/file`
- **Flow**: `git add -A` → `git commit <msg>` → `git fetch laraxot dev` → `git merge laraxot/dev --allow-unrelated-histories -s resolve` → `git push -u`

### PHPStan Rules
- **NEVER modify phpstan.neon** (only user can)
- **mixed** → always replace with more specific type
- **User model** → `UserContract` if contract needed, OR `XotData::getUserClass()` / `XotData::make()->getUserModel()`
- **Profile model** → `XotData::getProfileClass()` / `XotData::make()->getProfileModel()`

### Code Quality (post-edit)
After every edit: `phpstan` + `phpmd` (./tools) + `phpinsights` (./tools) + `pest`

### Composer/Packages
- **ALWAYS latest version**: never downgrade packages
- Check GitHub/Packagist for latest version before any action
- Update constraint to `^X.Y.Z` (allows patch updates)

### Docs
- **CONSTANTLY** study, update and improve docs/ in modules and themes
- **CONSTANTLY** update second brain + BMAD
- Update `Modules/<Name>/docs/stories/` with BMAD stories
- Create backlinks between docs

### Laravel Specific
- **XotBase / LangBase** — never extend Filament directly
- **XotBaseResourceTable** extends **HasXotTable** → who extends XotBaseResourceTable must NOT redefine `table()` (provided by HasXotTable.php:220)
- `declare(strict_types=1);` on every file
- Use Pest `it()` — no PHPUnit classes
- **NO RefreshDatabase** → use `.env.testing` SQLite
- **NO Controllers FO** → use Folio + Volt + Laraxot
- **NO direct Filament extension** → use XotBase / LangBase
- Module dependencies → `Modules/*/composer.json`

## Read First (on-demand)
1. `docs/wiki/rules/00-TRIGGER_MAP.md`
2. `bashscripts/docs/llm-wiki-qmd.sh search "<topic>" -n 5`
3. Module owner: `Modules/<Name>/docs/wiki/`
4. [Bootstrap compatto](docs/wiki/concepts/agent-bootstrap-compact.md)

## Commands (from `laravel/`)
```bash
./vendor/bin/phpstan analyse Modules --memory-limit=8G
./tools/phpmd.sh <file> text phpmd.xml
./vendor/bin/phpinsights analyse <file> --no-interaction
./vendor/bin/pest <tests>
```

## BMAD Story Template
```markdown
# BMAD Story — <Title>

## Understand
- Problem/requirement
- Applicable rules

## Plan
1. Step 1
2. Step 2

## Implement
- Action 1
- Action 2

## Verify
- phpstan
- phpmd
- phpinsights
- pest

## Document
- docs/ to update
- backlinks

## Status
- Branch: dev
- Module: <module>
- Next: <next step>
```

## PHPStan Quick Fixes

### Cannot cast mixed to string/int
```php
// BAD
$value = (string) $array['key'];

// GOOD
$value = is_string($array['key'] ?? null) ? $array['key'] : '';
$value = is_int($array['key'] ?? null) ? $array['key'] : 0;
```

### Pest internal class errors (tests/Feature/)
```php
// BAD
expect($x)->toBe($y)

// GOOD
Assert::assertSame($y, $x);
Assert::assertStringContainsString($needle, $haystack);
```

### Offset access array|null
```php
// BAD
$value = $array[$key];

// GOOD
$value = $array[$key] ?? default;
```
