# Modules Documentation Index

**Project**: base_techplanner_fila4_mono
**Architecture**: Modular Monolith
**Laravel**: 11.x | **Filament**: 4.x | **PHP**: 8.2+
**Status**: ✅ PHPStan Level 10 Compliant (All Modules)

---

## Quick Links

- [PHPStan Level 10 Success Story](phpstan-level-10-success.md) - Complete compliance achievement
- [Filament Extension Rules](architecture/filament-extension-rules.md) - Critical XotBase patterns
- [Development Rules](development_rules_updated.md) - Project-wide guidelines

---

## Core Modules

### Xot - Framework Foundation
**Path**: `Modules/Xot/docs/`
**Status**: ✅ PHPStan 0 errors (16 → 0)
**Role**: Core base classes, utilities, architectural patterns

**Key Documentation**:
- [README](../Modules/Xot/docs/README.md) - Module overview
- [Filament Extension Rules](../Modules/Xot/docs/filament-extension-rules.md)
- [PHPStan Patterns](../Modules/Xot/docs/phpstan-patterns-dec-2025.md)
- [Project Best Practices](../Modules/Xot/docs/project-best-practices.md)

**Provides**:
- XotBaseResource, XotBasePage, XotBaseWidget
- SafeStringCastAction, SafeArrayCastAction, etc.
- Shared traits and utilities

---

## Business Domain Modules

### TechPlanner - Main Application
**Path**: `Modules/TechPlanner/docs/`
**Status**: ✅ PHPStan 0 errors (4 → 0)
**Role**: Technical planning and device management

**Features**:
- Client management
- Device tracking
- Technical planning workflows

---

### Cms - Content Management
**Path**: `Modules/Cms/docs/`
**Status**: ✅ PHPStan Level 10 - 0 errors (7 → 0)
**Role**: Content management system

**Key Documentation**:
- [README](../Modules/Cms/docs/README.md) - Main documentation
- [Homepage Management](../Modules/Cms/docs/homepage-management.md)
- [Content Strategy](../Modules/Cms/docs/content-management-strategy.md)

**Features**:
- Page management
- Block-based content
- Metatag system
- Volt/Livewire components

**PHPStan Achievement**:
- Removed redundant `method_exists()` checks
- Fixed view-string type annotations
- Applied SafeStringCastAction for type safety
- 4 files corrected (XotComposer, LoginComponent, RegisterComponent, DownloadAttachmentPlaceHolder)

---

### Employee - HR Management
**Path**: `Modules/Employee/docs/`
**Status**: ✅ PHPStan 0 errors (9 → 0)
**Role**: Employee and HR management

**Features**:
- Employee profiles
- Work hours tracking
- Time entry management

---

## Infrastructure Modules

### User - Authentication & Authorization
**Path**: `Modules/User/docs/`
**Status**: ✅ PHPStan Level 10 - 0 errors (10 → 0)
**Role**: User management, authentication, permissions

**Key Documentation**:
- [README](../Modules/User/docs/README.md) - Comprehensive documentation
- [Architecture](../Modules/User/docs/architecture/) - System architecture
- [Security](../Modules/User/docs/security/best-practices.md) - Security guidelines

**Features**:
- User authentication (Passport, Socialite, SSO)
- Role-based permissions (Spatie)
- Profile management
- Team management
- Device tracking

**PHPStan Achievement**:
- Resolved Collection covariance issue
- Fixed return type `Collection<int|string, non-empty-string>`
- 1 file corrected (IsProfileTrait.php)

---

### Notify - Notifications
**Path**: `Modules/Notify/docs/`
**Status**: ✅ PHPStan Level 10 - 0 errors (11 → 0)
**Role**: Multi-channel notification system

**Key Documentation**:
- [README](../Modules/Notify/docs/README.md) - Main documentation
- [Email Attachments](../Modules/Notify/docs/email-sending/attachments_usage.md) - Attachment handling
- [Notifications Guide](../Modules/Notify/docs/notifications/notifications_implementation_guide.md)

**Features**:
- Email, SMS, push notifications
- Contact management
- Notification templates
- Channel abstraction
- Binary attachment support

**PHPStan Achievement**:
- Type-safe email attachments
- Safe cast actions throughout
- Enhanced validation for forms
- Multiple files corrected

---

### Activity - Audit Logging
**Path**: `Modules/Activity/docs/`
**Status**: ✅ PHPStan 0 errors (2 → 0)
**Role**: Activity logging and audit trails

**Key Documentation**:
- [Index](../Modules/Activity/docs/index.md)
- [Architecture](../Modules/Activity/docs/architecture/structure.md)

**Features**:
- Spatie activity log integration
- Event sourcing capabilities
- Audit trail generation

---

### Geo - Geographic Data
**Path**: `Modules/Geo/docs/`
**Status**: ✅ PHPStan Level 10 - 0 errors (68 → 0)
**Role**: Geographic data and address management

**Key Documentation**:
- [README](../Modules/Geo/docs/README.md) - Main documentation
- [Address Implementation](../Modules/Geo/docs/address-implementation.md)
- [PHPStan Fixes](../Modules/Geo/docs/phpstan-fixes-gennaio-2025.md) - Technical details

**Features**:
- Address management (polymorphic)
- Italian administrative divisions (Comune, Provincia, Regione)
- Google Maps, Mapbox, Here.com integration
- Coordinate management
- 8,000+ Italian municipalities database

**PHPStan Achievement** (Biggest Fix):
- Uncommented all 11 AddressItemEnum constants (60+ errors from this alone!)
- Added type safety to HasAddress trait
- Fixed array access patterns with type narrowing
- 2 files corrected (AddressItemEnum.php, HasAddress.php)
- **89% of module errors from one file!**

---

## Supporting Modules

### UI - User Interface Components
**Path**: `Modules/UI/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: Shared UI components

**Features**:
- Custom Filament components
- Calendar widgets
- Shared view components

---

### Lang - Internationalization
**Path**: `Modules/Lang/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: Multi-language support

**Features**:
- Translation management
- Locale handling
- Translation file organization

---

### Media - File Management
**Path**: `Modules/Media/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: Media library and file handling

**Features**:
- Spatie Media Library integration
- Image optimization
- File uploads

---

### Job - Background Processing
**Path**: `Modules/Job/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: Background job management

**Features**:
- Queue management
- Job monitoring
- Failed job handling

---

### Tenant - Multi-Tenancy
**Path**: `Modules/Tenant/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: Multi-tenant architecture

**Features**:
- Tenant isolation
- Database separation
- Tenant-aware queries

---

### Gdpr - Data Privacy
**Path**: `Modules/Gdpr/docs/`
**Status**: ✅ PHPStan 0 errors
**Role**: GDPR compliance

**Features**:
- Data anonymization
- Export/delete user data
- Consent management

---

## Documentation Standards

### Naming Conventions

**✅ CORRECT**:
- `README.md` (uppercase, module root)
- `architecture-overview.md` (lowercase, kebab-case)
- `phpstan-compliance.md`

**❌ WRONG**:
- `phpstan-2025-12-15.md` (no dates in filenames)
- `Architecture-Overview.md` (no uppercase except README)
- `phpstan_compliance.md` (prefer kebab-case over snake_case)

### Link Standards

**Always use relative links**:
```markdown
✅ [Xot Module](../Modules/Xot/docs/README.md)
✅ [PHPStan Success](phpstan-level-10-success.md)

❌ [Xot Module](/var/www/.../Modules/Xot/docs/README.md)
❌ [PHPStan](http://localhost/docs/phpstan.md)
```

### File Organization

```
Modules/{ModuleName}/docs/
├── README.md (module overview)
├── index.md (detailed index)
├── architecture/
│   └── *.md (architectural docs)
├── guides/
│   └── *.md (how-to guides)
├── api/
│   └── *.md (API documentation)
└── _integration/
    └── *.md (integration guides)
```

---

## Common Patterns

### PHPStan Compliance Documentation

Every module with PHPStan fixes should document:
1. Initial error count
2. Final error count (must be 0)
3. Key patterns applied
4. Files modified
5. Lessons learned

Example: [Geo Module PHPStan Fixes](../Modules/Geo/docs/phpstan-fixes.md)

### Filament Resource Documentation

Document:
- Which XotBase class is extended
- Custom methods (if any)
- Why custom logic is needed
- Form schema structure
- Table columns structure

### Migration Patterns

Document:
- Database changes
- Backward compatibility
- Data migration strategy
- Rollback procedure

---

## Contributing to Documentation

### Before Creating New Docs

1. **Search first**: Check if doc already exists
2. **DRY principle**: Link to existing docs instead of duplicating
3. **Relative links**: Always use relative paths
4. **No dates**: Don't put dates in filenames

### Creating New Documentation

1. Choose appropriate directory
2. Use kebab-case naming
3. Add to relevant index
4. Use relative links only
5. Include examples

### Updating Existing Docs

1. Read existing content first
2. Maintain existing style/language
3. Add, don't replace (unless obsolete)
4. Update modification date at bottom

---

## Quick Reference

| Need | Go To |
|------|-------|
| Filament patterns | [filament-extension-rules.md](architecture/filament-extension-rules.md) |
| PHPStan compliance | [phpstan-level-10-success.md](phpstan-level-10-success.md) |
| Base classes | [Xot Module README](../Modules/Xot/docs/README.md) |
| Development workflow | [Xot Development Workflow](../Modules/Xot/docs/development-workflow-detailed.md) |
| Testing | [CLAUDE.md](../../CLAUDE.md) Testing section |
| Git conflicts | [Git Conflicts Resolution](../Modules/Xot/docs/git-conflicts-resolution.md) |

---

**Last Updated**: December 15, 2025
**Maintained By**: Laraxot Team

🤖 Generated with [Claude Code](https://claude.com/claude-code)
