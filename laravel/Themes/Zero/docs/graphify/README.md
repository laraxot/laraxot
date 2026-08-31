# Graphify Knowledge Graph

## Overview

This directory contains the knowledge graph for the **Zero** theme, generated using [Graphify](https://graphify.dev/). The graph provides a comprehensive visualization of code dependencies, architecture, and relationships within the theme.

## Quick Start

### View the Graph

1. **Open in Graphify Visualizer**
   ```bash
   cd graphify-out
   graphify visualize .
   ```

2. **Analyze Graph Statistics**
   - **Nodes**: 334 (code entities: components, styles, templates)
   - **Edges**: 588 (dependencies and relationships)
   - **Communities**: 48 (logical clusters of theme functionality)

### Key Files

- **graph.json** — Full knowledge graph in JSON format
- **.graphify_analysis.json** — Analysis metadata and statistics
- **GRAPH_REPORT.md** — Generated community names and cluster analysis

## Graph Interpretation

The knowledge graph represents:

- **Nodes**: Components, styles, templates, assets, and other theme entities
- **Edges**: Component dependencies, style imports, template relationships
- **Communities**: Automatically detected clusters of related theme code

## Use Cases

- **Component Discovery**: Find all components and their relationships
- **Styling Architecture**: Understand style inheritance and dependencies
- **Theme Customization**: Identify which components to modify for custom themes
- **Consistency Analysis**: Ensure consistent patterns across theme components
- **Performance Optimization**: Find unused styles and components

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

For more information about this theme, see:
- Theme documentation in the main README
- Component specifications in components/
- Style guidelines in styles/

## References

- [Graphify Documentation](https://graphify.dev/)
- [Theme Structure Guidelines](../../../../docs/wiki/rules/theme-structure.md)

