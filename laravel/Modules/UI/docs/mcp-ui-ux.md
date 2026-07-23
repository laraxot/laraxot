---
title: "MCP Servers per UI/UX - Modulo UI"
type: concept
tags: [mcp]
created: 2026-07-14
updated: 2026-07-14
qmd: "mcp-ui-ux mcp servers per ui/ux - modulo ui"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# MCP Servers per UI/UX - Modulo UI


## Scopo

Questo documento descrive quali MCP server sono configurati per migliorare la qualità della UI/UX nel progetto e come usarli nel contesto del modulo UI.

## MCP Attivi per UI/UX

| MCP Server | Scopo | Comando |
| --- | --- | --- |
| flowbite | Componenti Tailwind CSS, Figma-to-code, theming | `npx -y flowbite-mcp` |
| shadcn | Registry componenti, installazione via prompt | `npx shadcn@latest mcp` |
| daisyui | Blueprint MCP, generazione UI 10x | `npx -y daisyui@latest mcp` |
| mui-mcp | Docs Material UI accurate | `npx -y @mui/mcp@latest` |

## Integrazione con il Modulo UI

Il modulo UI fornisce componenti Blade condivisi in `resources/views/components/ui/`. Quando si generano componenti via MCP:

1. Adattare l'output HTML a componenti Blade
2. Posizionare in `Modules/UI/resources/views/components/ui/`
3. Non usare stringhe hardcoded — usare file di traduzione
4. Seguire le convenzioni Tailwind CSS v4
5. Documentare ogni nuovo componente in `Modules/UI/docs/`

## Workflow consigliato

1. Generare componente via Flowbite MCP o shadcn MCP
2. Adattare a Blade component con slot e attributi
3. Aggiungere traduzioni nel modulo appropriato
4. Testare con Filament v5 se il componente è usato in admin
5. Aggiornare questa documentazione

## Collegamenti

- [MCP UI/UX Tema Two](../../themes/two/docs/mcp-ui-ux.md)
- [Status MCP Progetto](../../../../docs/mcp-servers-status.md)
- [Skill MCP UI/UX](../../../.windsurf/skills/mcp-ui-ux/skill.md)
- [Workflow MCP UI/UX](../../../.windsurf/workflows/mcp-ui-ux.md)
