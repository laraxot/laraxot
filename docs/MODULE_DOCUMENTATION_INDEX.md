# Laravel Modules Documentation Index

## Overview
This document provides a comprehensive index of all Laravel modules in the TechPlanner project and their documentation.

## Module List

### Core Modules

#### [Xot](../laravel/Modules/Xot/docs/README.md)
The core framework module providing base functionality and utilities.
- **Documentation**: [Xot Docs](../laravel/Modules/Xot/docs/)
- **Key Features**: Base models, services, and core functionality

#### [User](../laravel/Modules/User/docs/README.md)
User management and authentication module.
- **Documentation**: [User Docs](../laravel/Modules/User/docs/)
- **Key Features**: Authentication, roles, permissions, profiles

#### [Cms](../laravel/Modules/Cms/docs/README.md)
Content management system module.
- **Documentation**: [CMS Docs](../laravel/Modules/Cms/docs/)
- **Key Features**: Pages, content management, menus

### Business Modules

#### [Activity](../laravel/Modules/Activity/docs/README.md)
Activity logging and tracking module.
- **Documentation**: [Activity Docs](../laravel/Modules/Activity/docs/)
- **Key Features**: Activity logs, audit trails

#### [Employee](../laravel/Modules/Employee/docs/README.md)
Employee management module.
- **Documentation**: [Employee Docs](../laravel/Modules/Employee/docs/)
- **Key Features**: Employee records, HR functions

#### [Job](../laravel/Modules/Job/docs/README.md)
Job queue and scheduling module.
- **Documentation**: [Job Docs](../laravel/Modules/Job/docs/)
- **Key Features**: Queue management, job scheduling

#### [Notify](../laravel/Modules/Notify/docs/00-INDEX.md)
Notification system module.
- **Documentation**: [Notify Docs](../laravel/Modules/Notify/docs/00-INDEX.md)
- **Key Features**: Multi-channel notifications (email, SMS, push)

#### [TechPlanner](../laravel/Modules/TechPlanner/docs/README.md)
Tech planning module.
- **Documentation**: [TechPlanner Docs](../laravel/Modules/TechPlanner/docs/)
- **Key Features**: Technology planning tools

### Infrastructure Modules

#### [Geo](../laravel/Modules/Geo/docs/README.md)
Geographical data and location services module.
- **Documentation**: [Geo Docs](../laravel/Modules/Geo/docs/)
- **Key Features**: Geolocation, maps, address handling

#### [Lang](../laravel/Modules/Lang/docs/README.md)
Language and localization module.
- **Documentation**: [Lang Docs](../laravel/Modules/Lang/docs/)
- **Key Features**: Multi-language support, translations

#### [Media](../laravel/Modules/Media/docs/README.md)
Media management module.
- **Documentation**: [Media Docs](../laravel/Modules/Media/docs/)
- **Key Features**: File uploads, media processing

#### [Tenant](../laravel/Modules/Tenant/docs/README.md)
Multi-tenancy module.
- **Documentation**: [Tenant Docs](../laravel/Modules/Tenant/docs/)
- **Key Features**: Multi-tenant architecture

#### [UI](../laravel/Modules/UI/docs/README.md)
User interface components module.
- **Documentation**: [UI Docs](../laravel/Modules/UI/docs/)
- **Key Features**: UI components, themes

### Compliance Modules

#### [Gdpr](../laravel/Modules/Gdpr/docs/README.md)
GDPR compliance module.
- **Documentation**: [GDPR Docs](../laravel/Modules/Gdpr/docs/)
- **Key Features**: Data protection, GDPR compliance

## Documentation Standards

All module documentation follows the standards outlined in:
- [CRITICAL_RULES.md](CRITICAL_RULES.md)
- [DOCUMENTATION_INDEX.md](DOCUMENTATION_INDEX.md)

## Quick Navigation

### By Category
- **Core Framework**: Xot, User, Cms
- **Business Logic**: Activity, Employee, Job, Notify, TechPlanner
- **Infrastructure**: Geo, Lang, Media, Tenant, UI
- **Compliance**: Gdpr

### By Feature
- **Authentication**: User module
- **Content Management**: Cms module
- **Notifications**: Notify module
- **Geolocation**: Geo module
- **Multi-language**: Lang module
- **Media Handling**: Media module
- **Multi-tenancy**: Tenant module
- **UI Components**: UI module

## Contributing to Documentation

When adding or updating documentation:
1. Follow the naming convention: lowercase-with-dashes.md
2. Use the provided templates in docs/_DOCS_TEMPLATE/
3. Update the module's 00-INDEX.md file
4. Update this master index if adding new modules

## Documentation Quality

All modules maintain comprehensive documentation including:
- Installation and setup guides
- API documentation
- Development guidelines
- Testing procedures
- Troubleshooting guides
- Code quality reports (PHPStan, PHPMD, PHPInsights)

## Last Updated
This index was last updated on: 2025-12-10

For questions or issues with documentation, please refer to the individual module documentation or create an issue in the project repository.