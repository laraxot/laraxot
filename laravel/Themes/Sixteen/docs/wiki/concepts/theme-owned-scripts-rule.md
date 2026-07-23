# Theme-Owned Scripts Rule

## ⚖️ Decision: Localized Inspection Scripts

Inspection scripts (e.g., Playwright `.mjs` files) that verify UI, CSS parity, or theme-specific components MUST reside within the theme directory.

- **Location:** `laravel/Themes/Sixteen/scripts/`

## 🧠 Philosophy: Gravity of Code

- **Modularization:** Scripts that depend on the DOM structure of a specific theme are part of that theme's ecosystem.
- **Vision:** Tools should live as close as possible to the code they manipulate or verify.
- **Politics:** Modules should be self-contained. A Theme is a module for the front-office.
- **Zen:** When the theme is moved, its tests and inspection scripts move with it. Harmony is maintained.

## 🚀 Purpose
To prevent bloating the root `bashscripts` folder with scripts that are only applicable to a single UI implementation.

## Security rule: no hardcoded credentials

- Admin credentials for diagnostics MUST be loaded from `laravel/.env`.
- Never commit `email/password` literals in scripts.
- Accepted env keys (ordered fallback):
  - `FIXCITY_ADMIN_EMAIL`, `ADMIN_EMAIL`, `FILAMENT_ADMIN_EMAIL`
  - `FIXCITY_ADMIN_PASSWORD`, `ADMIN_PASSWORD`, `FILAMENT_ADMIN_PASSWORD`
- If missing, scripts must fail explicitly (no silent defaults).

## Placement rule for python diagnostics

- Python diagnostics for Sixteen map checks belong to:
  - `laravel/Themes/Sixteen/scripts/`
- Do not create new root-level `scripts` or `bashscripts`.
- Reuse existing script directories only.

## Reference implementation

- `../../scripts/inspect-fixcity-admin-ticket-create-map.cjs`
- `../../scripts/map_diagnostic.py`

## 🧘 The Zen of Script Placement
*The tool and the target are one.*
