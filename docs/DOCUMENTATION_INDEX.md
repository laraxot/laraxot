# Laraxot PTVX - Complete Documentation Index

**Last Update**: 2025-12-05
**Project**: Laraxot PTVX - Laravel Modular Application
**Framework**: Laravel 12 + Filament 4 + PHP 8.3
**Architecture**: Modular (nwidart/laravel-modules)

---

## 📋 Quick Navigation

- [Project Overview](#-project-overview)
- [Architecture](#-architecture)
- [Module Categories](#-module-categories)
- [Core Modules](#-core-modules-foundation)
- [Infrastructure Modules](#-infrastructure-modules)
- [Business Modules](#-business-modules)
- [Supporting Modules](#-supporting-modules)
- [Development Resources](#-development-resources)

---

## 🎯 Project Overview

**Laraxot PTVX** is an enterprise-grade Laravel application built with a **modular architecture** using `nwidart/laravel-modules` and **Filament v4** as the admin panel framework. The project follows **domain-driven design** principles where each module represents a specific business domain.

### Core Technologies
- **Laravel 12**: Modern PHP framework
- **Filament 4**: Admin panel and UI framework
- **Livewire 3**: Full-stack framework
- **Pest 3**: Testing framework
- **PHPStan Level 10**: Static analysis (strictest mode)
- **Vite + Tailwind CSS 4**: Frontend tooling

### Key Principles
- **Modularity**: Each functionality organized in independent modules
- **Type Safety**: PHPStan Level 10 compliance throughout
- **Consistency**: Standardized structure and conventions
- **Extensibility**: Easy to add new modules and features
- **Quality**: Comprehensive testing and code quality tools

---

## 🏗️ Architecture

### Layered Architecture

```
┌──────────────────────────────────────────────────────┐
│               Application Layer                       │
│  Laravel 12 │ Filament 4 │ Livewire 3 │ Vite        │
├──────────────────────────────────────────────────────┤
│                 Business Modules                      │
│  TechPlanner │ Employee │ Cms │ ...                  │
├──────────────────────────────────────────────────────┤
│              Infrastructure Modules                   │
│  User │ UI │ Tenant │ Geo │ Lang │ Media │ ...      │
├──────────────────────────────────────────────────────┤
│                Foundation Module                      │
│                    Xot Module                         │
│  • XotBase* Classes (Resources, Widgets, Actions)    │
│  • Shared Patterns & Traits                          │
│  • Common Services & Utilities                       │
└──────────────────────────────────────────────────────┘
```

### Module Dependencies

```
Foundation Layer:
  └── Xot (No dependencies)

Infrastructure Layer:
  ├── User (depends on: Xot)
  ├── UI (depends on: Xot)
  ├── Tenant (depends on: Xot, User)
  ├── Geo (depends on: Xot)
  ├── Lang (depends on: Xot)
  ├── Media (depends on: Xot)
  ├── Activity (depends on: Xot, User)
  ├── Job (depends on: Xot)
  ├── Notify (depends on: Xot, User)
  └── Gdpr (depends on: Xot, User)

Business Layer:
  ├── TechPlanner (depends on: Xot, User, Geo, Media)
  ├── Employee (depends on: Xot, User)
  └── Cms (depends on: Xot, User, UI, Lang, Media)
```

---

## 🗂️ Module Categories

### Foundation
**Core framework functionality that all other modules depend on**
- Xot - Base classes, patterns, utilities

### Infrastructure
**Cross-cutting concerns and shared services**
- User, UI, Tenant, Lang, Geo, Media, Activity, Job, Notify, Gdpr

### Business
**Domain-specific business logic and features**
- TechPlanner, Employee, Cms

---

## 🔧 Core Modules (Foundation)

### Xot Module
**[📖 Full Documentation](./Modules/Xot/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Foundation module providing base classes, patterns, and utilities for all modules

**Key Components**:
- `XotBaseResource` - Base Filament resource class
- `XotBasePage` - Base page class
- `XotBaseWidget` - Base widget class
- `XotBaseAction` - Base action class
- `XotData` - Cross-module data management
- `MetatagData` - SEO and metadata management
- `TransTrait` - Translation utilities

**Key Features**:
- Type-safe base classes (PHPStan Level 10)
- Shared architectural patterns
- Cross-module integration tools
- Automation scripts (git, testing, CI/CD)

**Dependencies**: None (foundation layer)

**Documentation Structure**:
```
Xot/docs/
├── README.md                    ← Main documentation
├── architecture/                ← Architecture guides
├── development/                 ← Developer guides
├── testing/                     ← Testing strategies
└── troubleshooting/             ← Problem resolution
```

---

## 🏛️ Infrastructure Modules

### User Module
**[📖 Full Documentation](./Modules/User/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Authentication, authorization, user management, teams, tenants

**Key Components**:
- `User` / `BaseUser` - User model with STI support
- `Profile` - User profiles and preferences
- `Role` / `Permission` - Spatie permission integration
- `Team` - Team collaboration
- `Tenant` - Multi-tenancy support
- `AuthenticationLog` - Security audit trail
- `Device` - Device management

**Key Features**:
- Multi-authentication (credentials, OAuth, SSO)
- Role-Based Access Control (RBAC)
- Multi-tenancy with data isolation
- Team-based collaboration
- Security auditing and logging

**Dependencies**: Xot

**Related Docs**:
- [Authentication Flow](./Modules/User/docs/auth/authentication-flow.md)
- [Roles & Permissions](./Modules/User/docs/authorization/roles-permissions.md)
- [Security Best Practices](./Modules/User/docs/security/best-practices.md)

---

### UI Module
**[📖 Full Documentation](./Modules/UI/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Shared UI components, design system, Filament customizations

**Key Components**:
- `IconStateColumn` - State display with icons
- `IconStateSplitColumn` - State with split actions
- `SelectStateColumn` - Inline state editing
- Design system utilities
- Filament component extensions

**Key Features**:
- Reusable Filament components
- Consistent design system
- Custom table columns
- Form components
- Widget library

**Dependencies**: Xot

**Related Docs**:
- [Component Guide](./Modules/UI/docs/components-guide.md)
- [Design System](./Modules/UI/docs/design-system.md)
- [Filament Components](./Modules/UI/docs/filament-components.md)

---

### Tenant Module
**[📖 Full Documentation](./Modules/Tenant/docs/README.md)** | **Status**: 🚧 In Development

**Purpose**: Enhanced multi-tenancy features and tenant management

**Key Features**:
- Advanced tenant isolation
- Tenant-specific configurations
- Cross-tenant data access controls
- Tenant switching

**Dependencies**: Xot, User

---

### Lang Module
**[📖 Full Documentation](./Modules/Lang/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Internationalization, localization, translation management

**Key Components**:
- `TransTrait` - Model translation support
- Translation file management
- Locale switching
- Enum translation patterns
- Filament integration

**Key Features**:
- Multi-language support (IT, EN, DE, FR, ES)
- Translation management interface
- Automatic translation discovery
- Laravel localization integration
- Pluralization support

**Dependencies**: Xot

**Related Docs**:
- [Translation Process](./Modules/Lang/docs/TRANSLATION_PROCESS.md)
- [Locale Management](./Modules/Lang/docs/LOCALE_MANAGEMENT.md)
- [Translation Strategies](./Modules/Lang/docs/TRANSLATION_STRATEGIES.md)

---

### Geo Module
**[📖 Full Documentation](./Modules/Geo/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Geographic data management, address handling, location services

**Key Components**:
- `Address` - Address management
- `Comune` - Italian municipality data
- `Provincia` / `Regione` - Geographic hierarchy
- Google Maps integration
- Location services

**Key Features**:
- Address parsing and standardization
- Geographic entity management
- Location-based services
- Maps integration
- Sushi model support for static data

**Dependencies**: Xot

**Related Docs**:
- [Address Implementation](./Modules/Geo/docs/address-implementation.md)
- [Sushi Models Guide](./Modules/Geo/docs/sushi-implementation.md)
- [Geographic Data](./Modules/Geo/docs/geo-entities.md)

---

### Media Module
**[📖 Full Documentation](./Modules/Media/docs/README.md)** | **Status**: ✅ Production Ready

**Purpose**: File and media management, image processing, storage

**Key Components**:
- Media library integration
- File upload components
- Image processing
- Video conversion
- Storage management

**Key Features**:
- Multiple storage drivers
- Image optimization
- Video transcoding (FFmpeg, AWS MediaConvert)
- Responsive images
- File versioning

**Dependencies**: Xot

**Related Docs**:
- [File Management](./Modules/Media/docs/file-management.md)
- [FFmpeg Integration](./Modules/Media/docs/ffmpeg-usage.md)
- [AWS Integration](./Modules/Media/docs/aws.md)

---

### Activity Module
**[📖 Full Documentation](./Modules/Activity/docs/README.md)** | **Status**: ✅ Production Ready

**Purpose**: Activity tracking, audit logging, event sourcing

**Key Components**:
- Activity logging
- Event sourcing patterns
- Change tracking
- Audit trail

**Key Features**:
- Automatic activity tracking
- User action logging
- Model change history
- Event sourcing support

**Dependencies**: Xot, User

**Related Docs**:
- [Event Sourcing](./Modules/Activity/docs/event-sourcing.md)
- [Activity Tracking](./Modules/Activity/docs/business-logic-analysis.md)

---

### Notify Module
**[📖 Full Documentation](./Modules/Notify/docs/README.md)** | **Status**: ✅ Production Ready

**Purpose**: Notification system, communication channels

**Key Components**:
- Multi-channel notifications
- Email notifications
- SMS notifications
- Push notifications
- In-app notifications

**Key Features**:
- Laravel notification system integration
- Multiple notification channels
- Template management
- Notification preferences

**Dependencies**: Xot, User

---

### Job Module
**[📖 Full Documentation](./Modules/Job/docs/README.md)** | **Status**: ✅ Production Ready

**Purpose**: Background job processing, queue management

**Key Components**:
- Queue management
- Job scheduling
- Job monitoring
- Failed job handling

**Key Features**:
- Queue worker management
- Job status tracking
- Scheduled tasks
- Job retry logic

**Dependencies**: Xot

**Related Docs**:
- [Queue Management](./Modules/Job/docs/queue-management.md)
- [Job Scheduling](./Modules/Job/docs/schedule.md)

---

### Gdpr Module
**[📖 Full Documentation](./Modules/Gdpr/docs/README.md)** | **Status**: ✅ Production Ready

**Purpose**: GDPR compliance, data privacy, cookie consent

**Key Components**:
- Cookie consent management
- Data privacy tools
- Terms and conditions
- Privacy policy management

**Key Features**:
- GDPR compliance tools
- Cookie banner
- Data export/deletion
- Consent tracking

**Dependencies**: Xot, User

**Related Docs**:
- [GDPR Compliance](./Modules/Gdpr/docs/implementation-guide.md)
- [Cookie Consent](./Modules/Gdpr/docs/cookie-consent.md)

---

## 💼 Business Modules

### TechPlanner Module
**[📖 Full Documentation](./Modules/TechPlanner/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Technical planning and device management for service companies

**Business Domain**: Technical service companies providing device inspection, maintenance, and compliance

**Key Components**:
- `Client` - Client management
- `Appointment` - Technical appointment scheduling
- `Device` / `Machine` - Equipment tracking
- `LegalRepresentative` - Compliance management
- `MedicalDirector` - Healthcare facility oversight
- `Worker` - Staff assignment

**Key Features**:
- Client lifecycle management
- Multi-contact management (phone, email, PEC, WhatsApp)
- Appointment scheduling
- Device verification tracking
- Legal compliance management
- Geographic integration

**Dependencies**: Xot, User, Geo, Media

**Business Workflows**:
1. Client onboarding and setup
2. Appointment scheduling and execution
3. Device inspection and certification
4. Compliance monitoring and reporting

**Related Docs**:
- [Business Logic](./Modules/TechPlanner/docs/business-logic.md)
- [Models & Relationships](./Modules/TechPlanner/docs/models-and-relationships.md)
- [Filament Resources](./Modules/TechPlanner/docs/filament-resources.md)

---

### Employee Module
**[📖 Full Documentation](./Modules/Employee/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: HR management, employee tracking, time management

**Business Domain**: Human resources and personnel management

**Key Components**:
- `Employee` - Employee records
- `TimeEntry` - Time tracking
- `WorkHour` - Work schedule
- `TimeclockWidget` - Clock in/out interface
- `Contract` - Employment contracts

**Key Features**:
- Employee management
- Time clock system
- Work hour tracking
- Attendance management
- Contract management

**Dependencies**: Xot, User

**Business Workflows**:
1. Employee onboarding
2. Time tracking and attendance
3. Work schedule management
4. HR reporting

**Related Docs**:
- [Time Tracking](./Modules/Employee/docs/business_logic/time-tracking.md)
- [Timeclock Widget](./Modules/Employee/docs/TIMECLOCK_WIDGET_MASTER.md)
- [Architecture](./Modules/Employee/docs/ARCHITECTURE.md)

---

### Cms Module
**[📖 Full Documentation](./Modules/Cms/docs/README.md)** | **Status**: ✅ Production Ready | **PHPStan**: Level 10

**Purpose**: Content management system, frontend website management

**Business Domain**: Website content management and publishing

**Key Components**:
- `Page` - Page management
- `Article` / `Post` - Content publishing
- `Block` - Content blocks
- `Theme` - Theme management
- Folio/Volt integration - Frontend routing

**Key Features**:
- Dynamic page management
- Content block system
- Theme customization
- Frontend routing (Folio)
- Livewire components (Volt)
- SEO management

**Dependencies**: Xot, User, UI, Lang, Media

**Business Workflows**:
1. Content creation and editing
2. Page publishing
3. Theme customization
4. SEO optimization

**Related Docs**:
- [Content Management](./Modules/Cms/docs/content-management.md)
- [Folio Routing](./Modules/Cms/docs/folio-routing-system.md)
- [Block System](./Modules/Cms/docs/content-blocks-system.md)
- [Theme Management](./Modules/Cms/docs/themes/README.md)

---

## 🛠️ Development Resources

### Getting Started
- **[Project Setup Guide](./CLAUDE.md)** - Project architecture and setup
- **[Module Creation Guide](./Modules/_DOCS_TEMPLATE/STRUCTURE_GUIDE.md)** - Creating new modules
- **[Documentation Template](./Modules/_DOCS_TEMPLATE/README.md)** - Standard docs structure

### Development Commands
```bash
# Start development environment
composer dev

# Run tests
composer test
php artisan test

# Code quality
./vendor/bin/pint               # Code formatting
./vendor/bin/phpstan analyse    # Static analysis

# Frontend
npm run dev                     # Development with hot reload
npm run build                   # Production build
```

### Module Commands
```bash
# List all modules
php artisan module:list

# Create new module
php artisan module:make ModuleName

# Enable/disable modules
php artisan module:enable ModuleName
php artisan module:disable ModuleName

# Generate components
php artisan module:make-model ModelName ModuleName
php artisan module:make-filament-resource ResourceName ModuleName
```

### Code Quality Standards
- **PHPStan**: Level 10 (strictest)
- **Coding Style**: PSR-12 + Laravel conventions
- **Testing**: Pest framework, minimum 80% coverage
- **Type Safety**: Strict types required (`declare(strict_types=1)`)

### Critical Development Rules

#### 1. Testing Configuration
**NEVER use `RefreshDatabase` in tests**
- Use `.env.testing` with SQLite in-memory database
- Use `DatabaseTransactions` trait instead
- Tests must run in seconds, not minutes

#### 2. Case-Sensitive Naming
**NEVER create files differing only by case**
- Always use PascalCase for classes and files
- Avoid: `TimeclockWidget.php` vs `TimeClockWidget.php`
- Prevents fatal autoloading errors

#### 3. Module Dependencies
- Check `module.json` for dependencies
- Most modules require Xot as foundation
- Respect dependency hierarchy

### Best Practices
- **Extend XotBase Classes**: Always extend Xot base classes for Filament
- **Use Type Hints**: PHPStan Level 10 requires strict typing
- **Follow Naming Conventions**: Consistent PascalCase, kebab-case for docs
- **Leverage Xot Services**: Use XotData, TransTrait, NavigationService
- **Write Tests**: Minimum 80% coverage for new code

### Testing Strategy
```bash
# Test specific module
./vendor/bin/pest Modules/ModuleName

# Test with coverage
./vendor/bin/pest --coverage-html coverage

# Run PHPStan
./vendor/bin/phpstan analyse Modules/ModuleName --level=max
```

---

## 📚 Additional Documentation

### Architecture & Patterns
- [Xot Base Classes](./Modules/Xot/docs/architecture/xotbase-classes.md)
- [Filament 4 Migration Guide](./Modules/Xot/docs/filament-4-migration-guide.md)
- [PHPStan Workflow](./Modules/Xot/docs/phpstan-workflow.md)
- [Design Patterns](./Modules/Xot/docs/architecture/design-patterns.md)

### Security & Quality
- [Security Best Practices](./Modules/User/docs/security/best-practices.md)
- [Code Quality Tools](./Modules/Xot/docs/code-quality-tools.md)
- [Testing Guidelines](./Modules/Xot/docs/testing-strategy.md)

### Frontend & UI
- [Filament Components](./Modules/UI/docs/filament-components.md)
- [Design System](./Modules/UI/docs/design-system.md)
- [Folio/Volt Integration](./Modules/Cms/docs/folio-routing-system.md)
- [Tailwind CSS 4](./resources/css/README.md)

### Troubleshooting
- [Common Issues](./Modules/Xot/docs/troubleshooting/common-issues.md)
- [Git Conflicts Resolution](./Modules/Xot/docs/risoluzione-conflitti-merge.md)
- [Environment Configuration](./Modules/Xot/docs/environment-configuration-issues.md)

---

## 🔗 External Resources

### Laravel Ecosystem
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Filament 4 Documentation](https://filamentphp.com/docs/4.x)
- [Livewire 3 Documentation](https://livewire.laravel.com/docs/3.x)
- [Laravel Modules Package](https://nwidart.com/laravel-modules/)

### Quality & Testing
- [PHPStan Documentation](https://phpstan.org/user-guide)
- [Pest Documentation](https://pestphp.com/docs)
- [Laravel Pint](https://laravel.com/docs/12.x/pint)

### Frontend
- [Vite Documentation](https://vitejs.dev/)
- [Tailwind CSS 4](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)

---

## 📝 Changelog & Roadmap

### Recent Updates (2025-12-05)
- ✅ Laravel 12 migration completed
- ✅ Filament 4 upgrade completed
- ✅ PHP 8.3 compatibility
- ✅ PHPStan Level 10 compliance across core modules
- ✅ Documentation standardization initiated

### Upcoming Features
- [ ] Documentation consolidation (500 → 120 files)
- [ ] Module health check dashboard
- [ ] Automated dependency analyzer
- [ ] GraphQL API support
- [ ] Enhanced caching strategies

---

## 🤝 Contributing

### Documentation Contributions
1. Follow the [Documentation Structure Guide](./Modules/_DOCS_TEMPLATE/STRUCTURE_GUIDE.md)
2. Use the [Documentation Template](./Modules/_DOCS_TEMPLATE/README.md)
3. Maintain consistency with existing docs
4. Update index when adding new documentation

### Code Contributions
1. Ensure PHPStan Level 10 compliance
2. Write comprehensive tests (80%+ coverage)
3. Follow coding standards (PSR-12)
4. Update documentation

---

## 📊 Project Statistics

- **Total Modules**: 14
- **Core Modules**: 1 (Xot)
- **Infrastructure Modules**: 10
- **Business Modules**: 3
- **PHPStan Level**: 10 (across most modules)
- **PHP Version**: 8.3+
- **Laravel Version**: 12.x
- **Filament Version**: 4.x

---

## 🎯 Quick Links by Role

### For Developers
- [Getting Started](./CLAUDE.md)
- [Creating Modules](./Modules/_DOCS_TEMPLATE/STRUCTURE_GUIDE.md)
- [Xot Base Classes](./Modules/Xot/docs/README.md)
- [PHPStan Guide](./Modules/Xot/docs/phpstan-workflow.md)

### For System Architects
- [Architecture Overview](./Modules/Xot/docs/architecture-overview.md)
- [Module Dependencies](#module-dependencies)
- [Design Patterns](./Modules/Xot/docs/architecture/design-patterns.md)

### For Business Analysts
- [TechPlanner Business Logic](./Modules/TechPlanner/docs/business-logic.md)
- [Employee Workflows](./Modules/Employee/docs/business_logic.md)
- [CMS Content Management](./Modules/Cms/docs/content-management.md)

### For Security Officers
- [User Security](./Modules/User/docs/security/best-practices.md)
- [GDPR Compliance](./Modules/Gdpr/docs/implementation-guide.md)
- [Authentication Audit](./Modules/User/docs/security/audit-log.md)

---

**Last Updated**: 2025-12-05
**Maintained By**: Laraxot Team
**Documentation Version**: 1.0
**Project Version**: 3.0 (Laravel 12 + Filament 4)
