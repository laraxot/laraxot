# Documentation Style Guide for Modules

## File Naming Conventions

1. **Use lowercase with hyphens** for all markdown files
   - ✅ `user-management.md`
   - ❌ `user_management.md`
   - ❌ `User-Management.md`

2. **Exceptions** - Only these files may contain uppercase letters:
   - `README.md`
   - `CHANGELOG.md`

3. **Be descriptive but concise** - File names should clearly indicate content
   - ✅ `filament-resource-configuration.md`
   - ❌ `frc.md`

## File Organization

1. **Main documentation files** (placed at root of docs folder):
   - `README.md` - Module overview and quick start
   - `installation.md` - Installation instructions
   - `configuration.md` - Configuration options
   - `api-reference.md` - API documentation
   - `examples.md` - Usage examples
   - `troubleshooting.md` - Common issues and solutions

2. **Subdirectories** for organized content:
   - `architecture/` - Architecture decisions and patterns
   - `best-practices/` - Best practice guides
   - `development/` - Development guides
   - `integration/` - Integration with other modules
   - `performance/` - Performance optimization
   - `testing/` - Testing documentation

## Content Guidelines

1. **Use clear headings** with Markdown `#` syntax
2. **Include table of contents** for longer documents
3. **Use code blocks** with syntax highlighting
4. **Add examples** for complex concepts
5. **Include links** to related documentation

## Language

1. **Write in English** for technical documentation
2. **Use Italian** when documenting Italian-specific features
3. **Be consistent** with terminology throughout

## Links and References

1. **Use relative links** for internal documentation
2. **Include full URLs** for external resources
3. **Check links regularly** to ensure they're valid

## Review Process

1. **Peer review** all documentation changes
2. **Test code examples** before including them
3. **Update documentation** with code changes
4. **Remove outdated content** regularly

## Templates

### README.md Template
```markdown
# [Module Name]

## Description
[Brief description of the module's purpose]

## Installation
[Installation instructions]

## Quick Start
[Basic usage example]

## Features
[List of main features]

## Documentation
- [Configuration](configuration.md)
- [API Reference](api-reference.md)
- [Examples](examples.md)
- [Troubleshooting](troubleshooting.md)

## Requirements
[List of dependencies]

## Contributing
[Guidelines for contributors]
```

### Configuration File Template
```markdown
# Configuration

## Basic Configuration
[Basic settings]

## Advanced Options
[Advanced configuration]

## Environment Variables
[List of environment variables]

## Examples
[Configuration examples]
```

## Maintenance

1. **Regular cleanup** of outdated files
2. **Consistency checks** for naming conventions
3. **Link validation** for internal and external links
4. **Content updates** with each release