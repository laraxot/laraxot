# Documentation Structure Guide

This guide explains the standard documentation structure for all modules in the Laraxot PTVX project.

## Purpose
This standardized structure ensures:
- **Consistency**: All modules follow the same documentation pattern
- **Discoverability**: Easy to find information across modules
- **Maintainability**: Clear organization for updates
- **Completeness**: All key aspects are documented

## Standard Structure

```
docs/
├── README.md                    # Main module documentation (REQUIRED)
├── CHANGELOG.md                 # Version history (REQUIRED)
├── ROADMAP.md                   # Future plans (OPTIONAL)
├── MIGRATION_GUIDE.md           # Migration guides (OPTIONAL)
│
├── architecture/                # Architecture documentation
│   ├── README.md                # Architecture overview
│   ├── database-schema.md       # Database structure
│   ├── design-patterns.md       # Design patterns used
│   └── dependencies.md          # Module dependencies
│
├── business/                    # Business logic documentation
│   ├── README.md                # Business overview
│   ├── workflows.md             # Business workflows
│   ├── rules.md                 # Business rules
│   └── use-cases.md             # Use cases
│
├── development/                 # Developer guides
│   ├── README.md                # Development overview
│   ├── setup.md                 # Setup instructions
│   ├── contributing.md          # Contribution guidelines
│   └── examples.md              # Code examples
│
├── testing/                     # Testing documentation
│   ├── README.md                # Testing overview
│   ├── strategy.md              # Testing strategy
│   └── examples.md              # Test examples
│
├── troubleshooting/             # Troubleshooting guides
│   ├── README.md                # Troubleshooting overview
│   ├── common-issues.md         # Common problems
│   └── faq.md                   # FAQ
│
└── archive/                     # Archived documentation
    └── [old-docs]               # Deprecated or old docs
```

## File Naming Conventions

### Use Kebab-Case
- ✅ `database-schema.md`
- ✅ `business-rules.md`
- ❌ `database_schema.md`
- ❌ `DatabaseSchema.md`

### Be Descriptive
- ✅ `filament-resource-guidelines.md`
- ✅ `phpstan-compliance-report.md`
- ❌ `guide.md`
- ❌ `report.md`

### Avoid Duplicates
- ✅ ONE `README.md` per directory
- ❌ `README.md.update`
- ❌ `readme.md` (wrong case)

## Required Documents

### 1. README.md (Main Module Doc)
**Must include**:
- Business overview and purpose
- Architecture and dependencies
- Core components (models, resources, services)
- Quick start guide
- Documentation index
- Recent updates and roadmap

### 2. CHANGELOG.md
**Must include**:
- Version history
- Changes per version (Added, Changed, Fixed, Removed)
- Dates of releases

### 3. Subdirectory READMEs
Each subdirectory (architecture/, business/, etc.) should have a README.md that:
- Provides overview of that category
- Lists and describes documents in that directory
- Links to related documentation

## Content Guidelines

### Be Clear and Concise
- Use short paragraphs
- Use bullet points and lists
- Use code examples
- Use diagrams where helpful

### Keep It Updated
- Update docs when code changes
- Mark outdated sections clearly
- Archive old documentation

### Link Effectively
- Link to related docs
- Link to external resources
- Use relative paths for internal links

### Use Consistent Formatting

#### Headings
```markdown
# Main Title (H1) - Once per document
## Major Section (H2)
### Subsection (H3)
#### Detail (H4)
```

#### Code Blocks
````markdown
```php
// Code example with language specified
public function example(): string
{
    return 'value';
}
```
````

#### Emojis for Visual Cues
- 📋 Table of Contents
- 🎯 Purpose/Goals
- 🏗️ Architecture
- 🔧 Components/Tools
- 🚀 Quick Start
- 💻 Development
- 🔗 Links/References
- 🧪 Testing
- ⚠️ Warnings
- ✅ Success/Completed
- 🚧 In Progress
- 📝 Notes

## Anti-Patterns to Avoid

### ❌ Don't Create Duplicate Files
```
❌ README.md
❌ README.md.update
❌ readme.md
❌ README.MD
```

### ❌ Don't Use Inconsistent Naming
```
❌ some-file.md
❌ some_file.md
❌ SomeFile.md
```
Choose ONE style (kebab-case recommended) and stick to it.

### ❌ Don't Leave Merge Conflicts
```markdown
❌
Old content
New content
```
Always resolve conflicts completely.

### ❌ Don't Write Monolithic Docs
- Split large documents into logical subdocuments
- Use a main README that links to detailed docs

### ❌ Don't Forget Links
- Always provide navigation (back to parent docs)
- Link to related modules
- Link to external dependencies

## Migration from Old Structure

If you have existing documentation that doesn't follow this structure:

1. **Audit Current Docs**: List all existing documentation files
2. **Categorize Content**: Determine which category each doc belongs to
3. **Consolidate Duplicates**: Merge duplicate content
4. **Reorganize**: Move files to appropriate directories
5. **Update Links**: Fix all internal links
6. **Archive Old Docs**: Move deprecated docs to archive/
7. **Test Navigation**: Verify all links work

## Tools for Documentation

### Markdown Linting
```bash
# Use markdownlint for consistency
npm install -g markdownlint-cli
markdownlint Modules/[ModuleName]/docs/**/*.md
```

### Link Checking
```bash
# Check for broken links
npm install -g markdown-link-check
markdown-link-check Modules/[ModuleName]/docs/README.md
```

### Documentation Generation
```bash
# Generate model documentation
php artisan docs:models [ModuleName]

# Generate API documentation
php artisan docs:api [ModuleName]
```

## Examples

See these modules for good documentation examples:
- **TechPlanner**: Comprehensive business documentation
- **Xot**: Technical foundation documentation
- **User**: Authentication system documentation

## Questions?

If you have questions about documentation structure:
1. Check this guide first
2. Look at example modules (TechPlanner, Xot, User)
3. Ask the team lead
4. Propose improvements via pull request

---

**Remember**: Good documentation is code that never needs debugging!
