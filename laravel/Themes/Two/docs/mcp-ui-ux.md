# MCP Servers per UI/UX - Tema Two


## Filosofia

Usiamo MCP servers per migliorare la qualità della UI/UX generata dall'AI, fornendo contesto accurato e aggiornato su componenti, design system e best practice. Principi: DRY + KISS — solo MCP che servono davvero.

## MCP Attivi per UI/UX

### 1. Flowbite MCP
- **Scopo**: Componenti Tailwind CSS pronti, conversione Figma-to-code, generazione temi
- **Perché serve**: Il tema Two usa Tailwind CSS v4. Flowbite fornisce componenti HTML/Blade compatibili e un tool per generare temi da colori brand
- **Configurazione**: `npx -y flowbite-mcp`
- **Tool principali**:
  - Conversione Figma layers → codice Tailwind
  - Generazione file tema da colore brand
  - Contesto componenti per generazione AI
- **Docs**: https://flowbite.com/docs/getting-started/mcp/

### 2. shadcn MCP
- **Scopo**: Registry componenti, installazione via prompt naturale
- **Perché serve**: Permette di cercare e installare componenti da registry pubblici/privati con linguaggio naturale
- **Configurazione**: `npx shadcn@latest mcp`
- **Tool principali**:
  - Browse componenti disponibili
  - Ricerca cross-registry
  - Installazione con linguaggio naturale
- **Docs**: https://ui.shadcn.com/docs/mcp

### 3. daisyUI Blueprint MCP
- **Scopo**: Generazione codice UI con qualità 10x, meno token, risultati accurati
- **Perché serve**: Fornisce design system resources on-demand all'LLM invece di context massivi
- **Configurazione**: `npx -y daisyui@latest mcp`
- **Tool principali**:
  - Generazione componenti con specs accurate
  - Conversione da altri framework (Bootstrap, Tailwind puro) a daisyUI
- **Docs**: https://daisyui.com/blueprint/

### 4. MUI MCP
- **Scopo**: Documentazione Material UI accurata e verificata
- **Perché serve**: Riferimento per pattern UI enterprise, risposte accurate senza hallucination
- **Configurazione**: `npx -y @mui/mcp@latest`
- **Tool principali**:
  - `useMuiDocs` — fetch docs del package rilevante
  - `fetchDocs` — fetch docs aggiuntive da URL reali
- **Docs**: https://mui.com/material-ui/getting-started/mcp/

## MCP Valutati ma Non Attivati

| MCP Server | Motivo esclusione |
|---|---|
| FlyonUI MCP | Richiede licenza PRO per funzionalità avanzate |
| UI/UX MCP Server (willem4130) | Richiede Storybook running + setup complesso, non necessario ora |
| Figma MCP (Cursor Talk) | Richiede plugin Figma + Cursor specifico |
| Magic UI MCP | Docs non sufficienti per valutazione |

## Stack Tecnologico del Tema Two

- **CSS Framework**: Tailwind CSS v4 + @tailwindcss/vite
- **Backend**: Laravel + Filament v5 + Livewire
- **Build**: Vite 6.x
- **Componenti**: Blade components + Filament components
- **Output build**: `public_html/themes/Two/` (manifest.json + assets/)

## Come Usare gli MCP nel Workflow

1. **Generazione componenti**: Chiedere all'AI di usare Flowbite MCP per generare componenti Tailwind
2. **Theming**: Usare Flowbite MCP per generare file tema da colore brand
3. **Ricerca componenti**: Usare shadcn MCP per cercare componenti specifici
4. **Riferimento pattern**: Usare MUI MCP per pattern UI enterprise

## Collegamenti

- [Configurazione MCP Windsurf](../../../.windsurf/mcp.json)
- [Status MCP Progetto](../../../docs/mcp-servers-status.md)
- [Prompt MCP](../../../bashscripts/tools/prompts/mcp.txt)
