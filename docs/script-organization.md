# Script Organization Rules

**Date**: 2025-12-18
**Context**: "Super Cow" Mode

## Overview
To maintain a clean and organized codebase, all utility scripts must be stored in the `bashscripts/` directory, categorized by their function. Chaos in the root directory or `docs/` folder is strictly forbidden.

## Directory Structure

```text
bashscripts/
├── fix/              # Scripts that perform automated fixes (migrations, syntax, traits)
├── testing/          # Scripts related to testing (pest conversions, fixes)
├── utilities/        # General dev utilities
├── mcp/              # MCP server configurations and setup scripts
└── deploy/           # Deployment related scripts (if any)
```

## Guidelines

1.  **Strict Categorization**: Do not dump scripts in `bashscripts/` root. Choose a subfolder.
2.  **Naming Convention**: Use `snake_case` for script names (e.g., `fix_migration_syntax.sh`).
3.  **Permissions**: Ensure scripts are executable (`chmod +x`).
4.  **Documentation**: Each script should have a header comment explaining its purpose.
5.  **No Root Scripts**: The `laravel/` root directory must remain clean of `.sh` or `.py` files.

## Legacy Scripts
If you find legacy scripts in `docs/` or `laravel/`, move them immediately to the appropriate `bashscripts/` subdirectory.

## Organization Script

To automatically organize scripts in `bashscripts/`, use:
```bash
./bashscripts/utilities/organize_bashscripts.sh
```

This script will:
- Move fix scripts to `fix/`
- Move testing scripts to `testing/`
- Move analysis scripts to `analysis/`
- Move utility scripts to `utilities/`
- Organize temporary files in `temp/`
- Handle duplicates intelligently (compare content before removing)

## Cleanup Checklist

Before committing, ensure:
- [ ] All scripts are in appropriate subdirectories
- [ ] No merge conflicts in any file (check with `grep -r "<<<<<<< HEAD" bashscripts/`)
- [ ] No merge conflicts in any file 
- [ ] `.gitignore` is clean (no conflict markers)
- [ ] Configuration files are not in `bashscripts/` root
- [ ] Scripts have shebang (`#!/bin/bash`)
- [ ] Scripts are executable (`chmod +x`)
- [ ] Scripts have header documentation
- [ ] No duplicate scripts (check with `find bashscripts -name "*.sh" -exec basename {} \; | sort | uniq -d`)

## Documentation

Complete documentation is available in `bashscripts/docs/`:
- [README.md](../bashscripts/docs/README.md) - Overview and quick reference
- [Scripts Index](../bashscripts/docs/scripts-index.md) - Complete catalog of all scripts
- [Best Practices](../bashscripts/docs/best-practices.md) - DRY+KISS+Clean Code guidelines
- [Duplicates Consolidation Plan](../bashscripts/docs/duplicates-consolidation-plan.md) - Strategy for consolidating duplicates
- [Work Summary](../bashscripts/docs/work-summary.md) - Summary of cleanup work