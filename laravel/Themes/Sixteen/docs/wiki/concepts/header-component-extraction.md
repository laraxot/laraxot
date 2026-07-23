# Header Component Extraction Pattern

## Overview
Extraction of reusable UI components from header Blade templates follows strict architectural principles to maintain consistency with Design Comuni standards and Alpine.js integration requirements.

## Component Extraction Rules

### 1. Partial Organization
All extracted components must be placed in:
```
/laravel/Themes/Sixteen/resources/views/components/sections/header/partials/
```

### 2. Component Naming Convention
- Use kebab-case filenames
- Prefix with semantic purpose:
  - `language-switcher.blade.php`
  - `personal-area-guest-cta.blade.php`
  - `personal-area-guest-parity.blade.php`
  - `user-dropdown.blade.php`

### 3. Integration Protocol
- Main templates include via:
  ```blade
  @include('components.sections.header.partials.language-switcher')
  ```
- Follow Laravel view resolver hierarchy

### 4. Alpine.js Integration
- Remove Bootstrap JS data attributes (`data-bs-toggle`)
- Use Alpine.js bindings:
  ```blade
  @click="userOpen = !userOpen"
  x-show="userOpen"
  x-on:click="() => { this.userOpen = !this.userOpen; }"
  ```

### 5. Design Comuni Compliance
- Color tokens: `#0066CC` primary blue background
- Typography: `text-white` on primary background
- Iconography: `icon-white` classes only
- Spacing: Consistent padding/margin tokens

### 6. Display Name Logic
Priority order for authenticated user display:
1. `$headerProfile->user_name`
2. `$headerProfile->full_name`
3. `$authUser->user_name`
4. `$authUser->full_name`
5. `"$first_name $last_name"`
6. Fallback to `'Account'`

## Implementation Example
```blade
@php
    $viewPath = 'components.sections.header.partials.language-switcher';
@endphp
@include($viewPath)
```

## Validation Checklist
- [ ] Component loads without PHP errors
- [ ] Display name renders correctly for authenticated users
- [ ] Dropdown opens/closes properly with Alpine.js
- [ ] Colors match Design Comuni token `#0066CC`
- [ ] Accessibility attributes present (`aria-expanded`, `aria-controls`)
- [ ] No Bootstrap JS conflicts in browser console