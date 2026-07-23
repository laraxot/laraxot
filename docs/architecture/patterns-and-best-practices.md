# Architecture Patterns and Best Practices

**Last Updated**: 2025-12-05  
**Status**: Current Architecture Patterns and Best Practices Guide

## 🏗️ Current Architecture Patterns

### 1. **XotBase Extension Pattern (CRITICAL)**
All Filament classes must extend XotBase or LangBase abstract classes instead of direct Filament classes:

```php
// ❌ Wrong - Never extend Filament classes directly
use Filament\Resources\Pages\ListRecords;
class MyPage extends ListRecords { }

// ✅ For non-multilingual modules
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class MyPage extends XotBaseListRecords { }

// ✅ For multilingual modules (Cms, Blog, News)
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
class MyPage extends LangBaseListRecords { }
```

### 2. **Method Signature Compliance (CRITICAL)**
Always match parent/trait method signatures exactly - static vs non-static matters:

```php
// ❌ Wrong - Method signature mismatch
class MyClass extends XotBaseClass {
    public static function route() { } // If parent method is not static
}

// ✅ Correct - Exact signature match
class MyClass extends XotBaseClass {
    public function route() { } // Matches parent signature exactly
}
```

### 3. **Abstract Method Implementation (CRITICAL)**
ALL abstract methods from parent classes and traits MUST be implemented:

```php
// ❌ Wrong - Missing abstract method implementation
abstract class ParentClass {
    abstract public function requiredMethod();
}

class ChildClass extends ParentClass {
    // Missing requiredMethod implementation
}

// ✅ Correct - All abstract methods implemented
class ChildClass extends ParentClass {
    public function requiredMethod() {
        // Implementation provided
    }
}
```

### 4. **Contract-Based Architecture**
Use interface contracts for type safety and consistent behavior:

```php
// Define contracts for consistent interfaces
interface UserContract {
    public function getName(): string;
    public function getEmail(): string;
}

// Implement contracts for type safety
class UserModel implements UserContract {
    public function getName(): string { /* implementation */ }
    public function getEmail(): string { /* implementation */ }
}
```

### 5. **Multi-Tenant Architecture Patterns**
Always consider tenant context in data operations:

```php
// ❌ Wrong - No tenant context consideration
User::find($id);

// ✅ Correct - Tenant-aware query
TenantUser::find($id); // Model with tenant scope
// or
User::where('tenant_id', tenant()->id)->find($id);
```

## 🚨 Critical Architecture Rules

### 1. **XotBase/LangBase Rule (MANDATORY)**
- **NEVER** extend Filament classes directly
- **ALWAYS** extend XotBase OR LangBase abstract classes
- **Check first** if module is multilingual (use LangBase) or not (use XotBase)

### 2. **Method Signature Rule (CRITICAL)**
- **ALWAYS** match parent/trait method signatures exactly
- **Static vs Non-Static** matters and must match
- **Parameter types and return types** must match exactly

### 3. **Abstract Method Rule**
- **ALL** abstract methods from parent classes and traits MUST be implemented
- **No exceptions** - PHP will throw fatal errors otherwise

### 4. **Module Registration Philosophy**
In a module, for each table there must be only ONE migration responsible for its creation. Multiple migrations for the same table in the same module is a violation of Laraxot philosophy. Subsequent migrations should extend existing tables using tableUpdate() rather than recreating them with tableCreate(). Always use hasColumn(), hasTable(), hasIndex() for safe checks.

## 🔧 Modern Development Patterns

### 1. **Action Pattern Usage**
NEVER use Services directly in controllers - ALWAYS use Actions instead:

```php
// ❌ Wrong - Direct service usage in controller
class UserController extends Controller {
    public function index(UserService $service) {
        return $service->getAllUsers();
    }
}

// ✅ Correct - Action pattern
class UserController extends Controller {
    public function index() {
        return UserListAction::execute();
    }
}

// Actions have static execute method and are used directly
class UserListAction 
{
    public static function execute() {
        // Business logic here
    }
}
```

### 2. **Eloquent Model Best Practices**
NEVER use property_exists() on Eloquent models - use hasAttribute(), isFillable() or Schema::hasColumn() instead, because model attributes are magical:

```php
// ❌ Wrong - property_exists() on Eloquent models
if (property_exists($model, 'custom_field')) { }

// ✅ Correct - Eloquent-specific methods
if ($model->hasAttribute('custom_field')) { }
// or
if (Schema::hasColumn($model->getTable(), 'custom_field')) { }
// or
if ($model->isFillable('custom_field')) { }
```

### 3. **Service Provider Architecture**
Use the XotBaseServiceProvider pattern for consistent module loading:

```php
class MyModuleServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'MyModule'; // Module name
    protected string $module_dir = __DIR__; // Module directory
    protected string $module_ns = __NAMESPACE__; // Module namespace

    public function boot(): void {
        parent::boot(); // Always call parent boot
        // Module-specific boot logic
    }

    public function register(): void {
        parent::register(); // Always call parent register
        // Module-specific registration
    }
}
```

## 🎨 Filament Integration Best Practices

### 1. **Resource Extension Pattern**
Use XotBaseResource for all module resources:

```php
// ✅ Correct - XotBaseResource extension
use Modules\Xot\Filament\Resources\XotBaseResource;

class UserResource extends XotBaseResource {
    // Implementation using XotBase patterns
}
```

### 2. **Component Configuration**
Use configureUsing pattern for component customization:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::configureUsing(
    fn (DatePicker $component) => $component
        ->timezone(config('app.timezone'))
        ->displayFormat(config('app.date_format', 'd/m/Y'))
);
```

### 3. **Localization Integration**
Ensure all components respect localization:

```php
// Components automatically translate labels
Field::configureUsing(function (Component $component): void {
    $component->translateLabel();
});
```

## 🧪 Quality Assurance Patterns

### 1. **Type Safety Standards**
- **PHPStan Level 10**: Complete type safety compliance
- **Strict Types**: All files use `declare(strict_types=1)`
- **Type Hints**: Comprehensive parameter and return type declarations

### 2. **Testing Patterns**
- **Unit Tests**: Model and service layer validation
- **Feature Tests**: HTTP endpoint and workflow testing
- **Integration Tests**: Module interaction validation
- **Multi-tenant Testing**: Tenant isolation verification

### 3. **Code Quality Tools**
- **PHPStan**: Level 10 type safety analysis
- **PHPMD**: Code quality and design rule checking
- **PHPInsights**: Comprehensive code quality analysis
- **Rector**: Automated refactoring and modernization

## 📚 Documentation Standards

### 1. **File Naming Conventions**
- **All .md files** in lowercase (except README.md, CHANGELOG.md)
- **Module documentation** in `/Modules/{Module}/docs/`
- **Architecture documentation** in `/docs/architecture/`
- **Pattern documentation** in `/docs/patterns/`

### 2. **Documentation Structure**
- **Business context** linked to technical implementation
- **Examples** with real-world usage scenarios
- **Best practices** with anti-pattern examples
- **Troubleshooting** sections for common issues

## 🚀 Performance Optimization Patterns

### 1. **Database Query Optimization**
- **Eager loading**: Use `with()` to prevent N+1 queries
- **Indexing**: Proper database indexes for query performance
- **Caching**: Strategic caching for expensive operations
- **Pagination**: Efficient data pagination for large datasets

### 2. **Memory Management**
- **Filament optimization**: Optimize resource loading
- **Lazy loading**: Load components only when needed
- **Session management**: Efficient session data storage
- **Garbage collection**: Proper memory management

### 3. **Asset Optimization**
- **Vite integration**: Modern asset compilation
- **CDN usage**: Static asset distribution
- **Compression**: Asset minification and compression
- **Caching**: Browser and server-side caching strategies

## 🔐 Security Best Practices

### 1. **Multi-tenant Security**
- **Data isolation**: Complete tenant data separation
- **Access controls**: Tenant-specific permissions
- **Context validation**: Verify tenant context in all operations
- **Cross-tenant protection**: Prevent unauthorized access

### 2. **Input Validation**
- **Sanitization**: Clean all user inputs
- **Validation**: Comprehensive input validation
- **Type safety**: Strict data type enforcement
- **SQL injection protection**: Use query builders and parameter binding

### 3. **Authentication & Authorization**
- **Role-based access**: Permission-based access control
- **Session security**: Secure session management
- **API security**: Token-based authentication
- **Rate limiting**: Prevent abuse and DoS attacks

## 🔄 Deployment Patterns

### 1. **Environment Configuration**
- **Environment-specific settings**: Different configs for dev/stage/prod
- **Secrets management**: Secure handling of sensitive data
- **Database configuration**: Proper connection settings
- **Cache configuration**: Optimized cache settings per environment

### 2. **Deployment Strategies**
- **Zero-downtime deployment**: Blue-green deployment patterns
- **Database migrations**: Safe migration execution
- **Asset compilation**: Proper asset building and deployment
- **Health checks**: Application monitoring and validation

## 🧩 Module Development Standards

### 1. **Module Structure Consistency**
- **Standard directories**: app/, config/, database/, docs/, etc.
- **Service provider**: Proper module registration
- **Migrations**: Single migration per table philosophy
- **Documentation**: Comprehensive module documentation

### 2. **Module Interactions**
- **Dependency management**: Clear module dependencies
- **Service registration**: Proper service provider configuration
- **Event handling**: Module-specific events and listeners
- **Communication patterns**: Clean module-to-module communication

## 🧠 Continuous Improvement Patterns

### 1. **Code Review Standards**
- **Architecture compliance**: Verify pattern adherence
- **Security checks**: Identify potential vulnerabilities
- **Performance considerations**: Optimize for efficiency
- **Documentation**: Ensure comprehensive documentation

### 2. **Refactoring Guidelines**
- **DRY principle**: Eliminate code duplication
- **KISS principle**: Keep it simple and straightforward
- **SOLID principles**: Maintain object-oriented design principles
- **Performance optimization**: Continuous performance improvements

This comprehensive guide represents the current architecture patterns and best practices for the TechPlanner Laravel application. Regular updates should be made as new patterns emerge and best practices evolve.