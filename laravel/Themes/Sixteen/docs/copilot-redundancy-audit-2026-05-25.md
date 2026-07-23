Copilot Redundancy Audit (Theme: Sixteen) — 2026-05-25

Sintesi
- Scan rileva componenti view duplicati e asset/partials replicati in subfolders.
- Alcuni file duplicati sono copie di backup (bootstrap/app.php vs app/bootstrap/app.php).

Raccomandazioni
- Consolidare shared components inside laravel/Themes/docs/shared-components/ and reference them from the theme.
- Replace duplicate blade components with @include of canonical components.
- Add a short "where to find canonical components" doc in Themes docs index.

Autore: Copilot CLI
