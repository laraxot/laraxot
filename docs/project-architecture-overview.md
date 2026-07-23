# TechPlanner Laravel Project Architecture Overview

**Last Updated**: 2025-12-05
**Status**: Comprehensive Architecture Documentation

## 🏗️ Project Structure

This is a comprehensive Laravel application built using a modular architecture with the following key characteristics:

### Core Architecture Components

#### 1. **Modular Design**
- Built with `nwidart/laravel-modules` package
- 15+ active modules providing different functionality areas
- Each module follows consistent structure with app/, docs/, config/, database/, etc.

#### 2. **Multi-tenant Support**
- Built-in tenant isolation using spatie/laravel-multitenancy
- Tenant-specific data separation and configuration
- Context switching capabilities for multi-tenant operations

#### 3. **Filament Integration**
- Modern admin interface using Filament v3/v4
- Custom XotBase and LangBase abstract classes for extension
- Enhanced resource patterns and UI components

#### 4. **Foundation Layer (Xot Module)**
- Core functionality and base classes for other modules
- Provides XotBaseModel, XotBaseResource, and other foundation patterns
- Contract system for type safety and interface consistency

## 📦 Active Modules Analysis

### Module Priorities and Dependencies

#### **Xot Module (Priority: 2)**
- **Role**: Foundation and core functionality
- **Key Features**:
  - Base classes (XotBaseServiceProvider, XotBaseModel, etc.)
  - Service provider architecture
  - Blade component registration
  - Configuration loading patterns
- **Documentation Status**: Well documented with comprehensive guides

#### **Tenant Module (Priority: 2)**
- **Role**: Multi-tenant support and data isolation
- **Key Features**:
  - Tenant switching and isolation
  - Context management
  - Database isolation patterns
- **Documentation Status**: Needs enhancement with implementation details

#### **TechPlanner Module (Priority: 0)**
- **Role**: Main business logic module
- **Key Features**:
  - Technical planning and management
  - Client and appointment management
  - Device tracking and compliance
- **Documentation Status**: Well documented with business logic focus

#### **User Module (Priority: 0)**
- **Role**: Authentication and authorization
- **Key Features**:
  - User management and profiles
  - Role-based access control
  - Team management and permissions
  - Authentication contracts and patterns
- **Documentation Status**: Extensively documented with comprehensive guides

#### **Other Active Modules**:
- **Activity**: Activity logging and tracking
- **Gdpr**: GDPR compliance features
- **Geo**: Geographic services and location management
- **Media**: File and media management
- **Notify**: Notification systems
- **UI**: User interface components and customizations
- **Cms**: Content management system
- **Employee**: Employee management features

## 🏛️ Architecture Patterns

### 1. **XotBase Extension Pattern**
All Filament classes should extend XotBase* or LangBase* abstract classes instead of direct Filament classes:

```php
// ❌ Wrong
use Filament\Resources\Pages\ListRecords;
class MyPage extends ListRecords { }

// ✅ For non-multilingual modules
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class MyPage extends XotBaseListRecords { }

// ✅ For multilingual modules (Cms, Blog, News)
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
class MyPage extends LangBaseListRecords { }
```

### 2. **Contract-Based Architecture**
- Comprehensive interface definitions for type safety
- UserContract and ProfileContract for user management
- Model contracts for consistent behavior across modules

### 3. **Service Provider Architecture**
- XotBaseServiceProvider provides common functionality
- Module-specific service providers extend base patterns
- Automatic registration of views, translations, migrations

### 4. **Multi-Tenant Implementation**
- Isolated data per tenant using spatie/multitenancy
- Tenant-specific configurations and contexts
- Automatic tenant switching based on request context

## 🚀 Development Standards

### Code Quality
- **PHPStan Level 10**: Complete type safety compliance
- **PSR-12**: Coding standards compliance
- **Strict Types**: All files use `declare(strict_types=1)`
- **Type Hints**: Comprehensive parameter and return type declarations

### Translation Standards
- **Trilingual Support**: Italian, English, and German translations
- **Consistent Keys**: All translation keys implemented in all languages
- **No Mixed Languages**: Prevent language mixing in single translations

### Documentation Standards
- **Lowercase Files**: All .md files in lowercase (except README.md)
- **Module-Specific Docs**: Each module has dedicated documentation
- **Business Context**: Technical features linked to business purpose

## 🎯 Business Logic Areas

### TechPlanner Core Features
1. **Client Management**: Complete lifecycle from onboarding to ongoing service
2. **Appointment Scheduling**: Technical appointment and device inspection workflows
3. **Device Tracking**: Equipment management and compliance monitoring
4. **Legal Compliance**: Regulatory requirements and legal representative management
5. **Communication Hub**: Multi-channel contact management with action integration

### User Management Features
1. **Authentication**: Complete user lifecycle with role-based access
2. **RBAC System**: Hierarchical role management with team-scoped permissions
3. **Team Management**: Multi-team collaboration and organization
4. **Device Integration**: User-device relationships for technical operations
5. **Social Authentication**: OAuth integration and external provider support

## 🔧 Technical Implementation Patterns

### Filament Integration
- XotBaseResource foundation for all module resources
- Custom components for business-specific form and table needs
- Relationship managers for complex business relationships
- Permission integration in UI with role-based access control
- Organized navigation management

### Model Architecture
- XotBaseModel as foundation for all business models
- Trait-based functionality sharing
- Eloquent relationships with proper type safety
- Custom scopes and query patterns

### Migration Strategy
- Single migration per table in each module (Laraxot Philosophy)
- Subsequent changes use tableUpdate() instead of tableCreate()
- Column/index existence checks before modifications

## 🧪 Quality Assurance

### Testing Strategy
- **Pest**: Primary testing framework
- **Unit Tests**: Model and service layer validation
- **Feature Tests**: HTTP endpoint and workflow testing
- **Integration Tests**: Module interaction validation

### Static Analysis
- **PHPStan Level 10**: Complete type safety
- **Rector**: Automated refactoring and modernization
- **Pint**: Code style consistency
- **PHP-CS-Fixer**: Code formatting standards

## 📊 Module Interdependencies

### Dependency Hierarchy
```
TechPlanner Module (Business Layer)
├── User Module (Authentication & Authorization)
│   ├── BaseProfile implementation
│   ├── Role & Permission system
│   └── Device user relationships
├── Xot Module (Foundation Layer)
│   ├── XotBaseModel and XotBaseResource
│   ├── Contract implementations
│   └── Core architectural patterns
├── Geo Module (Geographic Services)
│   ├── Address management and parsing
│   ├── Location services integration
│   └── Geographic query scopes
└── Media Module (File Management)
    ├── Avatar and attachment handling
    ├── Media collections
    └── File upload management
```

### Integration Points
1. **TechPlanner ↔ User**: Profile extensions, device assignments, role-based access
2. **User ↔ Xot**: Contract implementations, base model extensions, resource patterns
3. **TechPlanner ↔ Xot**: Business model foundations, Filament resource patterns
4. **All Modules ↔ Geo**: Address management, location services
5. **All Modules ↔ Media**: File attachments, avatar management

## 🚀 Deployment and Environment

### Environment Configuration
- **Production**: Optimized for performance and security
- **Staging**: Mirror of production for testing
- **Development**: Debug mode enabled with detailed logging

### Deployment Requirements
- PHP 8.2+
- Composer for dependency management
- Node.js & npm for asset compilation
- MySQL/PostgreSQL for primary database
- Redis for caching and queues
- Multi-tenant database support

## 📚 Documentation Structure

### Core Documentation Locations
- **Main Docs**: `/docs/` - Project-wide architecture and guidelines
- **Module Docs**: `/Modules/{Module}/docs/` - Module-specific documentation
- **Architecture**: `/docs/architecture/` - System architecture patterns
- **Patterns**: `/docs/patterns/` - Reusable development patterns
- **Quality**: `/docs/code-quality/` - Code quality and standards

### Documentation Standards
- Comprehensive business logic documentation
- Technical implementation details
- Migration guides and best practices
- Error prevention and troubleshooting guides

---

## 🎯 Future Considerations

### Architecture Evolution
1. **API Development**: Comprehensive REST/GraphQL API endpoints
2. **Performance Optimization**: Advanced caching and query optimization
3. **Security Enhancement**: Advanced authentication and authorization patterns
4. **Scalability**: Horizontal scaling and microservice patterns

### Documentation Expansion
1. **API Documentation**: Complete endpoint documentation
2. **Integration Guides**: Third-party service integration patterns
3. **Performance Guides**: Optimization techniques and best practices
4. **Security Guidelines**: Security implementation and compliance guides

This comprehensive overview provides the foundation for understanding the TechPlanner Laravel architecture and serves as a reference for ongoing development and maintenance.