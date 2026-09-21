<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
---
module: theme
topic: translation-resolution-prototype
canonical: ../../../Themes/docs/shared-components/translation-conflict-resolution-prototype.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/translation-conflict-resolution-prototype.md
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
# Translation Conflict Resolution Prototype

## Overview
This document describes the prototype for resolving Git conflicts in Laravel translation files while preserving all translation content.

## Conflict Types Identified

### Type 1: Complete Structure Conflicts
**Example**: `Modules/User/lang/it/device.php`
- One version has rich structure with `tooltip`, `helper_text`, `actions`, `navigation`, etc.
- Other version has minimal structure with basic `label`, `placeholder`, `help` fields
- **Resolution Strategy**: Merge both structures, prioritizing the richer version while preserving unique content from minimal version

### Type 2: Partial Key Conflicts
**Example**: `Modules/User/lang/it/edit_role.php`, `permission.php`
- Some keys exist in one version but not in another
- **Resolution Strategy**: Add missing keys from both versions

### Type 3: Duplicate Sections
**Example**: `Modules/TechPlanner/lang/it/appointment.php`
- Same sections appear twice without conflict markers
- **Resolution Strategy**: Merge duplicate content, removing redundancy

## Resolution Algorithm

### Step 1: Parse Conflict Markers
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
---
module: theme
topic: translation-resolution-prototype
canonical: ../../../Themes/docs/shared-components/translation-conflict-resolution-prototype.md
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

See canonical documentation: ../../../Themes/docs/shared-components/translation-conflict-resolution-prototype.md
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
=======
>>>>>>> laraxot/dev
