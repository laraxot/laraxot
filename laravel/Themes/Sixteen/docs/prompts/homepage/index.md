# Homepage Prompts - Similarity Report

## Current Status

| Metric | Value |
|--------|-------|
| **HTML Similarity** | 99.6% - 99.7% |
| **Status** | PASS |
| **Issue Type** | CSS differences only |

## Summary

The local homepage HTML is structurally identical (99.6%-99.7%) to the Design Comuni reference. The minimal differences are:

1. **Whitespace/indentation** - No visual impact
2. **CSS classes** - Tailwind vs Bootstrap Italia (different class names, same visual output)
3. **Asset paths** - Local paths vs relative paths

The task is to make pages **visually identical** via CSS fixes, NOT HTML changes.

## Files

### Source HTML
- `../design-comuni/html-homepage/reference-homepage.html` - Design Comuni reference
- `../design-comuni/html-homepage/local-homepage.html` - Local generated HTML

### Scripts
- `../../../bashscripts/visual-comparison/compare-structure.sh` - Single page comparison
- `../../../bashscripts/visual-comparison/compare-all-structure.sh` - All pages comparison

## Block Analysis

Each block is documented separately in `blocks/` directory:

- [01-header-slim.md](blocks/01-header-slim.md) - Header slim wrapper
- [02-header-main.md](blocks/02-header-main.md) - Main header with navigation
- [03-hero-section.md](blocks/03-hero-section.md) - Hero with news card and search
- [04-calendario.md](blocks/04-calendario.md) - Events calendar section
- [05-argomenti.md](blocks/05-argomenti.md) - Featured topics section
- [06-altri-argomenti.md](blocks/06-altri-argomenti.md) - Other topics chips
- [07-siti-tematici.md](blocks/07-siti-tematici.md) - Thematic sites
- [08-link-utili.md](blocks/08-link-utili.md) - Useful links section
- [09-rating.md](blocks/09-rating.md) - Page rating component
- [10-footer.md](blocks/10-footer.md) - Footer section

## Cross-References

- [Visual Comparison Results](../../visual-comparison/README.md)
- [BASHSCRIPTS Index](../../../bashscripts/docs/BASHSCRIPTS-INDEX.md)

## Multi-Agent Notes

This task can be parallelized by assigning different blocks to different agents:
- Agent A: Header blocks (01, 02)
- Agent B: Hero and main content (03, 04)
- Agent C: Argomenti and siti tematici (05, 06, 07)
- Agent D: Footer sections (08, 09, 10)

All agents should coordinate via shared documentation to avoid duplicate work.


./bashscripts deve essere agnostico, percio' crea/migliora uno script dentro una sottocartella di bashscripts per confrontare l'html dentro il tag body incluso esclusi gli scripts , poi lo documenti dentro la bashscripts/docs utilizzando gli indici , dopo lo utilizziamo per portare avanti http://127.0.0.1:8001/it/tests/homepage, invididua i blocchi che ci sono in https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html , ogni singolo blocco lo devi documentare dentro /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/docs/prompts/homepage/blocks , 
