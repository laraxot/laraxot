# Filament Integration Patterns and XotBase Extension System

**Last Updated**: 2025-12-05
**Status**: Complete Filament Integration Guide

## 🏗️ XotBase Extension Architecture

### Core Philosophy
The TechPlanner application implements a robust extension architecture that prevents direct extension of Filament classes. Instead, all Filament functionality is accessed through XotBase (for non-multilingual modules) or LangBase (for multilingual modules) abstract classes.

### Extension Pattern
```php
// ❌ WRONG - Never extend Filament classes directly
use Filament\Resources\Pages\ListRecords;
class MyPage extends ListRecords { }

// ✅ FOR NON-MULTILINGUAL MODULES - Always extend XotBase
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class MyPage extends XotBaseListRecords { }

// ✅ FOR MULTILINGUAL MODULES (Cms, Blog, News) - Use LangBase
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
class MyPage extends LangBaseListRecords { }
```

## 🧱 XotBase Foundation Classes

### Available XotBase Classes
#### Pages
- `XotBaseListRecords` - List page with tenant isolation
- `XotBaseCreateRecord` - Create record page
- `XotBaseEditRecord` - Edit record page  
- `XotBaseViewRecord` - View record page
- `XotBaseManageRecords` - Manage related records

#### Resources
- `XotBaseResource` - Base resource class with tenant awareness
- `XotBaseRelationManager` - Relationship management with tenant isolation

#### Widgets
- `XotBaseWidget` - Base widget class
- `XotBaseChartWidget` - Chart widget with tenant data isolation

### LangBase Classes (Multilingual Support)
- `LangBaseListRecords` - List with translation support
- `LangBaseCreateRecord` - Create with translation support
- `LangBaseEditRecord` - Edit with translation support
- `LangBaseViewRecord` - View with translation support
- `LangBaseResource` - Resource with translation support

## 🎨 Filament Integration Patterns

### 1. Resource Implementation Pattern
```php
<?php

namespace Modules\TechPlanner\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\TechPlanner\Filament\Resources\Project\Pages;
use Modules\TechPlanner\Models\Project;

class ProjectResource extends XotBaseResource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
```

### 2. Page Implementation Pattern
```php
<?php

namespace Modules\TechPlanner\Filament\Resources\Project\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListProjects extends XotBaseListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
```

### 3. Form Schema Pattern
```php
<?php

namespace Modules\TechPlanner\Filament\Resources\Project;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Modules\Xot\Actions\Filament\GetModelLabelAction;

class ProjectForm
{
    public static function schema(): array
    {
        return [
            Section::make(
                app(GetModelLabelAction::class)->execute(
                    class_basename(Project::class),
                    'attributes'
                )
            )
                ->schema([
                    TextInput::make('name')
                        ->label(
                            app(GetModelLabelAction::class)->execute(
                                class_basename(Project::class),
                                'attributes.name'
                            )
                        )
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(2),
        ];
    }
}
```

## 🔐 Multi-Tenant Integration

### Tenant-Aware Filament Components
All XotBase classes automatically handle tenant context:

1. **Data Isolation**: Queries automatically scoped to current tenant
2. **Configuration Loading**: Tenant-specific configurations applied
3. **Permission Checks**: Tenant-based permission validation
4. **Caching**: Tenant-specific cache keys

### Tenant Context in Filament Pages
```php
// XotBase classes automatically handle tenant switching
protected function getTableQuery(): Builder
{
    // Automatically scoped to current tenant
    return parent::getTableQuery();
}

protected function fillForm(): void
{
    // Tenant context automatically applied
    parent::fillForm();
}
```

## 🧩 Service Provider Integration

### Filament Panel Configuration
XotBase classes integrate with the panel provider system:

```php
// In Modules\Xot\Providers\Filament\AdminPanelProvider
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->tenant($this->getTenantClass())
        ->tenantRegistration($this->getTenantRegistrationClass())
        ->spa()
        ->resources([
            // XotBase resources automatically registered
        ])
        ->pages([
            // XotBase pages automatically available
        ])
        ->widgets([
            // XotBase widgets automatically loaded
        ]);
}
```

## 🚨 Critical Implementation Rules

### 1. Method Signature Compliance
```php
// ❌ WRONG - Static vs non-static mismatch
public static function getNavigationLabel(): string { }  // Parent method is non-static

// ✅ CORRECT - Match parent signature exactly
public function getNavigationLabel(): string { }
```

### 2. Abstract Method Implementation
```php
// ❌ WRONG - Missing required abstract methods
class MyPage extends XotBaseListRecords
{
    // Missing getModel() method
}

// ✅ CORRECT - All abstract methods implemented
class MyPage extends XotBaseListRecords
{
    protected static string $resource = ProjectResource::class;
    
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery();
    }
}
```

### 3. Resource Registration Pattern
```php
// Use XotBase resource classes
use Modules\Xot\Filament\Resources\XotBaseResource;

class ProjectResource extends XotBaseResource
{
    protected static ?string $model = Project::class;
    
    // Resource automatically integrated with tenant system
}
```

## 🔧 Extension Points and Customization

### 1. Form Customization
```php
class CustomizedProjectResource extends XotBaseResource
{
    public static function form(Form $form): Form
    {
        return $form->schema(
            // Use XotBase form patterns
            ProjectForm::schema()
        );
    }
}
```

### 2. Table Customization
```php
protected function getTableColumns(): array
{
    return [
        Tables\Columns\TextColumn::make('name')
            ->searchable(),
        Tables\Columns\TextColumn::make('created_at')
            ->dateTime(),
    ];
}
```

### 3. Action Customization
```php
protected function getHeaderActions(): array
{
    return [
        Actions\CreateAction::make()
            ->using(function (array $data) {
                // Tenant context automatically applied
                return static::getModel()::create($data);
            }),
    ];
}
```

## 🧪 Testing Filament Components

### Tenant-Aware Testing
```php
// Test tenant isolation in Filament components
public function test_tenant_data_isolation(): void
{
    // Switch to specific tenant context
    $tenant = Tenant::factory()->create();
    $tenant->makeCurrent();
    
    // Test that Filament pages only show tenant data
    $response = $this->get('/admin/projects');
    $response->assertSee($tenant->projects->first()->name);
}
```

## 🚀 Performance Optimization

### 1. Eager Loading
XotBase classes support eager loading optimization:

```php
protected function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->with(['tenant', 'related_models']);
}
```

### 2. Caching Strategies
```php
// XotBase classes include built-in caching
protected function getTableRecords(): Collection
{
    return Cache::remember(
        "tenant.{$this->tenant->id}.records",
        now()->addHour(),
        fn() => parent::getTableRecords()
    );
}
```

## 📋 Migration Guide from Direct Filament Usage

### Before (Direct Extension - ❌)
```php
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
}
```

### After (XotBase Extension - ✅)
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListProjects extends XotBaseListRecords
{
    protected static string $resource = ProjectResource::class;
}
```

## 🛡️ Security Considerations

### 1. Data Isolation
- All queries automatically scoped to tenant
- Cross-tenant data access prevented
- Permission checks enforced at model level

### 2. Input Validation
- Form inputs validated against tenant context
- File uploads scoped to tenant storage
- API endpoints protected by tenant middleware

### 3. Session Management
- Tenant context maintained across requests
- Session isolation between tenants
- Authentication tied to tenant context

## 📚 Best Practices

### 1. Consistent Extension
- Always use XotBase or LangBase classes
- Never extend Filament classes directly
- Follow established naming conventions

### 2. Tenant Awareness
- Consider tenant context in all operations
- Use tenant-scoped services
- Implement proper tenant validation

### 3. Type Safety
- Maintain PHPStan Level 10 compliance
- Use proper return types
- Implement strict typing

### 4. Testing
- Test tenant isolation thoroughly
- Verify cross-tenant data protection
- Validate performance under load

This comprehensive guide provides the foundation for implementing and maintaining Filament components using the XotBase extension system in the TechPlanner application.