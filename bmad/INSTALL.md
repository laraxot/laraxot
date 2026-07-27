# BMAD Method v6 — install progetto

- **Source:** https://github.com/aj-geddes/claude-code-bmad-skills.git
- **Ref:** main
- **Commit:** 27dca0e28960f4bb2db474837a14122ae83ab160
- **Plugin:** bmad-planning-orchestrator (planning & orchestration only — no dev-story)
- **Skills symlinked:** 20 → `bashscripts/ai/.agents/skills/bmad-*` (visibili anche via junction `.claude`)
- **Marketplace:** `.claude-plugin/marketplace.json`
- **Router:** `bashscripts/ai/.agents/skills/bmad/SKILL.md`

## Claude Code (marketplace locale)

```text
/plugin marketplace add ./bmad/upstream/claude-code-bmad-skills
/plugin install bmad-planning-orchestrator@bmad-method-harness
/reload-plugins
```

## Cursor / on-demand

1. `bmad-help` per orientamento
2. skill specifica `bmad-prd`, `bmad-architecture`, ecc.
3. routing wiki: `docs/wiki/skills/bmad-on-demand-routing.md`

Reinstall: `bash bashscripts/tools/install-bmad-v6-project.sh --force`
