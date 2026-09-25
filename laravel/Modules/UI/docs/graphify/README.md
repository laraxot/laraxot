# Graphify Knowledge Graph

## Overview

This directory contains the knowledge graph for the **UI** module, generated using [Graphify](https://graphify.dev/). The graph provides a comprehensive visualization of code dependencies, architecture, and relationships within the module.

## Quick Start

### View the Graph

1. **Open in Graphify Visualizer**
   ```bash
   cd graphify-out
   graphify visualize .
   ```

2. **Analyze Graph Statistics**
   - **Nodes**: 1312 (code entities: classes, functions, types)
   - **Edges**: 1177 (dependencies and relationships)
   - **Communities**: 548 (logical clusters of related code)

### Key Files

- **graph.json** — Full knowledge graph in JSON format
- **.graphify_analysis.json** — Analysis metadata and statistics
- **GRAPH_REPORT.md** — Generated community names and cluster analysis

## Graph Interpretation

The knowledge graph represents:

- **Nodes**: Classes, interfaces, functions, exports, and other code entities
- **Edges**: Import statements, function calls, type references, and dependencies
- **Communities**: Automatically detected clusters of related functionality

## Use Cases

- **Dependency Analysis**: Understand what each component depends on
- **Architecture Discovery**: Identify logical module boundaries and communication patterns
- **Refactoring**: Find high-coupling areas for potential optimization
- **Onboarding**: Quickly grasp the module's code structure and relationships
- **Impact Analysis**: Trace how changes propagate through the codebase

## Generating Updated Graphs

To regenerate the knowledge graph after code changes:

```bash
graphify . --code-only --output docs/graphify/graphify-out
```

To generate community analysis and GRAPH_REPORT.md:

```bash
graphify cluster-only docs/graphify/graphify-out
```

## Documentation Integration

For more information about this module, see:
- Module documentation in the main README
- Architecture decisions in docs/architecture/
- API specifications in docs/api/

## References

- [Graphify Documentation](https://graphify.dev/)
- [Module Structure Guidelines](../../../../docs/wiki/rules/module-structure.md)

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_QNBexX
=======
<<<<<<< .merge_file_AVaSlz
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
>>>>>>> .merge_file_LW136C
>>>>>>> .merge_file_YwTn4e
=======
>>>>>>> 804451c (Lint)
[![Module](https://img.shields.io/badge/Module-Graphify Knowledge Graph-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |

---

**Modulo** `UI` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
<<<<<<< HEAD
<<<<<<< .merge_file_QNBexX
=======
>>>>>>> laraxot/dev
=======
<<<<<<< .merge_file_AVaSlz
=======
>>>>>>> laraxot/dev
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> .merge_file_LW136C
>>>>>>> .merge_file_YwTn4e
=======
=======
>>>>>>> laraxot/dev
>>>>>>> 804451c (Lint)
