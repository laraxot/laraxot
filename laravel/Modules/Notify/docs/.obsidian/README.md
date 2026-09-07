# Notify

[![Module](https://img.shields.io/badge/Module-Notify-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

<<<<<<< HEAD
## Quick Start

1. **Open Obsidian**
2. Click **"Open folder as vault"**
<<<<<<< HEAD
<<<<<<< HEAD
3. Select: `/var/www/_bases/base_fixcity_fila5/docs`
4. Vault name: `base_fixcity_fila5-docs`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
3. Select: `/var/www/_bases/base_fixcity_fila5/docs`
4. Vault name: `base_fixcity_fila5-docs`
>>>>>>> a988596b (first)
3. Select: `/var/www/_bases/base_ptvx_fila5/docs`
4. Vault name: `base_ptvx_fila5-docs`
5. Click **"Open"**

## Configuration

Configuration files are already set up in `.obsidian/`:

- `app.json` - Core settings (wikilinks enabled, attachment path)
- `appearance.json` - Theme and font settings
- `hotkeys.json` - Keyboard shortcuts

## Recommended Plugins

### Install via Obsidian Community Plugins

1. Settings → Community Plugins → **Browse**
2. Search and install:

#### Essential Plugins

- **Dataview** - Query frontmatter for dynamic tables
  - Use case: List all concepts by tag, find orphan pages
  - Documentation: https://blacksmithgu.github.io/obsidian-dataview/

- **Templater** - Advanced templates for wiki pages
  - Use case: Auto-generate frontmatter, insert dates
  - Documentation: https://silentvoid13.github.io/Templater/

- **QuickAdd** - Quick page creation
  - Use case: Rapid concept/entity page creation
  - Documentation: https://quickadd.obsidian.md/

#### Optional Plugins

- **Obsidian Git** - Auto-commit wiki changes
  - Use case: Automatic git commits after wiki edits
  - Documentation: https://denolehov.github.io/obsidian-git/

- **Calendar** - Calendar view for log.md entries
  - Use case: Navigate activity log by date
  - Documentation: https://github.com/liamcain/obsidian-calendar-plugin

- **Outliner** - Better list handling
  - Use case: Structured wiki content outlines
  - Documentation: https://github.com/vslinko/obsidian-outliner

## Web Clipper

Install browser extension to capture web articles:

1. **Chrome/Edge**: [Obsidian Web Clipper](https://chrome.google.com/webstore/detail/obsidian-web-clipper)
2. **Firefox**: [Obsidian Web Clipper](https://addons.mozilla.org/en-US/firefox/addon/obsidian-web-clipper/)

**Configuration**:
<<<<<<< HEAD
<<<<<<< HEAD
- Clip destination: `/var/www/_bases/base_fixcity_fila5/docs/raw/articles/`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Clip destination: `/var/www/_bases/base_fixcity_fila5/docs/raw/articles/`
>>>>>>> a988596b (first)
- Clip destination: `/var/www/_bases/base_ptvx_fila5/docs/raw/articles/`
- Format: Markdown with YAML frontmatter
- Tags: Auto-extract from article metadata

## Usage Workflow

### 1. Capture Source

**Via Web Clipper**:
- Browse web → find article
- Click Web Clipper extension
- Clip to `raw/articles/`

**Via Manual**:
- Save PDF/article to `raw/articles/`

### 2. Ingest via LLM Agent

```
User: "ingest docs/raw/articles/filename.md"

LLM processes source and creates wiki pages.
```

### 3. Browse via Obsidian

- Open Graph View (Ctrl+Shift+G) to visualize connections
- Use backlinks panel to see incoming links
- Use tags pane to browse by topic

### 4. Query via LLM Agent

```
User: "What do we know about LMSR?"

LLM searches wiki and synthesizes answer with citations.
```

### 5. Lint via LLM Agent

```
User: "lint wiki"

LLM scans for issues and reports findings.
```

## Graph View Tips

- **Filter by type**: `type:concept` to see only concept pages
<<<<<<< HEAD
<<<<<<< HEAD
- **Filter by tags**: `tags:prediction-market` to see related pages
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- **Filter by tags**: `tags:prediction-market` to see related pages
>>>>>>> a988596b (first)
- **Filter by tags**: `tags:forecast-market` to see related pages
- **Local graph**: Open on a page to see only its connections
- **Groups**: Color-code by page type (concepts, entities, sources)

## Dataview Queries

### List All Concepts by Tag

````markdown
```dataview
TABLE title, confidence, updated
FROM "wiki/concepts"
SORT updated DESC
```
````

### Find Orphan Pages

````markdown
```dataview
TABLE title, type
FROM "wiki"
WHERE length(file.inlinks) = 0
SORT title
```
````

### Recent Activity

````markdown
```dataview
TABLE title, type, sources
FROM "wiki/sources"
SORT updated DESC
LIMIT 10
```
````

## Troubleshooting

### Wikilinks Not Working

- Settings → Files & Links → "Use [[Wikilinks]]" must be **enabled**
- Check `app.json`: `"useWikilinks": true`

### Attachments Not Saving to raw/assets/

- Settings → Files & Links → "Attachment folder path" = `raw/assets`
- Check `app.json`: `"attachmentFolderPath": "raw/assets"`

### Git Auto-Commit Not Working

- Install Obsidian Git plugin
- Configure commit interval (e.g., every 30 minutes)
- Set commit message format: `docs: wiki auto-commit`

## Related Documentation

- [LLM Wiki Integration Guide](../wiki/README.md)
- [Agent Instructions](../wiki/AGENTS.md)
- [Wiki Overview](../wiki/overview.md)
=======
**Modulo** `Notify` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
>>>>>>> d822d97f (.)
