# Core Rules and Guidelines

## 📚 Documentation Rule (CRITICAL)

**ALWAYS remember to constantly study and update the docs folders inside modules and themes.**

This is a fundamental rule for maintaining the TechPlanner application. Documentation in the following locations serves as the project's memory:

### Module Documentation Locations
- `/Modules/{ModuleName}/docs/` - Each module must have comprehensive documentation
- All .md files should be in lowercase (except README.md)
- Documentation should reflect current implementation and best practices
- Business logic and technical implementation should be documented together

### Theme Documentation Locations  
- `/Themes/{ThemeName}/docs/` - Each theme should have documentation for UI/UX patterns
- Documentation should cover theme-specific features and customizations
- Asset compilation and styling guidelines should be included

### Documentation Maintenance Process
1. **Study**: Regularly review existing documentation for accuracy
2. **Update**: Keep documentation synchronized with code changes  
3. **Improve**: Enhance documentation based on development insights
4. **Verify**: Ensure documentation follows project conventions

## 🏗️ Architecture Rules

### Modular Design
- All modules follow the nwidart/laravel-modules structure
- Module priorities determine loading order
- Dependencies should be clearly documented

### Multi-Tenant Architecture
- Data isolation between tenants is mandatory
- Tenant context must be considered in all operations
- Cross-tenant data access is prohibited without explicit permission

### Filament Integration
- Never extend Filament classes directly
- Always extend XotBase or LangBase abstract classes
- Match parent/trait method signatures exactly

## 🧪 Quality Standards

### Code Quality
- PHPStan Level 10 compliance required
- Strict typing with `declare(strict_types=1)` everywhere
- Comprehensive type hints and return types
- Follow PSR-12 coding standards

### Testing Standards  
- Comprehensive test coverage with Pest
- Multi-tenant functionality testing
- Integration and unit test balance
- Performance and stress testing

## 🚨 Critical Development Rules

1. **Documentation First**: Always update documentation when making changes
2. **Tenant Safety**: Verify tenant context in all data operations
3. **Type Safety**: Maintain PHPStan compliance at all times
4. **Architecture Patterns**: Follow established extension and inheritance patterns
5. **Conventions**: Respect project naming and structural conventions

## 🔄 Continuous Improvement

Documentation should be a living part of the development process. As you work on the codebase, continuously update and improve documentation to reflect:

- New architectural patterns discovered
- Best practices refined through implementation
- Common issues and their solutions
- Performance optimizations and recommendations
- Security considerations and guidelines

Remember: The docs folders are the project's memory - keep them updated!