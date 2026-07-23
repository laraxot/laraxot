# FAQ Page Documentation

Documentation for making the local FAQ page visually identical to the reference.

## Index

### Main Comparison
- **[FAQ Comparison](faq-comparison-2026-04-03.md)** - Initial HTML structure comparison

### Screenshots & Visual Analysis
- **[Screenshots Output](screenshots/faq/)** - All screenshot files
- **[Visual Analysis v2](screenshots/faq/VISUAL-ANALYSIS-v2.md)** - Complete visual comparison (final)
- **[Visual Analysis v1](screenshots/faq/VISUAL-ANALYSIS.md)** - Initial visual differences

## Script Documentation

- **[FAQ Screenshot Script](bashscripts/docs/faq-screenshots.md)** - Playwright script for capturing comparison screenshots

## Files Modified

### JSON Configuration
- `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json` - Page content (merged accordions, search box, link list)

### CSS Files
- `laravel/Themes/Sixteen/resources/css/style-apply.css` - FAQ search and accordion styles
- `laravel/Themes/Sixteen/resources/css/app.css` - Theme imports

### Blade Components
- `laravel/Themes/Sixteen/resources/views/components/blocks/accordion/default.blade.php` - FAQ accordion with Alpine.js
- `laravel/Themes/Sixteen/resources/views/components/blocks/search/input.blade.php` - Search with header

## Quick Links

- **Local Page:** http://127.0.0.1:8000/it/tests/domande-frequenti
- **Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html

## Related Documentation

- **Main Docs:** [bashscripts/docs/faq-screenshots.md](bashscripts/docs/faq-screenshots.md)
- **Theme Index:** [DOCUMENTATION_INDEX.md](./DOCUMENTATION_INDEX.md)
