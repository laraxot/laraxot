# Tooling UI/AI on demand nel tema Sixteen

## Boundary

**Stack:** Tailwind + Alpine + Lit + DaisyUI + Flowbite + Filament v5 (+ Vite). Design Comuni parity. MCP/skill = strumenti agente, non sostituzione stack.

Memoria: [frontend-stack-religion-standing.md](../memories/frontend-stack-religion-standing.md)

## Stato verificato (2026-06-03)

| Strumento | Uso Sixteen | Verifica |
|---|---|---|
| **frontend-design** (Anthropic plugin, adattato) | Design Thinking + anti AI-slop; overlay PA parity Fixcity | `bashscripts/ai/skills/frontend-design/SKILL.md`, [frontend-design-fixcity-overlay.md](./frontend-design-fixcity-overlay.md) |
| Impeccable | audit/polish CSS, `/it` HTML | `.cursor/skills/impeccable`, detect OK |
| Playwright MCP | verifica pagine Folio | v0.0.75 config `.mcp.json` |
| UI UX Pro Max | design system parity | uipro 2.2.3, script OK |
| Flowbite MCP | esempi on demand | help OK, no runtime Flowbite |
| daisyUI Blueprint | — | licenza mancante |
| Windframe / Tailkit | — | OAuth/licenza mancanti |
| Tailwind MCP | — | Pinterest, non pertinente |
| Laravel Boost | backend/routes | `boost:mcp --help` OK |
| Validation gate (MAUVE / PSI / GSC) | post-FO quality | [STORY-137](../../../../../../docs/stories/STORY-137-mcp-validation-mauve-pagespeed-gsc.md), [mcp-validation-fo-pages](./mcp-validation-fo-pages.md) |

## Workflow

1. Leggere parity Design Comuni e [frontend-design-fixcity-overlay.md](./frontend-design-fixcity-overlay.md) (design thinking + 3 modalità PA/accent/greenfield).
2. Impeccable / UI UX Pro Max per ragionamento UI.
3. Playwright MCP o test per verifica reale.
4. Tradurre proposte in componenti Sixteen esistenti.

## Riferimenti

- Geo (map-lit): `Modules/Geo/docs/wiki/concepts/ui-ai-tooling-on-demand.md`
- Hub: `docs/wiki/concepts/ui-ai-tooling-on-demand-matrix.md`
- Vite map-lit: `docs/wiki/concepts/map-lit-vite-build-troubleshooting.md` (Sixteen wiki)
