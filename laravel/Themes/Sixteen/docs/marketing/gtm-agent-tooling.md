---
title: "gtm agent tooling"
type: note
tags: [marketing, gtm, explorium, skills, mcp, sixteen]
created: 2026-10-22
updated: 2026-10-22
qmd: "explorium gtm skills vibe prospecting codex cursor install homepage civic"
related:
  - ./homepage-conversion-contract.md
  - ./civic-homepage-conversion-playbook.md
  - ../bmad/homepage-guest-visual-contract.md
status: draft
---

# Tooling GTM per agenti (Explorium + verifica FO)

## Perché

L’articolo [11 Best Codex Plugins for GTM](https://www.explorium.ai/blog/building-ai-agents/gtm-plugin-for-codex/) spiega plugin/skills/MCP per **prospecting B2B**. FixCity FO serve invece **conversione residenti** (task civici). Usiamo:

1. skill GTM Explorium dove servono ricerca ICP / comuni come clienti;
2. playbook civico + Puppeteer/Playwright per `/it` (trust Design Comuni + CTA above-the-fold).

Regola Explorium: *non automatizzare un playbook non provato a mano*.

## Installato (2026-10-22)

```bash
# Skills Cursor (user-wide)
npx skills add explorium-ai/gtm-skills -a cursor -g
npx skills add explorium-ai/vibeprospecting-plugin --skill vibe-prospecting -a cursor -g

# Codex plugins
codex plugin marketplace add explorium-ai/gtm-skills
codex plugin marketplace add explorium-ai/vibeprospecting-plugin
codex plugin add explorium-public-skills@explorium-public-skills
codex plugin add vpai@vibeprospecting
```

Auth Vibe (solo se serve MCP B2B live):

```bash
npx @vibeprospecting/vpai@latest login
npx @vibeprospecting/vpai@latest login --poll
```

MCP URL (opzionale): `https://vibeprospecting.explorium.ai/mcp`

## Verifica homepage (non GTM B2B)

```bash
# Chromium Playwright locale + puppeteer-core
CHROME=~/.cache/ms-playwright/chromium-1248/chrome-linux64/chrome
# screenshot in /tmp/fixcity-home-mkt/
```

Contratti: [homepage-conversion-contract.md](./homepage-conversion-contract.md) · [homepage-guest-visual-contract.md](../bmad/homepage-guest-visual-contract.md) · [civic-homepage-conversion-playbook.md](./civic-homepage-conversion-playbook.md)
