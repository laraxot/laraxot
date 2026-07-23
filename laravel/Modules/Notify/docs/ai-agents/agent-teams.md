---
title: "Agent teams (Experimental - Opus 4.6)"
type: concept
tags: [agent, teams]
created: 2026-07-14
updated: 2026-07-14
qmd: "agent-teams agent teams (experimental - opus 4.6)"
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

# Agent teams (Experimental - Opus 4.6)

> Source: [claude.md](../../claude.md)
> Back: [index](index.md)

Agent Teams coordinate multiple Claude Code instances working together. One session acts as the **team lead** (coordinates, assigns tasks, synthesizes results) while **teammates** work independently in their own context windows.

## How to enable

Add to `.claude/settings.local.json`:
```json
{
  "env": {
    "CLAUDE_CODE_EXPERIMENTAL_AGENT_TEAMS": "1"
  }
}
```

## Display modes

- **in-process** (default): all teammates run inside main terminal. Use `Shift+Up/Down` to select teammates.
- **split-pane**: each teammate gets its own tmux/iTerm2 pane. Set `"teammateMode": "tmux"` in settings.

## Delegate mode

Press `Shift+Tab` to enable delegate mode: the lead only coordinates (spawn, message, manage tasks) without touching code directly.

## Quality gate hooks

- **`TeammateIdle`**: runs when a teammate is about to go idle. Exit code 2 sends feedback and keeps teammate working.
- **`TaskCompleted`**: runs when a task is being marked complete. Exit code 2 prevents completion with feedback.

## Recommended team structures for PTVX

**Quality team** (3 teammates):
- PHPStan Specialist: `phpstan analyse Modules/{Module} --level=10`
- Test Runner: `./vendor/bin/pest Modules/{Module}/tests`
- Code Formatter: `./vendor/bin/pint Modules/{Module}`

**Module development team** (3 teammates):
- Code Lead: implements features following XotBase patterns
- Test Writer: writes Pest tests for new functionality
- Docs Updater: updates module `docs/` after changes

**Review team** (3 teammates):
- Security Reviewer: checks OWASP, SQL injection, XSS
- Performance Reviewer: queries, N+1, caching
- Test Reviewer: coverage, edge cases

**Localization team** (2 teammates):
- Translation Validator: checks all modules have complete lang files (it/en/de)
- Route Localizer: verifies mcamara/laravel-localization integration in routes

**Custom pages team** (2 teammates):
- Page Implementer: creates custom Filament pages extending XotBasePage
- View Builder: creates Blade views with `filament-panels::page` components

## Best practices

- Size tasks appropriately: 5-6 tasks per teammate keeps everyone productive
- Avoid file conflicts: break work so each teammate owns different files
- Give enough context: teammates don't inherit lead's conversation history
- Start with research: begin with review/research tasks before implementation
- Tell lead to wait: "Wait for your teammates to complete their tasks before proceeding"

## Limitations

- No session resumption with in-process teammates (`/resume` won't restore them)
- One team per session, no nested teams
- Teammates cannot spawn their own teams
- Split panes require tmux or iTerm2

## Rules for teammates

- Each teammate respects module boundaries
- Use XotBase wrappers, never extend Filament directly
- All PHPStan errors must be fixed (Level 10)
- Coordinate via git to avoid file conflicts
- Translations never hardcoded - use `trans()` keys
