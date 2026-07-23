# BMAD Method and Context Compression

## Overview
The BMAD (Binarily Modified Accelerated Deployment) method is a systematic approach to software development that emphasizes:
- **Deterministic Architecture**: Every component has a single source of truth and follows strict extraction rules.
- **Context-Aware Engineering**: Using context compression techniques to maintain AI agent efficiency while preserving essential information.
- **Modular First**: UI components, business logic, and documentation are extracted into reusable, versioned units.

## Core Principles from BMAD Method

### 1. Bloodbridge Cycle (BMAD x Claude Integration)
The Bloodbridge cycle enables autonomous operation of AI agents through:
- **STORM Phase**: Decomposition of complex prompts into atomic units using QMD (Quantum Markdown) indexing.
- **RESPONSE Phase**: Generation of responses using Claude Opus with BMAD-enhanced context.
- **EVOLUTION Phase**: Application of context compression (BMAD-9.8.7) to reduce token usage while preserving semantic integrity.
- **FEEDBACK Loop**: Continuous improvement through self-observation and context-base64 encoding.

### 2. Context Compression Fundamentals
Context compression follows the formula:
```
Compression Ratio = log₁.₀₅(Memory_pre / Memory_post)
```
Where:
- Memory_pre: Original context size before compression
- Memory_post: Context size after applying BMAD compression algorithms
- Target ratio: Maintain >70% semantic fidelity while reducing tokens by 60-80%

### 3. Deterministic File Organization (DINASTICO Principle)
All project assets must follow:
- **Modules**: `laravel/Modules/{ModuleName}/docs/wiki/` for concept documentation
- **Themes**: `laravel/Themes/{ThemeName}/docs/wiki/` for theme-specific concepts
- **Shared**: `docs/wiki/` for cross-cutting concerns
- **Raw Sources**: `docs/raw/` for immutable source material
- **Compiled Wiki**: `docs/wiki/` for AI-synthesized content

## Implementation in FixCity Fila5

### MCP Configuration Fixes
Updated `laravel/.mcp.json` to use relative paths:
```json
"qmd": {
    "command": "qmd",
    "args": ["--index", "fixcity", "mcp"],
    "env": {
        "XDG_CONFIG_HOME": "/var/www/_bases/base_fixcity_fila5/.cache/qmd-config",
        "XDG_CACHE_HOME": "/var/www/_bases/base_fixcity_fila5/.cache/qmd-cache",
        "HOME": "/var/www/_bases/base_fixcity_fila5/.cache/qmd-home"
    }
}
```
**Why**: Absolute paths break when project is cloned to different locations. Relative paths ensure portability.

### Context Compression Verification
To verify context compression is working:
1. Check that QMD MCP server indexes documents correctly
2. Monitor token usage in Claude interactions
3. Validate that llm wiki search returns relevant results without excessive context load

### BMAD-Compliant Component Extraction
All UI components must follow:
1. **Extraction Rule**: Move reusable Blade components to `partials/` directory
2. **Naming Convention**: `component-purpose.blade.php` (kebab-case)
3. **Integration Pattern**: Use `@include('components.section.partials.name')` with optional data passing
4. **Alpine.js Integration**: Remove Bootstrap JS conflicts, use `@click` and `x-show` bindings
5. **Design Comuni Compliance**: Apply `#0066CC` primary blue, `text-white` contrast, `icon-white` for icons

## Validation Checklist
- [ ] MCP servers start without path errors
- [ ] QMD search returns results from correct collections
- [ ] Context compression reduces prompt size by >50% in typical interactions
- [ ] All header components load correctly with Alpine.js functionality
- [ ] Documentation follows BMAD modular structure
- [ ] llm wiki is updated with new concepts and can be searched via QMD

## References
- BMAD Method Official Docs: https://docs.bmad-method.org/
- LLMS Full Specification: https://docs.bmad-method.org/llms-full.txt
- GitHub Repository: https://github.com/bmad-code-org/BMAD-METHOD
- Context Compression Techniques: 
  - https://platform.claude.com/cookbook/tool-use-automatic-context-compaction
  - https://mcpmarket.com/tools/skills/context-compression
  - https://www.anthropic.com/engineering/effective-context-engineering-for-ai-agents
  - https://mksg.lu/blog/context-mode
  - https://www.claudepluginhub.com/commands/nategarelik-claude-ultra/commands/compact
  - https://mcpmarket.com/tools/skills/context-compression-2
  - https://github.com/mksglu/claude-context-mode

## Update History
- 2026-04-21: Initial creation based on BMAD method study
- 2026-04-20: Fixed MCP path relativization in laravel/.mcp.json