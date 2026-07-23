# MCP Tools for Sixteen Theme

**Data:** 2026-06-03  
**Scope:** AI-powered UI/UX tools for Sixteen theme development  
**Theme:** Sixteen (Fixcity project)  
**Stack:** Laravel + Livewire + Tailwind CSS + Filament

---

## Overview

This document describes how MCP (Model Context Protocol) tools enhance the Sixteen theme development workflow.

---

## Tool Suitability for Sixteen

| Tool | Best For | Sixteen Compatibility |
|------|----------|----------------------|
| **Impeccable** | Design polish, anti-pattern detection | ✅ Excellent |
| **Playwright MCP** | Automated testing, visual regression | ✅ Excellent |
| **UI UX Pro Max** | Design system generation | ✅ Excellent |
| **Flowbite MCP** | Tailwind components | ✅ Excellent |
| **DaisyUI Blueprint** | Component library | ✅ Good (requires migration) |

---

## Recommended Workflows

### 1. New Page Development

**Phase 1: Design System (UI UX Pro Max)**
```
/ui-ux-pro-max Generate design system for [PAGE_NAME]
Stack: Laravel + Livewire + Tailwind CSS
Theme: Sixteen (clean, professional, accessible)
```

**Phase 2: Build (Impeccable)**
```
/impeccable shape [PAGE_NAME] requirements
/impeccable craft [PAGE_NAME] implementation
```

**Phase 3: Review (Impeccable + Playwright)**
```
/impeccable critique [PAGE_NAME] design
/impeccable audit [PAGE_NAME] technical quality
```

**Phase 4: Refine (Impeccable)**
```
/impeccable polish [PAGE_NAME]
```

---

### 2. Component Enhancement

**Example: Map Legend Component**

```
/impeccable critique the map legend component
Focus: Status colors, collapsible design, mobile responsive
```

```
/impeccable polish the map legend
Requirements:
- Colors represent status (pending/in-progress/completed)
- Collapsible on mobile
- Matches Sixteen theme styling
- WCAG AA accessible
```

---

### 3. Figma to Code

**With Flowbite MCP:**

1. Export from Figma (Design Comuni mockups)
2. Copy node link
3. Prompt:
   ```
   use flowbite mcp to convert Figma [LINK] to Laravel Blade + Tailwind
   Theme: Sixteen color palette
   Components: Use Flowbite where applicable
   ```

---

## Tool-Specific Guides for Sixteen

### Impeccable

**Location:** `.claude/skills/impeccable/`

**Best Commands for Sixteen:**

| Command | Use Case |
|---------|----------|
| `audit` | Technical quality of Filament resources |
| `critique` | Design review of new pages |
| `polish` | Final refinement before deployment |
| `layout` | Fix spacing in Blade templates |
| `colorize` | Enhance color usage in maps/charts |
| `adapt` | Responsive improvements |

**Sixteen-Specific Rules:**

1. **Color Contrast**
   - Body text on Sixteen backgrounds: ≥4.5:1
   - Map marker text: ≥4.5:1
   - Button text: ≥4.5:1

2. **Typography**
   - Use Sixteen's configured fonts
   - Line length: 65-75ch for content
   - Hero text: max 6rem

3. **Layout**
   - Follow Sixteen's spacing scale
   - Cards: use sparingly, never nested
   - Flexbox for 1D, Grid for 2D

---

### Playwright MCP

**Configuration:** `docs/playwright-mcp-config.json`

**Sixteen Test Scenarios:**

```
# Homepage
Navigate to http://127.0.0.1:8000/it
Verify Sixteen theme loads correctly
Check navigation menu visibility

# Map Page
Navigate to http://127.0.0.1:8000/it/#
Verify map-lit component renders
Check markers load from /data/tickets.json
Test cluster behavior

# Responsive
Set viewport to 375px width
Verify mobile menu works
Check map legend collapses
```

---

### UI UX Pro Max

**Installation:** `uipro-cli` installed globally

**Sixteen Prompts:**

```
/ui-ux-pro-max Build a new dashboard for Fixcity admin
Stack: Laravel + Filament + Tailwind
Theme: Sixteen (professional, clean)
Colors: Match existing Sixteen palette
```

**Design System Output Integration:**

1. Copy generated colors to `tailwind.config.js`
2. Apply typography to `resources/css/app.css`
3. Use generated patterns in Blade templates
4. Follow pre-delivery checklist

---

### Flowbite MCP

**Configuration:** `docs/flowbite-mcp-config.json`

**Sixteen Integration:**

Sixteen uses Tailwind CSS, making Flowbite compatible:

```bash
# Install Flowbite (if not already)
npm install -D flowbite
```

**Useful Components:**

| Component | Sixteen Use Case |
|-----------|------------------|
| Dropdowns | Filter menus, user menu |
| Modals | Ticket detail popups |
| Drawers | Mobile sidebar |
| Tooltips | Map marker info |
| Tabs | Ticket view tabs |
| Alerts | Form validation messages |

**Prompts:**

```
use flowbite mcp to create a responsive navigation for Sixteen theme
use flowbite mcp to convert Figma mobile mockup to responsive drawer
```

---

## Theme-Specific Considerations

### Tailwind Configuration

Sixteen's `tailwind.config.js` should be compatible with:

- ✅ Impeccable (no changes needed)
- ✅ Flowbite (add plugin)
- ✅ DaisyUI (add plugin, optional)

### Filament Integration

Filament has its own component system. Use MCP tools for:

- Custom Filament widgets
- Blade components outside Filament
- Frontend pages (not admin panel)

### Color Palette

Sixteen's colors should be the master reference:

```javascript
// tailwind.config.js
colors: {
  primary: '#...',    // Sixteen primary
  secondary: '#...',  // Sixteen secondary
  // ... etc
}
```

Override tool-generated colors with Sixteen's palette.

---

## Testing Checklist

### Before Deployment

- [ ] Run `impeccable audit` on new pages
- [ ] Run `impeccable critique` on design changes
- [ ] Playwright MCP: Test critical user flows
- [ ] Verify responsive at 375px, 768px, 1024px, 1440px
- [ ] Check color contrast ≥4.5:1
- [ ] Verify prefers-reduced-motion support
- [ ] Test with actual data (tickets.json)

---

## Configuration Files

Copy from root `docs/` to theme if needed:

```bash
# Optional: Copy configs to theme
cp docs/playwright-mcp-config.json laravel/Themes/Sixteen/docs/
cp docs/flowbite-mcp-config.json laravel/Themes/Sixteen/docs/
```

---

## References

- Root Master Guide: `docs/mcp-tools-master-guide.md`
- Impeccable Guide: `docs/impeccable-complete-guide.md`
- Playwright MCP: `docs/playwright-mcp-setup.md`
- UI UX Pro Max: `docs/ui-ux-pro-max-guide.md`
- Flowbite MCP: `docs/flowbite-mcp-guide.md`
- DaisyUI Blueprint: `docs/daisyui-blueprint-guide.md`

---

**Status:** Documented for Sixteen theme integration

**Last Updated:** 2026-06-03
