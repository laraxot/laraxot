---
title: "SuperMemory - AI Memory Infrastructure"
type: concept
tags: [supermemory, quickstart]
created: 2026-07-14
updated: 2026-07-14
qmd: "supermemory-quickstart supermemory - ai memory infrastructure"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# SuperMemory - AI Memory Infrastructure

**Project**: FixCity Platform  
**Last Updated**: 2026-04-09  
**API Key**: Configured (sm_BzH3Cugxk1hMDm5V1EHC2N_...)  
<<<<<<< HEAD
<<<<<<< HEAD
**Container Tag**: `fixcity`  
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Container Tag**: `fixcity`  
>>>>>>> a988596b (first)
**Container Tag**: `ptv`  
**User**: marco.sottana@gmail.com (Xot org)

## Overview

SuperMemory is the long-term and short-term memory and context infrastructure for AI agents. It provides persistent memory across conversations, semantic search, and knowledge graph capabilities.

**Master MCP Docs**: [Project MCP Servers](../../../docs/mcp-servers.md)

## Quick Start

### Verify Authentication
```bash
supermemory whoami
```

Expected output:
```
User:  marco.sottana@gmail.com
Org:   Xot (BzH3Cugxk1hMDm5V1EHC2N)
Plan:  free
Auth:  api-key (sm_BzH3Cugxk1hMDm5V1EHC2N_Jr9N****)
```

### Add Project Context
```bash
<<<<<<< HEAD
<<<<<<< HEAD
cd /var/www/_bases/base_fixcity_fila5
supermemory add --tag fixcity --file .supermemory/fixcity-context.md
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
cd /var/www/_bases/base_fixcity_fila5
supermemory add --tag fixcity --file .supermemory/fixcity-context.md
>>>>>>> a988596b (first)
cd /var/www/_bases/base_ptv_fila5
supermemory add --tag ptv --file .supermemory/ptv-context.md
```

### Search Memories
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory search "FixCity architecture" --tag fixcity
supermemory search "Laravel Filament patterns" --tag fixcity
supermemory search "theme build process" --tag fixcity
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory search "FixCity architecture" --tag fixcity
supermemory search "Laravel Filament patterns" --tag fixcity
supermemory search "theme build process" --tag fixcity
>>>>>>> a988596b (first)
supermemory search "FixCity architecture" --tag ptv
supermemory search "Laravel Filament patterns" --tag ptv
supermemory search "theme build process" --tag ptv
```

### Get Profile
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory profile --tag fixcity --query "project preferences"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory profile --tag fixcity --query "project preferences"
>>>>>>> a988596b (first)
supermemory profile --tag ptv --query "project preferences"
```

## Core Commands

| Command | Description | Example |
|---------|-------------|---------|
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
| `add` | Ingest content and extract memories | `supermemory add --tag fixcity --file docs/mcp-servers.md` |
| `search` | Search memories semantically | `supermemory search "ticket workflow" --tag fixcity` |
| `remember` | Store a specific memory | `supermemory remember "All models extend XotBaseModel" --tag fixcity` |
| `forget` | Remove a specific memory | `supermemory forget <memory-id>` |
| `update` | Update an existing memory | `supermemory update <memory-id> --content "..."` |
| `profile` | Get user/project profile | `supermemory profile --tag fixcity --query "preferences"` |
<<<<<<< HEAD
=======
| `add` | Ingest content and extract memories | `supermemory add --tag ptv --file docs/mcp-servers.md` |
| `search` | Search memories semantically | `supermemory search "ticket workflow" --tag ptv` |
| `remember` | Store a specific memory | `supermemory remember "All models extend XotBaseModel" --tag ptv` |
| `forget` | Remove a specific memory | `supermemory forget <memory-id>` |
| `update` | Update an existing memory | `supermemory update <memory-id> --content "..."` |
| `profile` | Get user/project profile | `supermemory profile --tag ptv --query "preferences"` |
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
| `tags` | Manage container tags | `supermemory tags list` |
| `docs` | Manage documents | `supermemory docs list` |

## FixCity Use Cases

### 1. Project Context Persistence
Store project architecture decisions:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory add --tag fixcity --content "FixCity uses Nwidart modules + Laraxot extensions. All models extend XotBaseModel. Service providers extend XotBaseServiceProvider."
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory add --tag fixcity --content "FixCity uses Nwidart modules + Laraxot extensions. All models extend XotBaseModel. Service providers extend XotBaseServiceProvider."
>>>>>>> a988596b (first)
supermemory add --tag ptv --content "FixCity uses Nwidart modules + Laraxot extensions. All models extend XotBaseModel. Service providers extend XotBaseServiceProvider."
```

### 2. Module-Specific Knowledge
Store module patterns:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory add --tag fixcity --content "Fixcity module: Ticket model extends XotBaseModel, uses Filament resources for admin, Folio+Volt for frontoffice."
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory add --tag fixcity --content "Fixcity module: Ticket model extends XotBaseModel, uses Filament resources for admin, Folio+Volt for frontoffice."
>>>>>>> a988596b (first)
supermemory add --tag ptv --content "Fixcity module: Ticket model extends XotBaseModel, uses Filament resources for admin, Folio+Volt for frontoffice."
```

### 3. Theme Conventions
Store theme development patterns:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory add --tag fixcity --content "Sixteen theme: Bootstrap Italia classes replicated with Tailwind @apply. Vite outDir: './public', then npm run copy to public_html/themes/Sixteen/."
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory add --tag fixcity --content "Sixteen theme: Bootstrap Italia classes replicated with Tailwind @apply. Vite outDir: './public', then npm run copy to public_html/themes/Sixteen/."
>>>>>>> a988596b (first)
supermemory add --tag ptv --content "Sixteen theme: Bootstrap Italia classes replicated with Tailwind @apply. Vite outDir: './public', then npm run copy to public_html/themes/Sixteen/."
```

### 4. Development Workflows
Store build processes:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory remember "After ANY CSS/JS change in theme: cd Themes/Sixteen && npm run build && npm run copy" --tag fixcity
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory remember "After ANY CSS/JS change in theme: cd Themes/Sixteen && npm run build && npm run copy" --tag fixcity
>>>>>>> a988596b (first)
supermemory remember "After ANY CSS/JS change in theme: cd Themes/Sixteen && npm run build && npm run copy" --tag ptv
```

### 5. Architectural Decisions
Store reasoning behind decisions:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
supermemory add --tag fixcity --content "Decision: Use Actions over Services for business logic. Rationale: Queueable, testable, reusable. Spatie/laravel-queueable-action package."
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory add --tag fixcity --content "Decision: Use Actions over Services for business logic. Rationale: Queueable, testable, reusable. Spatie/laravel-queueable-action package."
>>>>>>> a988596b (first)
supermemory add --tag ptv --content "Decision: Use Actions over Services for business logic. Rationale: Queueable, testable, reusable. Spatie/laravel-queueable-action package."
```

## Integration with AI Workflow

### Before Starting Work
```bash
# Get project context
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
supermemory profile --tag fixcity --query "FixCity project architecture and conventions"

# Search for relevant patterns
supermemory search "Filament widget patterns" --tag fixcity
<<<<<<< HEAD
=======
supermemory profile --tag ptv --query "FixCity project architecture and conventions"

# Search for relevant patterns
supermemory search "Filament widget patterns" --tag ptv
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
```

### During Development
```bash
# Store decisions
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
supermemory remember "Added file upload component to CreateTicketWizardWidget using wire:change" --tag fixcity

# Search for similar patterns
supermemory search "file upload Livewire" --tag fixcity
<<<<<<< HEAD
=======
supermemory remember "Added file upload component to CreateTicketWizardWidget using wire:change" --tag ptv

# Search for similar patterns
supermemory search "file upload Livewire" --tag ptv
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
```

### After Completion
```bash
# Store completed work summary
<<<<<<< HEAD
<<<<<<< HEAD
supermemory add --tag fixcity --file path/to/session-summary.md
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory add --tag fixcity --file path/to/session-summary.md
>>>>>>> a988596b (first)
supermemory add --tag ptv --file path/to/session-summary.md

# Update project context if needed
supermemory update <context-memory-id> --content "Updated architecture..."
```

## Container Tags Strategy

| Tag | Purpose | Example Content |
|-----|---------|-----------------|
| `ptv` | Project-wide context | Architecture, conventions, decisions |
| `ptv-{module}` | Module-specific | Module patterns, models, resources |
| `ptv-{theme}` | Theme-specific | Theme conventions, build process |
| `ptv-{session}` | Session-specific | Session summary, decisions made |

## Best Practices

1. **Use Descriptive Content**: Be specific about what you're storing
<<<<<<< HEAD
<<<<<<< HEAD
2. **Tag Consistently**: Always use `fixcity` as base tag
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
2. **Tag Consistently**: Always use `fixcity` as base tag
>>>>>>> a988596b (first)
2. **Tag Consistently**: Always use `ptv` as base tag
3. **Update Regularly**: Keep memories current with project evolution
4. **Search Before Adding**: Avoid duplicate memories
5. **Use Metadata**: Add metadata for better filtering:
   ```bash
<<<<<<< HEAD
<<<<<<< HEAD
   supermemory add --tag fixcity --content "..." --metadata '{"type":"architecture","module":"Xot"}'
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
   supermemory add --tag fixcity --content "..." --metadata '{"type":"architecture","module":"Xot"}'
>>>>>>> a988596b (first)
   supermemory add --tag ptv --content "..." --metadata '{"type":"architecture","module":"Xot"}'
   ```

## Configuration

### MCP Configuration
Located in `laravel/.mcp.json`:
```json
{
  "supermemory": {
    "command": "supermemory",
    "args": [],
    "env": {
      "SUPERMEMORY_API_KEY": "sm_BzH3Cugxk1hMDm5V1EHC2N_..."
    }
  }
}
```

### CLI Configuration
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
Located in `~/.supermemory/projects/-var-www-_bases-base_fixcity_fila5/config.json`:
```json
{
  "apiKey": "sm_BzH3Cugxk1hMDm5V1EHC2N_...",
  "containerTag": "fixcity"
<<<<<<< HEAD
=======
Located in `~/.supermemory/projects/-var-www-_bases-base_ptv_fila5/config.json`:
```json
{
  "apiKey": "sm_BzH3Cugxk1hMDm5V1EHC2N_...",
  "containerTag": "ptv"
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
}
```

## Troubleshooting

### Authentication Issues
```bash
# Check authentication
supermemory whoami

# Re-authenticate if needed
<<<<<<< HEAD
<<<<<<< HEAD
supermemory init --api-key YOUR_KEY --container-tag fixcity --scope project
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
supermemory init --api-key YOUR_KEY --container-tag fixcity --scope project
>>>>>>> a988596b (first)
supermemory init --api-key YOUR_KEY --container-tag ptv --scope project
```

### No Results from Search
- Try broader search terms
<<<<<<< HEAD
<<<<<<< HEAD
- Verify container tag: `--tag fixcity`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Verify container tag: `--tag fixcity`
>>>>>>> a988596b (first)
- Verify container tag: `--tag ptv`
- Wait 1-2 minutes after adding content for processing

### Content Not Appearing
- Check file path is correct
- Verify content format (markdown preferred)
- Use `supermemory docs list` to see ingested documents

## Related Documentation

- **Master MCP Docs**: [Project MCP Servers](../../../docs/mcp-servers.md)
- **Xot Module MCP**: [Xot MCP Guide](../../Modules/Xot/docs/mcp-servers.md)
- **Theme MCP**: [Sixteen Theme MCP](../../Themes/Sixteen/docs/mcp-servers.md)
- **SuperMemory Skill**: [.qwen/skills/supermemory/](.qwen/skills/supermemory/)
- **SuperMemory Console**: https://console.supermemory.ai

---

*This document follows DRY+KISS principles. For general MCP server info, see the master doc.*
