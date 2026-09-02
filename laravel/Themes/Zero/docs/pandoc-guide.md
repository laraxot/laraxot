---
title: Pandoc Documentation Generation Guide
description: Generate theme documentation in multiple formats using Pandoc
---

# Pandoc Documentation Generation — Theme

This guide explains how to generate distributable theme documentation using Pandoc.

## Installation

Pandoc is installed at `~/.local/bin/pandoc`. Verify:

```bash
pandoc --version
# pandoc 3.10.1
# Features: +server +lua
```

## Generate HTML

Convert theme documentation to standalone HTML:

```bash
# Single file
pandoc -s -t html docs/README.md -o docs/README.html

# With table of contents
pandoc -s --toc -N -t html docs/README.md -o docs/README.html

# With theme CSS styling
pandoc -s -c assets/style.css -t html docs/README.md -o docs/README.html
```

## Generate PDF

Convert theme documentation to PDF:

```bash
# Basic PDF
pandoc docs/README.md -o docs/README.pdf

# PDF with TOC and styled sections
pandoc -N --toc -V geometry:margin=1in docs/README.md -o docs/README.pdf
```

## Batch Generate All Formats

Generate HTML and PDF for all theme documentation:

```bash
#!/bin/bash
cd Themes/YourTheme/docs

for file in *.md; do
  base=$(basename "$file" .md)
  
  # HTML with TOC
  pandoc -s --toc -N "$file" -o "${base}.html"
  
  # PDF
  pandoc -N --toc "$file" -o "${base}.pdf"
  
  echo "Generated: ${base}.html ${base}.pdf"
done
```

## Theme-Specific Documentation

Common theme docs:

| File | Purpose |
|------|---------|
| `README.md` | Theme overview & setup |
| `COMPONENTS.md` | Component showcase & usage |
| `STYLING.md` | CSS variables & customization |
| `INSTALLATION.md` | Installation instructions |
| `CHANGELOG.md` | Version history |

## With Metadata

Add front matter for theme-specific metadata:

```markdown
---
title: Theme Documentation
theme: YourTheme
author: Marco Sottana
version: 1.0.0
---

# Theme Setup

Content...
```

## Self-Contained HTML

Create single-file HTML with all resources embedded:

```bash
pandoc -s --self-contained docs/README.md -o docs/README_standalone.html
```

Useful for distribution or archiving.

## References

- Installation: ../../docs/wiki/tools/pandoc-installation.md
- Usage guide: ../../docs/wiki/tools/pandoc-usage.md
- Official: https://pandoc.org
