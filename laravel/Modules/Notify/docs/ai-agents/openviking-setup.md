---
title: "OpenViking Setup"
type: concept
tags: [openviking, setup]
created: 2026-07-14
updated: 2026-07-14
qmd: "openviking-setup openviking setup"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# OpenViking Setup

**Path**: `.agents/docs/openviking-setup.md`  
**Last Updated**: 2026-03-28  
**Status**: canonical

## Baseline

OpenViking is a global runtime for the machine, not a dependency to install inside each repository.

Use this split:
- global runtime: `ov`, `openviking-server`
- global config: `~/.openviking/ov.conf`, `~/.openviking/ovcli.conf`
- project workspace: `./.openviking`

## Current Repository Convention

- start OpenViking from the project root so the workspace resolves to `./.openviking`
- index repository docs with `ov add-resource`
- keep module and theme docs DRY through local indices and canonical shared guides

## Commands

```bash
ov --version
openviking-server --host 127.0.0.1 --port 1933
ov status
ov add-resource ./docs --wait
ov add-resource ./laravel/Modules/Xot/docs --wait
ov add-resource ./laravel/Themes/Zero/docs --wait
ov find "Livewire 4"
```

## MCP Note

If an agent configuration exposes OpenViking as an MCP server, it must point to the global `openviking-server` command, not to a repo-local venv binary.

## References

- [Skill definition](./../skills/openviking/SKILL.md)
- [Canonical integration guide](../../docs/openviking-integration.md)
- Official repository: https://github.com/volcengine/OpenViking
