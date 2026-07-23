# Segnalazione Dettaglio - HTML Structure Comparison Reports

## Overview

This directory contains HTML structure comparison reports for the "Segnalazione Dettaglio" page. These reports analyze the parity between:
- **Reference Implementation**: Official Design Comuni Italia page structure
- **Local Implementation**: FixCity theme implementation

---

## Report Files

### comparison-report.json
**Machine-readable detailed comparison data**

Structure:
```json
{
  "reference": {
    "url": "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html",
    "elements": [...],
    "stats": { ... }
  },
  "local": {
    "elements": [...],
    "stats": { ... }
  },
  "comparison": {
    "parity_score": 0.95,
    "matching_elements": 287,
    "total_elements": 301,
    "differences": [...]
  }
}
```

**Use Cases**:
- Automated validation in CI/CD pipelines
- Programmatic analysis tools
- Integration with comparison dashboards

---

### comparison-report.md
**Human-readable comparison summary**

Sections:
1. **Executive Summary** - Overall parity score and key findings
2. **Section Breakdown** - Analysis by major page sections
3. **Element Matching** - Detailed element-by-element results
4. **CSS Classes** - Class name and usage analysis
5. **IDs & Identifiers** - ID consistency check
6. **Accessibility** - ARIA attributes and hidden elements
7. **Deviations** - Detailed list of differences and rationale

**Use Cases**:
- Manual review and validation
- Identifying specific issues
- Documentation of known deviations
- Stakeholder reporting

---

### details/ Directory
**Per-element detailed difference reports**

Structure:
```
details/
├── header/
│   ├── navigation.md
│   └── navigation.json
├── main-content/
│   ├── accordion.md
│   └── accordion.json
├── footer/
│   ├── lists.md
│   └── lists.json
└── [more sections]
```

Each file contains:
- Element structure comparison
- CSS class differences
- Attribute analysis
- Visual impact assessment

---

## Parity Score Interpretation

### Score Scale
- **95-100%**: Excellent - Structural parity confirmed
- **85-94%**: Good - Minor deviations, functionally equivalent
- **70-84%**: Fair - Some structural differences, review needed
- **Below 70%**: Poor - Significant deviations, investigation required

### What Affects Score
- **Matching Elements**: +1 point each
- **Missing Elements**: -5 points each
- **Extra Elements**: -2 points each
- **Class Differences**: -0.5 points per class mismatch
- **Attribute Differences**: -0.25 points per attribute mismatch
- **ID Inconsistencies**: -1 point per inconsistency

### What's Ignored (Acceptable Deviations)
- CSS class ordering
- Whitespace and formatting
- Dynamic ID generation (e.g., Alpine.js, Livewire IDs)
- Asset URLs and CDN paths
- Script src attributes
- Meta tag variations

---

## How Reports Are Generated

### Generation Script
```bash
./bashscripts/compare-html-structure.sh segnalazione-dettaglio
```

### Process
1. **Fetch Reference**
   - Download latest from: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html
   - Parse DOM structure
   - Extract elements, classes, IDs

2. **Parse Local Implementation**
   - Render local page (or use static capture)
   - Parse DOM structure
   - Extract elements, classes, IDs

3. **Analyze Differences**
   - Compare element hierarchies
   - Identify missing/extra elements
   - Analyze class and ID differences
   - Calculate parity score

4. **Generate Reports**
   - Create JSON for machine processing
   - Create markdown for human review
   - Generate per-section detailed reports

---

## Running Comparisons

### One-Time Comparison
```bash
cd /var/www/_bases/base_fixcity_fila5
./bashscripts/compare-html-structure.sh segnalazione-dettaglio
```

### Scheduled Comparisons
Add to CI/CD pipeline:
```yaml
- name: Compare HTML Structure
  run: |
    ./bashscripts/compare-html-structure.sh segnalazione-dettaglio
    
- name: Check Parity Score
  run: |
    score=$(jq .comparison.parity_score comparison-report.json)
    if (( $(echo "$score < 0.85" | bc -l) )); then
      echo "Parity score below 85%: $score"
      exit 1
    fi
```

### Comparing Multiple Pages
```bash
for page in segnalazione-dettaglio ticket-list segnalazione-01-privacy; do
  ./bashscripts/compare-html-structure.sh "$page"
done
```

---

## Review & Action Items

### Interpreting Results

#### Green Flag (95%+)
✅ Structure matches reference implementation  
✅ No action needed, implementation is accurate  
✅ Safe to deploy  

#### Yellow Flag (85-94%)
⚠️ Minor deviations detected  
⚠️ Review deviation details  
⚠️ Verify deviations are acceptable (see ANALYSIS.md)  
⚠️ Document if intentional  

#### Red Flag (<85%)
🔴 Significant structural differences  
🔴 **MUST investigate and address**  
🔴 Check details/ directory for specific issues  
🔴 Update local implementation to match  
🔴 Re-run comparison after fixes  

### Common Deviation Scenarios

**Scenario 1: Dynamic IDs**
```
Reference: id="accordion-faq-12345"
Local: id="accordion-faq-abc123" (Livewire-generated)
Status: ✅ Acceptable - functional equivalence
```

**Scenario 2: Asset URLs**
```
Reference: href="/design-comuni/assets/bootstrap.css"
Local: href="/themes/Sixteen/assets/app-hash.css"
Status: ✅ Acceptable - assets equivalent, delivered differently
```

**Scenario 3: Class Additions**
```
Reference: class="accordion-button collapsed"
Local: class="accordion-button collapsed livewire-mounted"
Status: ⚠️ Review - Livewire classes may be added, verify no conflicts
```

**Scenario 4: Missing Elements**
```
Reference: 19 accordion items present
Local: 15 accordion items present
Status: 🔴 Issue - Functionality gap, needs investigation
```

---

## Debugging Failed Comparisons

### Common Issues

**1. Report File Not Found**
```
Error: comparison-report.json not found
Solution: Run the generation script first
```

**2. Parity Score Unexpectedly Low**
```
Steps:
1. Check details/ directory for specific mismatches
2. Review ANALYSIS.md for expected deviations
3. Compare reference.html with local page source
4. Run script with --verbose flag
```

**3. Script Errors**
```
Check:
- Reference HTML file exists: ../prompts/segnalazione-dettaglio/reference.html
- Local page is accessible/rendering correctly
- All dependencies installed (jq for JSON parsing)
```

---

## Integration with ANALYSIS.md

### Reference Documentation
See [HTML Structure Analysis](../prompts/segnalazione-dettaglio/ANALYSIS.md) for:
- Complete element inventory (1,519 lines)
- Component breakdown (accordion, navigation, footer)
- CSS class patterns and usage
- Design Comuni conventions
- Expected mapping to local implementation
- Documented acceptable deviations

### Workflow
1. **Review ANALYSIS.md** - Understand reference structure
2. **Run Comparison** - Generate parity reports
3. **Check Score** - Assess alignment (target: 95%+)
4. **Review Details** - For deviations, check details/ directory
5. **Update Implementation** - If structural gaps found
6. **Re-run Comparison** - Verify fixes

---

## File Structure

```
laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/
├── README.md                          # This file
├── comparison-report.json             # Machine-readable results
├── comparison-report.md               # Human-readable summary
└── details/
    ├── header/
    │   ├── navigation.md
    │   └── navigation.json
    ├── main-content/
    │   ├── accordion.md
    │   └── accordion.json
    ├── footer/
    │   ├── lists.md
    │   └── lists.json
    └── [other sections]
```

---

## Tools & Scripts

### View Parity Score Quickly
```bash
jq .comparison.parity_score laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/comparison-report.json
```

### List Detected Differences
```bash
jq '.comparison.differences | .[]' laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/comparison-report.json
```

### Export to CSV for Analysis
```bash
jq -r '.comparison.differences[] | [.element, .type, .severity] | @csv' \
  laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/comparison-report.json > differences.csv
```

---

## Related Documentation

### Theme Documentation
- [Sixteen Theme Index](../../00-INDEX.md) - Complete theme documentation
- [Component Catalog](../../COMPONENT_CATALOG.md) - All available components
- [Visual Comparison Archive](../../visual-comparison/) - Historical comparisons

### Project Standards
- [Code Quality Standards](../../../../docs/CODE_QUALITY_STANDARDS.md)
- [Theme Architecture](../../../../docs/THEMES_DOCUMENTATION_INDEX.md)

### Design Reference
- [Design Comuni Official](https://italia.github.io/design-comuni-pagine-statiche/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)

---

## Maintenance

### Regular Reviews
- Run comparison **weekly** during active development
- After theme updates
- Before releases
- After dependency upgrades

### Updating Reference HTML
```bash
# Download latest reference
curl -o laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/reference.html \
  "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html"

# Re-run comparison
./bashscripts/compare-html-structure.sh segnalazione-dettaglio

# Review changes
git diff laravel/Themes/Sixteen/docs/body-structure-comparison/segnalazione-dettaglio/
```

---

**Last Updated**: See git history  
**Status**: Ready for analysis  
**Parity Target**: 95%+
