# Filament Badge Usage Guide

## Overview
This guide outlines the correct way to implement badges in Filament tables, particularly for status or state representations.

## ❌ Common Mistakes to Avoid

### Using Deprecated BadgeColumn
```php
// INCORRECT - BadgeColumn is deprecated
BadgeColumn::make('status')  // Do not use BadgeColumn
```

## ✅ Correct Implementation

### Using TextColumn with badge()
```php
TextColumn::make('status')
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'active' => 'Active',
        'inactive' => 'Inactive',
        default => $state,
    })
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'active' => 'success',
        'inactive' => 'danger',
        default => 'secondary',
    })
```

### Complete Example with Status Colors
```php
TextColumn::make('status')
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'scheduled' => 'Scheduled',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        default => $state,
    })
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'scheduled' => 'warning',
        'in_progress' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
        default => 'secondary',
    })
```

## Best Practices

1. **State Formatting**
   - Always use `formatStateUsing()` with match expressions
   - Include a default case to handle unexpected states
   - Keep formatting logic clean and readable

2. **Color Application**
   - Use closure-based color conditions
   - Match colors to semantic meaning
   - Use standard Filament color options:
     * primary
     * secondary
     * success
     * warning
     * danger
     * info

3. **Code Organization**
   - Group related states together
   - Maintain consistent formatting
   - Document any complex state logic

## Examples

### Simple Status Badge
```php
'status' => TextColumn::make('status')
    ->formatStateUsing(fn (string $state): string => ucfirst($state))
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'active' => 'primary',
        'inactive' => 'danger',
        default => 'secondary',
    })
```

### Complex State Badge
```php
'process_status' => TextColumn::make('process_status')
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'pending_review' => 'Pending Review',
        'in_progress' => 'In Progress',
        'under_revision' => 'Under Revision',
        'completed' => 'Completed',
        'rejected' => 'Rejected',
        default => $state,
    })
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'pending_review', 'under_revision' => 'warning',
        'in_progress' => 'primary',
        'completed' => 'success',
        'rejected' => 'danger',
        default => 'secondary',
    })
```

## Related Documentation
- Filament Table Columns Structure
- UI/UX Best Practices
- State Management Guidelines
