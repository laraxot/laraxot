# Filament Resources Guidelines

## Resource Class Structure

### XotBaseResource Extension
Resources that extend `XotBaseResource` should follow these guidelines:

1. **Navigation Icon**
   - Do NOT define `protected static ?string $navigationIcon`
   - Navigation icons are managed by the base resource class

2. **Required Methods**
   - Must implement `getFormSchema(): array`
   - Should implement `getRelations(): array`
   - Should implement `getPages(): array`

3. **Form Schema**
   - Use proper form components with complete configuration
   - Include validation rules
   - Add helpful labels and descriptions

4. **Code Organization**
   - Keep imports organized and grouped by purpose
   - Maintain consistent method ordering
   - Follow PSR coding standards

## Affected Resources
The following resources extend XotBaseResource and should follow these guidelines:
- `ActivityResource`
- `SnapshotResource`
- `StoredEventResource`
- `NotificationResource`
- `ContactResource`

## Best Practices
1. Always check the parent class before defining properties
2. Maintain consistent implementation across all resources
3. Document any deviations from these guidelines
4. Keep form schemas well-documented and organized
