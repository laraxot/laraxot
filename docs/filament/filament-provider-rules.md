# Filament Provider Rules - CRITICAL ARCHITECTURAL REQUIREMENTS

## LAW: AdminPanelProvider Extension Rule

### MANDATORY REQUIREMENT
**ALL AdminPanelProvider classes MUST extend XotBaseMainPanelProvider**

- **File**: `/app/Providers/Filament/AdminPanelProvider.php`
- **MUST extend**: `Modules\Xot\Providers\Filament\XotBaseMainPanelProvider`
- **Status**: NON-NEGOTIABLE ARCHITECTURAL LAW

### Correct Implementation
```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Implementation follows XotBase patterns
}
```

### Why This Rule Exists
1. **Architectural Consistency**: Ensures all Laraxot projects follow the same base structure
2. **Feature Inheritance**: Inherits all XotBase functionality and patterns
3. **Maintainability**: Centralized updates and improvements through base class
4. **Standards Compliance**: Enforces Laraxot framework conventions

### Enforcement Checklist
- [ ] AdminPanelProvider extends XotBaseMainPanelProvider
- [ ] Proper namespace imports are present
- [ ] No direct extension of Filament's base classes
- [ ] Follows declare(strict_types=1) convention

### Validation Commands
```bash
# Check current implementation
grep -n "extends" ../laravel/app/Providers/Filament/AdminPanelProvider.php

# Verify namespace import
grep -n "XotBaseMainPanelProvider" ../laravel/app/Providers/Filament/AdminPanelProvider.php
```

### Related Documentation
- [Laraxot Architecture Rules](./laraxot.md)
- [Provider Documentation](./development_rules.md)
- [Filament Integration Guidelines](./filament_resources.md)

### Anti-Patterns (FORBIDDEN)
```php
// ❌ NEVER DO THIS
class AdminPanelProvider extends PanelProvider
{
    // This violates Laraxot architecture
}

// ❌ NEVER DO THIS
class AdminPanelProvider extends ServiceProvider
{
    // This breaks the Filament panel structure
}
```

## Compliance Status
✅ **CURRENT PROJECT STATUS**: COMPLIANT
- AdminPanelProvider correctly extends XotBaseMainPanelProvider
- Proper namespace imports are in place
- Follows architectural requirements

---

**Last Updated**: July 2025  
**Compliance Level**: MANDATORY - LAW  
**Enforcement**: ALWAYS REQUIRED
