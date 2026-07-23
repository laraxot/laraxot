# Sixteen Theme LLM Wiki

The Sixteen theme is a strong candidate for an LLM wiki because the team repeatedly revisits the same parity, layout, accessibility, and asset questions.

## Mapping

- Source umbrella: `laravel/Themes/Sixteen/docs/`
- Optional imported sources: `laravel/Themes/Sixteen/docs/sources/`
- Compiled wiki: `laravel/Themes/Sixteen/docs/wiki/`
- Schema: `laravel/Themes/Sixteen/docs/wiki/schema.md`

## What Belongs In The Theme Wiki

- cross-page parity patterns;
- recurring frontend decisions;
- design-comuni synthesis pages;
- frontoffice troubleshooting knowledge that should survive chat history.

## What Stays In The Source Layer

- screenshots and screenshot folders;
- raw HTML comparisons;
- batch reports;
- one-run diagnostics.

## Commands

```bash
bashscripts/docs/llm-wiki-qmd.sh collection add laravel/Themes/Sixteen/docs --name theme-sixteen-docs
bashscripts/docs/llm-wiki-qmd.sh search "homepage parity"
bashscripts/docs/llm-wiki-qmd.sh search "accessibility" -c theme-sixteen-docs
```
