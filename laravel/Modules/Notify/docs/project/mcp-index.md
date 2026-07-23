---
title: "MCP Index"
type: concept
tags: [mcp, index]
created: 2026-07-14
updated: 2026-07-14
qmd: "mcp-index mcp index"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./2-1-1-plan.md"
  - "./2-1-context.md"
  - "./agents.md"
  - "./ai-agent-lessons-learned.md"
  - "./ai-skills-and-plugins-complete.md"
  - "./commit-message.md"
  - "./configuration.md"
  - "./design-comuni-bmad-master-plan.md"
---

# MCP Index

**Last Updated**: 2026-04-09

## Memory & Knowledge

- [MCP Servers Configuration](../../docs/project/mcp-servers.md) - All MCP servers, memory systems, config
- [Supermemory Setup](../../docs/project/supermemory-setup.md) - Supermemory API, tags, CLI commands

## Development Tools

- [Laravel MCP Development](../../.qwen/skills/mcp-development/) - Laravel MCP tools, resources, prompts
- [MCP Manager Skill](../../.qwen/skills/mcp-manager/) - MCP server configuration management

## Configuration

- [Qwen MCP Config](../../.qwen/mcp.json) - MCP server definitions
- [Memory Bank](../../memory-bank/) - Project memory bank (structured docs)

## Rules

- **NEVER** create duplicate MCP config files
- All MCP configs in `.qwen/mcp.json`
- Documentation in `docs/project/` (project-wide)
- Module-specific MCP docs in `Modules/*/docs/mcp/`
- Theme-specific MCP docs in `Themes/*/docs/mcp/`
- **NO dates in .md filenames**
