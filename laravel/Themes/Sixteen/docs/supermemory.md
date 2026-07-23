# SuperMemory Integration

**Service**: SuperMemory AI - Persistent Memory Infrastructure for AI Agents  
**Status**: ✅ Active  
**Container Tag**: `fixcity-sixteen`  
**Last Updated**: 2026-04-09

## Overview

SuperMemory provides persistent memory for AI agents working on the FixCity Sixteen theme. It enables:
- **Project context retention** across sessions
- **Semantic search** over project documentation and decisions
- **Automated memory extraction** from conversations and work done

## Quick Start

### For AI Agents

```javascript
// At session start - retrieve context
const context = await client.profile({
  containerTag: 'fixcity-sixteen',
  q: 'relevant task context'
});

// After completing work - store memory
await client.add({
  content: 'Fixed CSS parity for segnalazione-01-privacy page',
  containerTag: 'fixcity-sixteen',
  metadata: { type: 'work-done', page: 'segnalazione-01-privacy' }
});
```

### Configuration

All configuration is in `laravel/Themes/Sixteen/.supermemory/`:

| File | Purpose |
|------|---------|
| `config.json` | API key and project settings (gitignored) |
| `team.json` | Team configuration (committed) |
| `init-memories.js` | Project context initialization |
| `README.md` | Detailed usage guide |

## Stored Memories

### Architecture
- Project overview (citizen reporting platform)
- Tech stack (Laravel 12, PHP 8.3, SQLite, Filament 3, Livewire 3)
- Module architecture (Xot, Fixcity, User, Cms, Geo, etc.)

### Frontend
- Theme system (Sixteen, Tailwind CSS v4, Alpine.js, Vite)
- Design Comuni replication (38 pages, 47 components)
- CSS/JS build process (npm run build && npm run copy)

### Development
- Critical rules (HTML parity, Tailwind @apply, 5-level translations)
- Workflow (CSS/JS phase, documentation updates)
- Methodology (BMAD + GSD + Ralph Loop)

## Usage Patterns

### CSS/JS Phase Work

```javascript
// Before starting CSS work
const context = await client.search.memories({
  q: 'CSS JS phase workflow HTML parity',
  containerTag: 'fixcity-sixteen',
  threshold: 0.3
});

// After completing visual fixes
await client.add({
  content: `Visual parity fix for ${page}: stepper, checkbox, button, contacts card`,
  containerTag: 'fixcity-sixteen',
  metadata: {
    type: 'css-fix',
    page: 'segnalazione-01-privacy',
    htmlParity: '99.8%',
    date: '2026-04-09'
  }
});
```

### Documentation Updates

```javascript
await client.add({
  content: 'Updated segnalazione-parity.css with 14 new visual parity rules',
  containerTag: 'fixcity-sixteen',
  metadata: {
    type: 'documentation',
    file: 'resources/css/segnalazione-parity.css',
    linesAdded: 850
  }
});
```

## Links

- [SuperMemory Console](https://console.supermemory.ai)
- [SDK Documentation](https://www.npmjs.com/package/supermemory)
- [Configuration Details](../../.supermemory/README.md)
