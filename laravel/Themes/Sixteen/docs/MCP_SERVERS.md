# MCP Servers - Theme Context

**Theme**: Sixteen (Active)  
**Last Updated**: 2026-04-09

## Master Documentation

**See [Project MCP Servers](../../../docs/MCP_SERVERS.md)** for complete server list, configuration, and usage examples.

This document provides Sixteen theme-specific MCP usage guidelines only.

## Theme-Specific MCP Usage

### puppeteer
- **Primary Use**: Visual parity verification
- **Reference URLs**: `https://italia.github.io/design-comuni-pagine-statiche/sito/<page>.html`
- **Local URLs**: `http://127.0.0.1:8000/it/tests/<page>`
- **Screenshots**: Saved to `docs/prompts/<page>/css-js-phase/`

### filesystem
- **Scope**: `Themes/Sixteen/` directory
- **Use**: Explore theme structure, find CSS/JS files
- **Key Paths**:
  - `resources/css/app.css` - CSS entry point
  - `resources/js/app.js` - JS entry point
  - `tailwind.config.js` - Tailwind configuration
  - `vite.config.js` - Build configuration

### sqlite
- **Use**: Query CMS content blocks for theme pages
- **Example**: 
  ```sql
  SELECT content_blocks FROM cms_pages WHERE slug = 'tests.segnalazione-01-privacy';
  SELECT * FROM blocks WHERE page_id IN (SELECT id FROM pages WHERE theme = 'Sixteen');
  ```

### fetch
- **Use**: Fetch reference HTML/CSS for parity comparison
- **Example**: `curl https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html`

### memory
- **Use**: Store theme conventions, CSS patterns, build process knowledge
- **Examples**:
  - "Theme build requires npm run build && npm run copy from Themes/Sixteen/"
  - "Bootstrap Italia classes replicated with Tailwind @apply in style-apply.css"
  - "Folio pages use Volt class-based components"

### context7
- **Use**: Look up TailwindCSS, Alpine.js, Vite, Livewire documentation
- **Example Queries**:
  - "Tailwind CSS v4 @apply syntax"
  - "Alpine.js x-data patterns"
  - "Vite theme build configuration"
  - "Livewire Volt class-based component patterns"

### sequential-thinking
- **Use**: Theme architecture decisions, component design analysis
- **Example**: Evaluating visual parity strategies, component refactoring

### supermemory
- **Container Tag**: `fixcity`
- **Use**: Store theme evolution, design decisions, visual parity results
- **Commands**:
  - `supermemory add --tag fixcity --file path/to/theme-decision.md`
  - `supermemory search "theme visual parity" --tag fixcity`
  - `supermemory profile --tag fixcity --query "theme preferences"`

### memory-bank
- **Use**: Store theme development sessions, visual parity checkpoints
- **Example**: "Session 2026-04-09: Added file upload component to segnalazione-crea"

### git
- **Scope**: Theme file changes
- **Use**: Track theme evolution, find when components were added
- **Example**: `git blame Themes/Sixteen/resources/views/components/layouts/main.blade.php`

## Theme Development Workflow with MCP

### CSS/JS Changes
1. Edit theme files (`Themes/Sixteen/resources/`)
2. Run build commands:
   ```bash
   cd laravel/Themes/Sixteen
   npm run build   # Compile CSS/JS
   npm run copy    # Copy to public_html/themes/Sixteen/
   ```
3. Use `memory` to record the change
4. Use `supermemory` to store visual parity results

### Visual Parity Checks
1. Use `fetch` to get reference page HTML
2. Use `filesystem` to read theme implementation
3. Use `puppeteer` to capture screenshots
4. Use `sequential-thinking` to analyze differences
5. Store results in `memory` and `supermemory`

### Component Development
1. Use `context7` to look up best practices
2. Use `filesystem` to explore existing components
3. Use `memory` to store component patterns
4. Use `sqlite` to verify CMS integration

## Cross-References

- **Master MCP Docs**: [Project MCP Servers](../../../docs/MCP_SERVERS.md)
- **Xot Module MCP**: [Xot MCP Guide](../../Modules/Xot/docs/MCP_SERVERS.md)
- **Theme Documentation**: [Sixteen Theme Index](README.md)
- **Vite Configuration**: [Theme Vite Config](../vite.config.js)
- **Tailwind Configuration**: [Theme Tailwind Config](../tailwind.config.js)

---

*This document follows DRY+KISS principles. Server list and configuration are in the master doc.*
