# Visual Parity Fix Plan - Segnalazione Pages

**Generated**: 2026-04-06
**Goal**: Achieve ≥90% visual parity with design.comuni.it reference pages

---

## Executive Summary

After analyzing all 8 pages, the main issues are:
1. **Wrong CSS Framework** - Local uses Tailwind, Reference uses Bootstrap Italia
2. **Missing Components** - Rating widget, specific accordions, service cards
3. **Incorrect Class Names** - Using Tailwind classes instead of Bootstrap Italia equivalents
4. **Missing data-element attributes** - Used for analytics tracking

---

## Page-by-Page Analysis

### 1. segnalazione-disservizio (Service Landing)
- **Similarity**: 9% (very low)
- **Issues**:
  - Simple form instead of full service page with sections
  - Missing service status chip
  - Missing navigation sidebar (navscroll)
  - Missing "Come fare", "Cosa serve", "Cosa si ottiene" sections
  - Missing related contents carousel
  - Missing rating widget
- **Fix**: Create full service detail component

### 2. segnalazione-01-privacy
- **Similarity**: Already analyzed
- **Status**: Step 1 of flow - needs validation styling

### 3. segnalazione-02-dati  
- **Similarity**: Already analyzed
- **Status**: Step 2 of flow - needs form field styling

### 4. segnalazione-03-riepilogo
- **Similarity**: Already analyzed
- **Status**: Step 3 of flow - needs summary card styling

### 5. segnalazione-04-conferma ✅ COMPONENT EXISTS
- **Similarity**: 11%
- **Issues**:
  - Uses Tailwind classes instead of Bootstrap Italia
  - Missing green check icon (icon-success)
  - Missing PDF download button
  - Missing link to area riservata
  - Missing "Servizi correlati" section
  - Missing rating widget (most important)
  - Missing contacts section
- **Fix**: Update 04-conferma.blade.php to match reference

### 6. segnalazione-area-personale (Dashboard)
- **Similarity**: 62% (highest!)
- **Issues**:
  - Missing accordion styling for "Pratiche" section
  - Missing card folder icons (folder-waiting, folder-concluded)
  - Missing filter dropdown styling
  - Missing search autocomplete styling
  - Missing "Servizi" tab content
  - Missing "Attività" tab content
- **Fix**: Enhance dashboard component with Bootstrap Italia accordion

### 7. segnalazioni-elenco (List Page)
- **Similarity**: Low
- **Issues**:
  - Missing category filter sidebar
  - Missing Map/List tab switcher
  - Missing map with pin
  - Missing info-button-card styling
  - Missing "Fai una segnalazione" CTA section
  - Missing pagination
- **Fix**: Create list page component with filters

### 8. segnalazione-dettaglio
- **Similarity**: Low
- **Issues**:
  - Missing service detail structure
  - Missing navscroll sidebar
  - Missing sections (A chi è rivolto, Descrizione, Come fare, etc.)
- **Fix**: This appears to be same as segnalazione-disservizio

---

## Priority Fixes

### HIGH PRIORITY (User-Facing)

1. **Rating Widget** (used on multiple pages)
   - Star rating component with 5 stars
   - Feedback form with positive/negative questions
   - Text input for additional feedback
   
2. **04-conferma Confirmation Page**
   - Success icon (green check)
   - Confirmation number display
   - PDF download button
   - Link to area riservata

3. **Accordion Component** (for area personale)
   - Card folder status icons
   - Expand/collapse behavior
   - Service status badges

### MEDIUM PRIORITY

4. **Navigation Sidebar (navscroll)**
   - Sticky sidebar with page index
   - Progress bar
   - Smooth scroll links

5. **Service Card Components**
   - Info button cards
   - Icon list components
   - Related services

---

## CSS Strategy

Instead of trying to replicate Bootstrap Italia classes with Tailwind, we should:

1. **Keep Bootstrap Italia assets** already in the theme
2. **Use Bootstrap Italia classes directly** in components where possible
3. **Only use Tailwind for custom layouts** that Bootstrap doesn't handle

The theme already imports: `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/css/bootstrap-italia.min.css`

---

## Files to Modify

1. `resources/views/components/blocks/flow/segnalazione/04-conferma.blade.php`
2. `resources/views/components/blocks/flow/area-personale/dashboard.blade.php`
3. Create: `resources/views/components/blocks/rating-widget.blade.php`
4. Create: `resources/views/components/blocks/service/detail.blade.php`
5. Create: `resources/views/components/blocks/segnalazioni/list.blade.php`

---

## Next Steps

1. First fix: Update 04-conferma.blade.php to match reference design
2. Add rating widget to all confirmation pages
3. Fix accordion styling in area personale dashboard
4. Verify with visual comparison screenshots