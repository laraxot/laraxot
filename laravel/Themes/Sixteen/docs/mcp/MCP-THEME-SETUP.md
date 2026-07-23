# MCP Configuration - Sixteen Theme

**Ultimo aggiornamento**: 2026-04-09  
**Config**: `laravel/.mcp.json` (canonical)  
**Module Docs**: [MCP Servers Index](../../../Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md)

## 🎯 Theme-Specific MCP Usage

Questo documento copre come usare i MCP servers per lo sviluppo del tema Sixteen.

## 🧠 Memory per Frontend Development

### Knowledge Graph Memory
**Use cases per theme development**:
- Ricordare pattern CSS usati
- Salvare decisioni di design
- Tenere traccia di componenti riutilizzabili

**Esempio**:
```
create_entities:
  - name: "segnalazione-parity-css"
    entityType: "stylesheet"
    observations:
      - "Font: Titillium Web 18px/16px"
      - "Colors: Bootstrap Italia mapped to Tailwind"
      - "HTML parity: 99-100%"
```

### Memory Bank
**Path**: `.memory-bank/` (project root)

**File utili per theme dev**:
- `activeContext.md`: Sessione CSS/JS corrente
- `techContext.md`: Stack Tailwind + Alpine.js
- `progress.md`: Pagine completate, TODO

## 🔧 CSS/JS Development Workflow

### 1. Build Process
```bash
cd laravel/Themes/Sixteen

# Modifica CSS/JS
# Poi:
npm run build
npm run copy

# Verifica in browser
http://127.0.0.1:8000/it/tests/[slug]
```

### 2. HTML Parity Check
```bash
# Script check-all-pages (Sixteen root)
node check-all-pages.cjs

# Output:
# - Screenshots: docs/screenshots/segnalazione-pages/
# - Parity report: Console output
```

### 3. Font Analysis
```bash
# Screenshot comparison script
node screenshot-compare.cjs

# Output fonts analysis:
# REF → "Titillium Web" | 18px | 400 | normal
# LOC → "Titillium Web", Geneva, Tahoma | 14px | 400 | 20px
```

## 📚 MCP Tools per Theme Dev

| Tool | Use Case | Esempio |
|------|----------|---------|
| **memory** | Salvare pattern CSS | "Create entity: button-styles" |
| **context7** | Docs Tailwind/Alpine | "Lookup Tailwind v4 grid" |
| **filesystem** | Leggere/modificare CSS | "Read style-apply.css" |
| **sqlite** | Query DB per content | "Check CMS blocks" |

## 📁 Theme CSS Structure

```
Themes/Sixteen/resources/css/
├── app.css                 → Entry point
├── style-apply.css         → Bootstrap → Tailwind mapping
├── container-override.css  → Container fixes
├── footer-override.css     → Footer overrides
├── bootstrap-italia.css    → Bootstrap classes
├── design-comuni-global.css → Global styles
└── segnalazione-parity.css → Segnalazione pages
```

## 🎨 Font Configuration

### Current (WRONG)
```css
font-family: "Titillium Web", Geneva, Tahoma, sans-serif;
```

### Target (CORRECT - match reference)
```css
font-family: "Titillium Web";
```

**Issue**: Il fallback stack nel computed style previene il match esatto con il reference.

**Fix Required**: Rimuovere fallback fonts per computed style match.

## 🔍 Screenshot Comparison

**Location**: `docs/screenshots/segnalazione-pages/[pagina]/`

**Files per pagina**:
- `reference-full.png` → Reference screenshot
- `reference-viewport.png` → Reference viewport
- `local-full.png` → Local screenshot
- `local-viewport.png` → Local viewport

**Next Step**: Confrontare visivamente e fixare CSS.

## 📊 Current Status

| Pagina | HTML Parity | Font Match | CSS Status |
|--------|-------------|------------|------------|
| segnalazione-area-personale | 100% | 0/33 | 🟡 Da migliorare |
| ticket-list | 100% | 0/31 | 🟡 Da migliorare |
| segnalazione-dettaglio | 100% | 0/32 | 🟡 Da migliorare |
| segnalazione-01-privacy | 100% | 0/22 | 🟡 Da migliorare |
| segnalazione-02-dati | 100% | 0/31 | 🟡 Da migliorare |
| segnalazione-03-riepilogo | 100% | 1/30 | 🟡 Da migliorare |
| segnalazione-04-conferma | 99.8% | 0/29 | 🟡 Da migliorare |

## Related Docs

- **Module MCP Index**: [MCP-SERVERS-INDEX.md](../../../Modules/Xot/docs/mcp/MCP-SERVERS-INDEX.md)
- **Theme Index**: [00-index.md](../00-index.md)
- **CSS Docs**: [CSS Directory](../css/)
- **Screenshots**: [Segnalazione Pages](../screenshots/segnalazione-pages/)
- **HTML Comparison**: [HTML Structure Comparison](../html-structure-comparison.md)
