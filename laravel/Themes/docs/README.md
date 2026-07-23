# Laravel Themes Documentation

This directory routes to the active documentation roots for the Laravel themes in this repository.

## Current Themes

| Theme | Role | Status | Docs |
| --- | --- | --- | --- |
| `Sixteen` | active public-facing theme aligned with Design Comuni and Bootstrap Italia parity work | active | [README](../Sixteen/docs/README.md) |
| `TwentyOne` | alternative theme with Vite and Tailwind-based frontoffice work | available | [README](../TwentyOne/docs/README.md) |

## Theme Rules

- The active documentation home for a theme is `laravel/Themes/<Theme>/docs/README.md`.
- Never create `lang/lang/` or `_docs/` in a theme; see [directory-structure-rules.md](./directory-structure-rules.md).
- Keep root theme indexes short; they should route to the theme, not duplicate the theme docs.
- Treat generated screenshots, vendor docs, and `node_modules/**/docs` as supporting material, not as canonical repository guidance.
- Use project-level rules from [docs/project/docs-governance.md](../../../docs/project/docs-governance.md).

## Runtime Context

- Web document root: `public_html/`
- Application code: `laravel/`
- Theme assets are published under `public_html/themes/<Theme>/`

## Fast Paths

- Active theme docs: [Sixteen](../Sixteen/docs/README.md)
- Alternative theme docs: [TwentyOne](../TwentyOne/docs/README.md)
- Module index: [laravel/Modules/docs/README.md](../../Modules/docs/README.md)
- LLM wiki workflow: [docs/wiki/README.md](../../../docs/wiki/README.md)
- Themes note: [llm-wiki.md](./llm-wiki.md)
- Shared project docs: [docs/README.md](../../../docs/README.md)
- Agent rules: [AGENTS.md](../../../AGENTS.md)
- Ridondanze documentazione temi: [ridondanze-documentazione-temi.md](./ridondanze-documentazione-temi.md)

## Documentation (hub — non duplicare nei temi)

- [On-Demand Pattern](./ON-DEMAND-PATTERN.md) — Pattern per caricamento efficiente
- [QMD Setup](./QMD-SETUP.md) — Configurazione ricerca locale
- [Performance](./PERFORMANCE-OPTIMIZATION.md) — Metriche e best practice
- [Project Structure](./PROJECT-STRUCTURE.md) — Directory layout
