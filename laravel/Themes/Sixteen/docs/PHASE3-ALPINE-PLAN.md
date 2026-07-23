# Phase 3: Alpine.js Interactivity

## Goal
Replace Bootstrap JS interactions with Alpine.js directives (x-show, @click, x-data, etc.)

## Interactive Components Identified

### 1. Dropdown Menu (1 component)
**Location**: Navigation bar
**Bootstrap**: `data-bs-toggle="dropdown"`
**Alpine replacement**:
```html
<div x-data="{ open: false }" @click.away="open = false">
  <button @click="open = !open">Menu</button>
  <div x-show="open">Menu items...</div>
</div>
```

### 2. Modal Dialogs (3 modals)
**Locations**: 
- Search modal (primary)
- Secondary modals?
**Bootstrap**: `data-bs-toggle="modal"`
**Alpine replacement**:
```html
<div x-data="{ searchOpen: false }" x-show="searchOpen" @keydown.escape="searchOpen = false">
  <button @click="searchOpen = true">Open Search</button>
  <div class="modal">Search form...</div>
</div>
```

### 3. Search Modal
**Feature**: Text input search with modal overlay
**Bootstrap**: `data-bs-target="#search-modal"`
**Alpine replacement**:
- Controlled by Alpine x-data
- Focus management on open
- ESC to close

## Implementation Steps

1. **Identify all Bootstrap JS attributes**
   - Search for: `data-bs-*` attributes
   - Search for: `aria-*` attributes that need JS handling
   - Search for: Form interactions

2. **Create Alpine components**
   - Navigation dropdown
   - Search modal
   - Other modals

3. **Add Alpine.js to theme**
   - Import Alpine in resources/js/app.js
   - Initialize components

4. **Test interactivity**
   - Click dropdown → opens/closes
   - Click search icon → opens modal
   - ESC key → closes modals
   - Click outside → closes dropdowns

## Files to Modify
- `resources/js/app.js` - Alpine initialization
- `resources/views/components/` - Alpine directives
- Template files - Add x-data attributes

## Success Criteria
✅ All Bootstrap JS attributes converted to Alpine
✅ No Bootstrap JS errors in console
✅ All interactions work as in reference
✅ Mobile menu toggles responsive
✅ Search modal closes on ESC key

