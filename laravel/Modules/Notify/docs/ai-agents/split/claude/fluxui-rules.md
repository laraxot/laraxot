---
title: "Fluxui Rules"
type: rule
tags: [fluxui, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "fluxui-rules fluxui rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./boost-rules.md"
  - "./filament-rules.md"
  - "./filament-v3-rules.md"
  - "./folio-rules.md"
  - "./foundation-rules.md"
  - "./laravel-core-rules.md"
  - "./laravel-v11-rules.md"
  - "./livewire-rules.md"
---

=== fluxui-free/core rules ===

## Flux UI Free

- This project is using the free edition of Flux UI. It has full access to the free components and variants, but does not have access to the Pro components.
- Flux UI is a component library for Livewire. Flux is a robust, hand-crafted, UI component library for your Livewire applications. It's built using Tailwind CSS and provides a set of components that are easy to use and customize.
- You should use Flux UI components when available.
- Fallback to standard Blade components if Flux is unavailable.
- If available, use Laravel Boost's `search-docs` tool to get the exact documentation and code snippets available for this project.
- Flux UI components look like this:

<code-snippet name="Flux UI Component Usage Example" lang="blade">
    <flux:button variant="primary"/>
</code-snippet>


### Available Components
This is correct as of Boost installation, but there may be additional components within the codebase.

<available-flux-components>
avatar, badge, brand, breadcrumbs, button, callout, checkbox, dropdown, field, heading, icon, input, modal, navbar, profile, radio, select, separator, switch, text, textarea, tooltip
</available-flux-components>



---

## Cross-References

- ← [CLAUDE Index](INDEX.md) — All Laravel Boost guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
- ← [../../../../docs/claude.md](../../../../docs/../../../../docs/claude.md) — Original source

