# 🗺️ Mappings Folder

> Bootstrap Italia → Tailwind CSS class translations and component mappings

**Status**: In Progress  
**Last Updated**: 2026-04-02  
**Related**: [← Master Index](../00-HOMEPAGE-REPLICATION-INDEX.md)

---

## 📋 Documents in This Folder

### 1. **01-BOOTSTRAP-CLASSES-INVENTORY.md**
**Purpose**: Complete inventory of Bootstrap Italia classes used on reference homepage

**Includes**:
- All `.container`, `.row`, `.col-*` grid classes
- Component classes (`.card`, `.btn-*`, `.chip-*`, `.badge-*`)
- Typography utility classes
- Spacing utilities (`.mb-*`, `.px-*`, `.p-*`)
- Display utilities (`.d-none`, `.d-block`, `.d-lg-flex`)
- Visibility helpers (`.visually-hidden`, `.visually-hidden-focusable`)

**Format**: Table with columns: Class | Usage | Frequency | Components

🔗 [Read](./01-BOOTSTRAP-CLASSES-INVENTORY.md) | [← Back](#-documents-in-this-folder)

---

### 2. **02-TAILWIND-EQUIVALENTS.md**
**Purpose**: Tailwind CSS equivalents for each Bootstrap class

**Mapping Examples**:
```
.container              → @apply max-w-7xl mx-auto px-4
.row                   → @apply flex flex-wrap -mx-4
.col-12                → @apply w-full
.col-lg-6              → @apply lg:w-1/2
.card                  → @apply border rounded-lg shadow-sm
.btn-primary           → @apply bg-blue-600 text-white px-4 py-2
.mb-5                  → @apply mb-8  (adjust scale)
.d-lg-flex             → @apply lg:flex
```

**Usage**: Cross-reference with Bootstrap Inventory to create Tailwind classes

🔗 [Read](./02-TAILWIND-EQUIVALENTS.md) | [← Back](#-documents-in-this-folder)

---

### 3. **03-CUSTOM-COMPONENTS-MAP.md**
**Purpose**: Bootstrap Italia custom components → Tailwind component utilities

**Components**:
- `.chip`, `.chip-simple` → Custom @apply in Tailwind
- `.read-more` → Link + icon pattern
- `.category-top` → Metadata display pattern
- `.text-paragraph-card` → Typography variant
- `.card-teaser`, `.card-image` → Card layout variants
- `.rounded-icon` → Icon styling pattern

**Format**: 
```markdown
### Component: [name]
**Reference Class**: `.chip-simple`
**Purpose**: Tag/label display
**Reference Markup**: [HTML snippet]
**Tailwind Equivalent**: [utility classes or @apply rule]
**Alpine.js Needs**: [any interactivity requirements]
```

🔗 [Read](./03-CUSTOM-COMPONENTS-MAP.md) | [← Back](#-documents-in-this-folder)

---

### 4. **04-COLOR-TOKEN-MAPPING.md**
**Purpose**: AGID color tokens → Tailwind config colors

**AGID Color System**:
- Primary colors (blues): various shades
- Neutral grays: from 50 to 950
- Status colors: success (green), warning (yellow), danger (red), info (blue)
- Text colors: dark, secondary, muted
- Background colors: white, light gray, section backgrounds

**Tailwind Config Extension**:
```javascript
colors: {
  'agid-primary': '#1a5490',      // Primary blue
  'agid-secondary': '#...',       // Secondary
  'agid-success': '#...',         // Success green
  'agid-warning': '#...',         // Warning yellow
  'agid-danger': '#...',          // Error red
  // ... all tokens
}
```

🔗 [Read](./04-COLOR-TOKEN-MAPPING.md) | [← Back](#-documents-in-this-folder)

---

## 🎯 How to Use Mappings

### Creating Tailwind Utilities

1. Start with [01-BOOTSTRAP-CLASSES-INVENTORY.md](./01-BOOTSTRAP-CLASSES-INVENTORY.md)
2. Find Tailwind equivalent in [02-TAILWIND-EQUIVALENTS.md](./02-TAILWIND-EQUIVALENTS.md)
3. If custom component, check [03-CUSTOM-COMPONENTS-MAP.md](./03-CUSTOM-COMPONENTS-MAP.md)
4. Verify colors from [04-COLOR-TOKEN-MAPPING.md](./04-COLOR-TOKEN-MAPPING.md)

### Extending tailwind.config.js

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        // From 04-COLOR-TOKEN-MAPPING.md
      },
      spacing: {
        // From 02-TAILWIND-EQUIVALENTS.md
      },
      components: {
        // From 03-CUSTOM-COMPONENTS-MAP.md
      }
    }
  },
  plugins: []
}
```

### Implementation in Blade Templates

Replace Bootstrap classes:
```blade
<!-- Before (Bootstrap) -->
<div class="container">
  <div class="row">
    <div class="col-lg-6">...

<!-- After (Tailwind) -->
<div class="max-w-7xl mx-auto px-4">
  <div class="flex flex-wrap -mx-4">
    <div class="w-full lg:w-1/2">...
```

---

## 📊 Mapping Statistics

| Category | Count | Status |
|----------|-------|--------|
| Grid classes | ~15 | ✅ Ready |
| Component classes | ~20 | ⏳ In progress |
| Typography | ~10 | ⏳ In progress |
| Spacing utilities | ~30 | ⏳ In progress |
| Custom components | ~8 | ⏳ In progress |
| **Total** | **~83** | **⏳** |

---

## 🔗 Related Documents

| Document | Purpose |
|----------|---------|
| [Analysis](../analysis/) | Structural breakdown |
| [Screenshots](../screenshots/) | Visual reference |
| [Visual Comparison](../visual-comparison/) | Annotated analysis |
| [Phases - Config](../phases/02-PHASE-B-CONFIG.md) | Implementation using mappings |

---

## 🚀 Next Steps

1. **Complete inventories** (01, 02, 03, 04)
2. **Create tailwind.config.js extensions** from mappings
3. **Test utilities** in component templates
4. **Document patterns** in [Phases](../phases/)

---

**Navigation**: 
- [← Back to Master Index](../00-HOMEPAGE-REPLICATION-INDEX.md)
- [← Previous: Visual Comparison](../visual-comparison/README.md)
- [Next: Phases →](../phases/README.md)

