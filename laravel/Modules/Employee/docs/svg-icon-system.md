# SVG Icon System - Employee Module

## Overview

The Employee module implements a custom SVG icon system that automatically registers and manages module-specific icons through the Laraxot framework.

## How It Works

### 1. Automatic Registration
The `XotBaseServiceProvider` automatically scans and registers SVG icons:

```php
// XotBaseServiceProvider.registerBladeIcons() - lines 61-75
public function registerBladeIcons(): void
{
    $this->callAfterResolving(BladeIconsFactory::class, function (BladeIconsFactory $factory) {
        $assetsPath = app(GetModulePathByGeneratorAction::class)->execute($this->name, 'assets');
        $svgPath = $assetsPath.'/../svg';
        try {
            $factory->add($this->nameLower, ['path' => $svgPath, 'prefix' => $this->nameLower]);
        } catch (\Throwable $e) {
            // Ignore missing SVG path
        }
    });
}
```

### 2. Directory Structure
```
Modules/Employee/
└── resources/
    └── svg/
        ├── employee-icon.svg    # Original employee icon
        └── icon2.svg           # Alternative icon (employee-icon2)
```

### 3. Naming Convention
- **SVG Files**: `{filename}.svg` in `resources/svg/`
- **Icon Names**: `{module-name}-{filename}` (e.g., `employee-icon2`)
- **Module Prefix**: Automatically uses lowercase module name (`employee`)

### 4. Configuration Usage
In `config/config.php`:
```php
'icon' => 'employee-icon2',  // References resources/svg/icon2.svg
```

## Icon Features

### Animated SVG Support
Both icons include CSS animations and hover effects:

```css
@keyframes employee-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
}

svg:hover .employee-group {
    animation: employee-pulse 2s ease-in-out infinite;
}
```

### Design Elements
- **Employee Icon**: Group of employees with ID badge
- **Icon2**: Single employee with detailed ID badge and status indicator
- **Responsive**: Scales with container using `viewBox="0 0 24 24"`
- **Accessible**: Includes `aria-hidden="true"` and `role="img"`

## Usage Examples

### In Blade Templates
```blade
<x-icon name="employee-icon2" class="w-6 h-6" />
<x-icon name="employee-employee-icon" class="w-8 h-8" />
```

### In Filament Resources
```php
protected static ?string $navigationIcon = 'employee-icon2';
```

### In Configuration
```php
'icon' => 'employee-icon2',
'navigation' => [
    'icon' => 'employee-employee-icon',
],
```

## Adding New Icons

### 1. Create SVG File
Place new SVG files in `Modules/Employee/resources/svg/`:
```
resources/svg/
├── new-icon.svg        # Becomes employee-new-icon
├── dashboard.svg       # Becomes employee-dashboard
└── report.svg          # Becomes employee-report
```

### 2. Use in Configuration
```php
'icon' => 'employee-new-icon',
```

### 3. Auto-Registration
Icons are automatically registered when the module boots - no manual registration required.

## Best Practices

### SVG Optimization
- Use `viewBox="0 0 24 24"` for consistency
- Include `stroke="currentColor"` for theme compatibility
- Add `fill="none"` for outline icons
- Optimize with SVGO for smaller file sizes

### Naming Conventions
- Use descriptive filenames: `dashboard.svg`, `report.svg`, `settings.svg`
- Avoid spaces and special characters
- Use kebab-case for multi-word names: `user-profile.svg`

### Accessibility
```xml
<svg xmlns="http://www.w3.org/2000/svg" 
     fill="none" 
     viewBox="0 0 24 24" 
     stroke="currentColor"
     stroke-width="1.5"
     aria-hidden="true" 
     role="img">
```

## Troubleshooting

### Icon Not Showing
1. Check file exists in `resources/svg/`
2. Verify filename matches config reference
3. Clear cache: `php artisan cache:clear`
4. Check SVG syntax is valid

### Wrong Icon Displayed
1. Verify prefix: `employee-{filename}`
2. Check for naming conflicts
3. Restart development server

### Animation Issues
1. Ensure CSS is properly scoped
2. Check for conflicting styles
3. Verify browser support for CSS animations

## Integration with Laraxot

### XotBaseServiceProvider Integration
The system integrates seamlessly with Laraxot's module architecture:
- Automatic discovery and registration
- Module-specific prefixing prevents conflicts
- Graceful fallback if SVG directory doesn't exist

### BladeIconsFactory
Uses Laravel's Blade Icons package for:
- Icon set management
- Prefix handling
- Template rendering
- Cache optimization

## Performance Considerations

### Caching
- Icons are cached by BladeIconsFactory
- SVG content is loaded once per request
- No database queries required

### Optimization
- SVG files should be optimized for web
- Use CSS for animations instead of JavaScript
- Minimize SVG complexity for better performance

## Links and References

- [Blade Icons Documentation](https://github.com/blade-ui-kit/blade-icons)
- [SVG Optimization Guide](https://web.dev/optimize-svgs/)
- [Laraxot Module System](../README.md)
- [XotBaseServiceProvider](../../app/Providers/EmployeeServiceProvider.php)
