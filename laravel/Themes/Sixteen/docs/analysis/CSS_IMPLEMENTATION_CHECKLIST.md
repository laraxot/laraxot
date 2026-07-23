# CSS Implementation Checklist - FAQ Components

## Overview

Systematic checklist for converting FAQ component styling from Bootstrap Italia to pure Tailwind CSS. Track progress by component with verification gates.

---

## PHASE 1: BREADCRUMB COMPONENT

### Blade Template Updates
- [ ] **1.1** Remove Bootstrap grid classes from breadcrumb container
  - Remove: `.container`, `.row`, `.justify-content-center`, `.col-12 col-lg-10`
  - Add: `max-w-3xl mx-auto px-4`
  - File: `resources/views/components/blocks/breadcrumb/default.blade.php`

- [ ] **1.2** Update breadcrumb list styling
  - Remove: `.p-0` (already in Tailwind)
  - Keep: `flex items-center list-none` (already applied)
  - File: `resources/views/components/blocks/breadcrumb/default.blade.php`

- [ ] **1.3** Update breadcrumb item styling
  - Remove: `.breadcrumb-item`, `.breadcrumb-item a`
  - Add: `flex items-center`, `text-blue-600 no-underline hover:underline`
  - File: `resources/views/components/blocks/breadcrumb/default.blade.php`

### CSS Rules Creation
- [ ] **1.4** Create breadcrumb link styles
  - Verify: Link color is #0066cc (Italia Blue)
  - Verify: Hover state adds underline
  - Verify: Active/last item is gray (#757575)
  - File: `resources/css/faq-replication.css` or `style-apply.css`

- [ ] **1.5** Create separator styling
  - Verify: Separator is "/" character
  - Verify: Color is #757575
  - Verify: Spacing is 0.5rem on each side
  - File: Same as above

### Responsive Testing
- [ ] **1.6** Test breadcrumb on mobile (<576px)
  - Verify: Font size is 14px
  - Verify: Spacing reduces appropriately
  - Verify: No text overflow

- [ ] **1.7** Test breadcrumb on desktop (≥992px)
  - Verify: Font size is 14px (unchanged)
  - Verify: Full spacing applied
  - Verify: Proper centering

### Visual Verification
- [ ] **1.8** Compare breadcrumb to reference design
  - Screenshot reference vs. implementation
  - Verify colors match
  - Verify spacing matches
  - Verify typography matches

**Estimated CSS Lines**: 15-20 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 2: HERO SECTION

### Blade Template Updates
- [ ] **2.1** Remove Bootstrap grid classes from hero
  - Remove: `.container`, `.row`, `.justify-content-center`, `.col-12 col-lg-10`
  - Add: `max-w-3xl mx-auto px-4`
  - File: `resources/views/components/blocks/hero/default.blade.php`

- [ ] **2.2** Update hero wrapper styling
  - Remove: `.cmp-hero` (duplicate div)
  - Remove: `.it-hero-wrapper`, `.bg-white`, `.align-items-start`
  - Add: `flex flex-col items-start bg-white`
  - File: `resources/views/components/blocks/hero/default.blade.php`

- [ ] **2.3** Update hero text wrapper
  - Remove: `.it-hero-text-wrapper`, `.pt-0`, `.ps-0`, `.pb-4`, `.pb-lg-60`
  - Add: `w-full pt-0 ps-0 pb-4 lg:pb-12`
  - File: `resources/views/components/blocks/hero/default.blade.php`

- [ ] **2.4** Update hero title (h1) styling
  - Remove: `.text-black` (use Tailwind class)
  - Add: `text-3xl lg:text-4xl font-bold text-black mb-4`
  - File: `resources/views/components/blocks/hero/default.blade.php`

### CSS Rules Creation
- [ ] **2.5** Create hero section background & padding
  - `.cmp-hero`: `w-full bg-white py-8 lg:py-12`
  - Verify: Mobile padding is correct
  - Verify: Desktop padding is 3rem (48px)
  - File: `resources/css/faq-replication.css`

- [ ] **2.6** Create hero title typography
  - Font size: 1.5rem mobile, 2rem desktop
  - Font weight: 700 (bold)
  - Color: #000000 (black)
  - Line height: 1.2
  - File: Same as above

- [ ] **2.7** Create hero subtitle typography
  - Font size: 1rem (16px)
  - Font weight: 400 (normal)
  - Color: #191919 (dark gray)
  - Line height: 1.6
  - File: Same as above

### Responsive Testing
- [ ] **2.8** Test hero on mobile (<576px)
  - Verify: H1 font size is 1.5rem (24px)
  - Verify: H1 line spacing is correct
  - Verify: Padding-bottom is 1.5rem

- [ ] **2.9** Test hero on desktop (≥992px)
  - Verify: H1 font size is 2rem (32px)
  - Verify: Padding-bottom is 3rem (48px)
  - Verify: Text alignment and spacing

### Visual Verification
- [ ] **2.10** Compare hero to reference design
  - Screenshot reference vs. implementation
  - Verify colors match (#000000, #191919)
  - Verify typography matches (Titillium Web, sizes, weights)
  - Verify spacing matches

**Estimated CSS Lines**: 30-40 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 3: SEARCH INPUT COMPONENT

### Blade Template Updates
- [ ] **3.1** Remove Bootstrap grid from search section
  - Remove: `.cmp-input-search` wrapper grid classes
  - Add: `max-w-3xl mx-auto px-4 my-8`
  - File: `resources/views/components/blocks/search/input.blade.php`

- [ ] **3.2** Update form-group wrapper
  - Remove: `.form-group`, `.autocomplete-wrapper`, `.mb-2`, `.mb-lg-4`
  - Add: `mb-2 lg:mb-4`
  - File: `resources/views/components/blocks/search/input.blade.php`

- [ ] **3.3** Update input-group styling
  - Remove: `.input-group` (use Tailwind)
  - Add: `flex items-center relative border border-gray-300 rounded`
  - File: `resources/views/components/blocks/search/input.blade.php`

- [ ] **3.4** Update input element styling
  - Remove: `.form-control`, `.autocomplete`
  - Add: `w-full px-4 py-3 border-0 outline-none text-base`
  - File: `resources/views/components/blocks/search/input.blade.php`

- [ ] **3.5** Update button element styling
  - Remove: `.btn`, `.btn-primary`
  - Add: `px-6 py-3 bg-blue-600 text-white font-semibold hover:bg-blue-700`
  - File: `resources/views/components/blocks/search/input.blade.php`

- [ ] **3.6** Update icon positioning
  - Remove: `.autocomplete-icon` (use absolute positioning)
  - Add: `absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none`
  - File: `resources/views/components/blocks/search/input.blade.php`

### CSS Rules Creation
- [ ] **3.7** Create input field styling
  - Background: #FFFFFF
  - Border: 1px solid #D0D0D0
  - Padding: 0.75rem 1rem (12px 16px)
  - Border radius: 0.25rem (4px)
  - Font: Titillium Web, 1rem, 400
  - Color: #191919
  - File: `resources/css/faq-replication.css`

- [ ] **3.8** Create input focus state
  - Border color: #0066cc (Italia Blue)
  - Box shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25)
  - Outline: none
  - File: Same as above

- [ ] **3.9** Create placeholder styling
  - Color: #999999 (light gray)
  - Opacity: 1
  - File: Same as above

- [ ] **3.10** Create button styling
  - Background: #0066cc
  - Color: #FFFFFF
  - Border: 0
  - Padding: 0.75rem 1.5rem (12px 24px)
  - Border radius: 0.25rem (4px)
  - Font weight: 600 (semi-bold)
  - Cursor: pointer
  - File: Same as above

- [ ] **3.11** Create button hover state
  - Background: #0052a3 (darker blue)
  - Transition: color 150ms, background-color 150ms
  - File: Same as above

- [ ] **3.12** Create button active state
  - Background: #003366 (darkest blue)
  - Box shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125)
  - File: Same as above

### Responsive Testing
- [ ] **3.13** Test input on mobile (<576px)
  - Verify: Input width is 100%
  - Verify: Button width is appropriate
  - Verify: Icon positioning is correct
  - Verify: Padding is reduced if needed

- [ ] **3.14** Test input on desktop (≥992px)
  - Verify: Input width is 100% or max-width applied
  - Verify: Button styling is consistent
  - Verify: Icon is visible and properly positioned

### Accessibility Testing
- [ ] **3.15** Test keyboard navigation
  - Verify: Tab focuses input
  - Verify: Tab focuses button
  - Verify: Space/Enter activates button
  - Verify: Focus indicators are visible

- [ ] **3.16** Test screen reader
  - Verify: Label is associated with input
  - Verify: Label text is read
  - Verify: Button is announced correctly

### Visual Verification
- [ ] **3.17** Compare input to reference design
  - Screenshot reference vs. implementation
  - Verify colors match
  - Verify spacing and sizing match
  - Verify focus states visible

**Estimated CSS Lines**: 50-60 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 4: ACCORDION COMPONENT

### Blade Template Updates
- [ ] **4.1** Review accordion structure
  - Verify: Alpine.js integration is present
  - Verify: x-data, x-show, x-cloak are correct
  - File: `resources/views/components/blocks/accordion/default.blade.php`

- [ ] **4.2** Update accordion container
  - Remove: No Bootstrap grid (already clean)
  - Verify: `.cmp-accordion.faq` wrapper is present
  - File: `resources/views/components/blocks/accordion/default.blade.php`

- [ ] **4.3** Update accordion-item styling
  - Remove: `.accordion-item` Bootstrap class
  - Add: `border-0 mb-2 rounded-lg overflow-hidden bg-white shadow`
  - File: `resources/views/components/blocks/accordion/default.blade.php`

- [ ] **4.4** Update accordion-button styling
  - Remove: `.accordion-button`, `.collapsed`, `.title-snall-semi-bold`, `.py-3`
  - Add: `w-full px-4 py-4 text-left flex items-center justify-between font-semibold text-lg`
  - File: `resources/views/components/blocks/accordion/default.blade.php`

- [ ] **4.5** Verify button wrapper structure
  - Keep: `<div class="button-wrapper">` for text
  - Keep: `<div class="icon-wrapper">` for icon
  - Keep: `<svg class="icon">` for expand icon
  - File: `resources/views/components/blocks/accordion/default.blade.php`

### CSS Rules Creation
- [ ] **4.6** Create accordion-item styling
  - Border: 0
  - Margin bottom: 0.5rem (8px)
  - Border radius: 0.5rem (8px)
  - Background: #FFFFFF
  - Box shadow: 0 1px 3px rgba(0, 0, 0, 0.1)
  - Overflow: hidden
  - File: `resources/css/faq-replication.css`

- [ ] **4.7** Create accordion-item hover state
  - Box shadow: 0 2px 8px rgba(0, 0, 0, 0.15)
  - File: Same as above

- [ ] **4.8** Create accordion-button styling
  - Width: 100%
  - Padding: 1rem (16px) all sides
  - Text align: left
  - Display: flex with space-between
  - Font size: 1.125rem (18px)
  - Font weight: 600 (semi-bold)
  - Color: #FFFFFF (white)
  - Background color: #004445 (dark teal)
  - Border: 0
  - Border radius: 0
  - Cursor: pointer
  - Transition: all 200ms ease
  - File: Same as above

- [ ] **4.9** Create accordion-button hover state
  - Background color: #003334 (darker teal)
  - Color: #FFFFFF (unchanged)
  - File: Same as above

- [ ] **4.10** Create accordion-button focus state
  - Background color: #004445 (unchanged)
  - Outline: none
  - Box shadow: inset 0 0 0 3px #0066cc (blue focus ring)
  - Color: #FFFFFF
  - File: Same as above

- [ ] **4.11** Create button-wrapper styling
  - Flex: 1
  - Display: flex
  - Align items: center
  - Justify content: space-between
  - Width: 100%
  - File: Same as above

- [ ] **4.12** Create icon-wrapper styling
  - Flex shrink: 0
  - Margin left: 0.5rem (8px)
  - Display: flex
  - Align items: center
  - Justify content: center
  - File: Same as above

- [ ] **4.13** Create icon styling
  - Width: 1.25rem (20px)
  - Height: 1.25rem (20px)
  - Color: #FFFFFF (white)
  - Transition: transform 300ms ease
  - File: Same as above

- [ ] **4.14** Create icon rotate-180 state
  - Transform: rotate(180deg)
  - File: Same as above

- [ ] **4.15** Create accordion-collapse styling
  - Overflow: hidden
  - Transition: max-height 300ms ease-in-out
  - File: Same as above

- [ ] **4.16** Create accordion-collapse.collapse state
  - Display: block
  - Max height: 0
  - Overflow: hidden
  - File: Same as above

- [ ] **4.17** Create accordion-collapse.show state
  - Max height: 2000px (allow content)
  - File: Same as above

- [ ] **4.18** Create accordion-body styling
  - Padding: 1rem (16px) all sides
  - Background color: #FFFFFF (white)
  - Border top: 1px solid #E5E7EB (light gray)
  - File: Same as above

- [ ] **4.19** Create accordion-body paragraph styling
  - Font family: Titillium Web
  - Font size: 1rem (16px)
  - Font weight: 400 (normal)
  - Color: #191919 (dark gray)
  - Line height: 1.6 (24px)
  - Margin bottom: 1rem
  - File: Same as above

- [ ] **4.20** Create x-cloak hide styling
  - Display: none !important
  - File: Same as above

### Accessibility Testing
- [ ] **4.21** Test keyboard navigation
  - Verify: Tab focuses each button
  - Verify: Space/Enter toggles accordion
  - Verify: Focus indicators are visible
  - Verify: Arrow keys work (if implemented)

- [ ] **4.22** Test ARIA attributes
  - Verify: `aria-expanded` toggles correctly
  - Verify: `aria-controls` links to content ID
  - Verify: `aria-labelledby` is present
  - Verify: `role="region"` is on collapse div

- [ ] **4.23** Test screen reader
  - Verify: Question text is read
  - Verify: Expanded/collapsed state is announced
  - Verify: Answer content is accessible

### Interactive Testing
- [ ] **4.24** Test expand/collapse animation
  - Verify: Animation is smooth (300ms)
  - Verify: Icon rotates 180°
  - Verify: Content fades/slides in

- [ ] **4.25** Test only-one-open behavior
  - Verify: Only one item open at a time
  - Verify: Opening new item closes previous
  - Verify: Closing behavior works

- [ ] **4.26** Test click-outside-to-close
  - Verify: Clicking outside closes open accordion
  - Verify: Clicking content inside doesn't close

### Visual Verification
- [ ] **4.27** Compare accordion to reference design
  - Screenshot reference vs. implementation
  - Verify colors match (#004445, #003334, #FFFFFF)
  - Verify button sizing and spacing
  - Verify answer area styling
  - Verify animation smoothness

**Estimated CSS Lines**: 80-100 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 5: FEEDBACK/RATING COMPONENT

### Blade Template Updates
- [ ] **5.1** Review feedback component structure
  - Current file: `resources/views/components/blocks/feedback/faq-rating.blade.php`
  - Assess: Component needs significant rewrite
  - File: Same as above

- [ ] **5.2** Update component wrapper
  - Remove: `.bg-primary`, `.container`, `.row`, `.justify-content-center`
  - Add: `max-w-3xl mx-auto px-4 bg-gray-100 py-12 lg:py-16 my-12`
  - File: Same as above

- [ ] **5.3** Update card styling
  - Remove: `.card`, `.shadow`, Bootstrap card classes
  - Add: `bg-white rounded-lg shadow-lg p-8`
  - File: Same as above

- [ ] **5.4** Update title (h2) styling
  - Remove: `.title-medium-2-semi-bold`
  - Add: `text-2xl lg:text-3xl font-bold text-black mb-4`
  - File: Same as above

- [ ] **5.5** Update subtitle (p) styling
  - Remove: Bootstrap spacing classes
  - Add: `text-base text-gray-700 mb-8`
  - File: Same as above

- [ ] **5.6** Update rating stars container
  - Remove: `.faq-rating-stars`
  - Add: `flex justify-center gap-4 flex-wrap`
  - File: Same as above

- [ ] **5.7** Update rating buttons
  - Remove: `.faq-rating-star`
  - Add: `px-6 py-3 bg-white border-2 border-gray-300 rounded-lg cursor-pointer transition-all hover:border-blue-600 active:bg-blue-600`
  - File: Same as above

### CSS Rules Creation
- [ ] **5.8** Create feedback component background
  - Background: #F2F2F2 (light gray)
  - Padding: 3rem 0 (48px) top/bottom
  - Margin: 3rem 0 (48px) top/bottom
  - File: `resources/css/faq-replication.css`

- [ ] **5.9** Create feedback responsive padding
  - Mobile (<576px): padding 2rem 0, margin 2rem 0
  - File: Same as above

- [ ] **5.10** Create feedback wrapper
  - Text align: center
  - File: Same as above

- [ ] **5.11** Create feedback h2 styling
  - Font family: Titillium Web
  - Font size: 1.5rem (24px)
  - Font weight: 700 (bold)
  - Color: #000000 (black)
  - Margin bottom: 1rem
  - File: Same as above

- [ ] **5.12** Create feedback h2 responsive sizing
  - Mobile: 1.25rem (20px)
  - File: Same as above

- [ ] **5.13** Create feedback p styling
  - Font family: Titillium Web
  - Font size: 1rem (16px)
  - Font weight: 400 (normal)
  - Color: #191919 (dark gray)
  - Margin bottom: 2rem
  - Line height: 1.6
  - File: Same as above

- [ ] **5.14** Create rating-options styling
  - Display: flex
  - Justify content: center
  - Gap: 1rem (16px)
  - Flex wrap: wrap
  - File: Same as above

- [ ] **5.15** Create rating-btn styling
  - Padding: 0.75rem 1.5rem (12px 24px)
  - Font family: Titillium Web
  - Font size: 2rem (for emoji)
  - Background: #FFFFFF
  - Border: 2px solid #D0D0D0
  - Border radius: 0.5rem (8px)
  - Cursor: pointer
  - Transition: all 200ms ease
  - File: Same as above

- [ ] **5.16** Create rating-btn hover state
  - Border color: #0066cc (Italia Blue)
  - Box shadow: 0 2px 8px rgba(0, 102, 204, 0.2)
  - File: Same as above

- [ ] **5.17** Create rating-btn active state
  - Background: #0066cc (Italia Blue)
  - Border color: #0066cc
  - Color: #FFFFFF (white)
  - File: Same as above

### Responsive Testing
- [ ] **5.18** Test feedback on mobile (<576px)
  - Verify: Padding reduces to 2rem
  - Verify: Title font size is 1.25rem
  - Verify: Stars are visible and clickable
  - Verify: No horizontal overflow

- [ ] **5.19** Test feedback on desktop (≥992px)
  - Verify: Padding is 3rem
  - Verify: Title font size is 1.5rem
  - Verify: Stars spacing is correct
  - Verify: Centering is maintained

### Visual Verification
- [ ] **5.20** Compare feedback to reference design
  - Screenshot reference vs. implementation
  - Verify colors match
  - Verify spacing and sizing match
  - Verify button styling and states

**Estimated CSS Lines**: 40-50 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 6: CONTACTS COMPONENT

### Blade Template Updates
- [ ] **6.1** Review contacts component
  - Current file: `resources/views/components/blocks/contacts/faq.blade.php`
  - Current status: Uses Tailwind but mixing dark footer approach
  - File: Same as above

- [ ] **6.2** Update component wrapper
  - Remove: Dark footer styling (`.it-footer-main`, `bg-[#17324D]`)
  - Add: `bg-white border-t border-gray-200`
  - File: Same as above

- [ ] **6.3** Update container/grid
  - Remove: `.container`, `.row`, `.col-12 col-md-6 col-lg-4`
  - Add: `max-w-3xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6`
  - File: Same as above

- [ ] **6.4** Update section titles (h2 and h3)
  - Remove: Uppercase styling, current font sizes
  - Add: `text-2xl lg:text-3xl font-bold text-black mb-6` for h2
  - Add: `text-lg font-semibold text-black mb-3` for h3
  - File: Same as above

- [ ] **6.5** Update contact cards
  - Remove: Dark background and white text
  - Add: `flex flex-col items-start p-6 bg-gray-50 rounded-lg border border-gray-200 transition-all hover:shadow-lg hover:border-gray-300`
  - File: Same as above

- [ ] **6.6** Update contact icons
  - Remove: `bg-white/10` opacity styling
  - Add: `w-8 h-8 text-blue-600 mb-4`
  - File: Same as above

- [ ] **6.7** Update contact links
  - Remove: White text, current styling
  - Add: `text-blue-600 no-underline font-semibold hover:underline`
  - File: Same as above

### CSS Rules Creation
- [ ] **6.8** Create contacts component container
  - Background: #FFFFFF
  - Padding: 2rem 0 (32px) top/bottom
  - Border top: 1px solid #E5E7EB (light gray)
  - File: `resources/css/faq-replication.css`

- [ ] **6.9** Create contacts responsive padding
  - Mobile (<576px): padding 1.5rem 0
  - File: Same as above

- [ ] **6.10** Create contacts-grid styling
  - Display: grid
  - Grid template columns: repeat(auto-fit, minmax(250px, 1fr))
  - Gap: 2rem (32px)
  - Margin bottom: 2rem
  - File: Same as above

- [ ] **6.11** Create contacts-grid responsive
  - Mobile/tablet (<768px): grid-template-columns: 1fr, gap 1.5rem
  - File: Same as above

- [ ] **6.12** Create contact-item styling
  - Display: flex
  - Flex direction: column
  - Align items: flex-start
  - Padding: 1.5rem (24px)
  - Background: #F9F9F9 (very light gray)
  - Border radius: 0.5rem (8px)
  - Border: 1px solid #E5E7EB
  - Transition: all 200ms ease
  - File: Same as above

- [ ] **6.13** Create contact-item hover state
  - Box shadow: 0 2px 8px rgba(0, 0, 0, 0.1)
  - Border color: #D0D0D0 (darker gray)
  - File: Same as above

- [ ] **6.14** Create contact h2 styling
  - Font family: Titillium Web
  - Font size: 1.5rem (24px)
  - Font weight: 700 (bold)
  - Color: #000000 (black)
  - Margin bottom: 1.5rem
  - Margin top: 2rem (not for first)
  - File: Same as above

- [ ] **6.15** Create contact h3 styling
  - Font family: Titillium Web
  - Font size: 1.125rem (18px)
  - Font weight: 600 (semi-bold)
  - Color: #000000 (black)
  - Margin bottom: 0.75rem
  - Line height: 1.4
  - File: Same as above

- [ ] **6.16** Create contact icon styling
  - Width: 2rem (32px)
  - Height: 2rem (32px)
  - Color: #0066cc (Italia Blue)
  - Margin bottom: 1rem
  - File: Same as above

- [ ] **6.17** Create contact link styling
  - Color: #0066cc (Italia Blue)
  - Text decoration: none
  - Font weight: 600 (semi-bold)
  - Transition: all 150ms ease
  - Align self: flex-start
  - File: Same as above

- [ ] **6.18** Create contact link hover state
  - Text decoration: underline
  - Color: #0052a3 (darker blue)
  - File: Same as above

### Responsive Testing
- [ ] **6.19** Test contacts on mobile (<576px)
  - Verify: Grid is single column
  - Verify: Cards are full width
  - Verify: Spacing is reduced
  - Verify: Icons and text are readable

- [ ] **6.20** Test contacts on tablet (576-768px)
  - Verify: Grid is 1 or 2 columns as appropriate
  - Verify: Cards maintain aspect
  - Verify: Spacing is consistent

- [ ] **6.21** Test contacts on desktop (≥992px)
  - Verify: Grid is 3 columns
  - Verify: Cards are equal width
  - Verify: Full spacing applied

### Visual Verification
- [ ] **6.22** Compare contacts to reference design
  - Screenshot reference vs. implementation
  - Verify colors match
  - Verify grid layout matches
  - Verify card styling and spacing match

**Estimated CSS Lines**: 60-80 lines  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## PHASE 7: COMPREHENSIVE INTEGRATION

### All Components Together
- [ ] **7.1** Test page layout without Bootstrap
  - Verify: No layout shifts or broken sections
  - Verify: All components render correctly
  - Verify: Spacing between sections is consistent

- [ ] **7.2** Test responsive design
  - Mobile (375px): All components stack and are readable
  - Tablet (768px): Grid components show 2 columns
  - Desktop (1024px): Full layout with 3 columns
  - Large (1280px): Max-width is enforced

- [ ] **7.3** Test color consistency
  - Verify: All blues are #0066cc (Italia Blue)
  - Verify: All grays are from the design palette
  - Verify: All backgrounds match reference

- [ ] **7.4** Test typography consistency
  - Verify: All text uses Titillium Web
  - Verify: Font sizes are consistent
  - Verify: Font weights are correct
  - Verify: Line heights are proper

### Bootstrap Classes Removal
- [ ] **7.5** Audit and remove Bootstrap grid classes
  - Search for: `.container`, `.row`, `.col-`, `.justify-content-*`
  - Status: All removed from templates
  - File(s): All component templates

- [ ] **7.6** Audit and remove Bootstrap spacing classes
  - Search for: `.p-*`, `.m-*`, `.pt-*`, `.pb-*`, `.ps-*`, `.pe-*`
  - Status: Replaced with Tailwind equivalents
  - File(s): All component templates

- [ ] **7.7** Audit and remove Bootstrap component classes
  - Search for: `.btn-*`, `.card`, `.badge`, `.form-*`, `.input-group*`
  - Status: Replaced with Tailwind styles
  - File(s): All component templates

- [ ] **7.8** Audit and remove Bootstrap utility classes
  - Search for: `.align-*`, `.flex-*`, `.text-*`, `.bg-*`, `.border-*`
  - Status: Replaced where applicable
  - File(s): All component templates and CSS

### CSS File Organization
- [ ] **7.9** Create faq-replication.css (if needed)
  - Contains: All FAQ-specific Tailwind override styles
  - Status: Properly organized with component sections
  - File: `resources/css/faq-replication.css`

- [ ] **7.10** Verify CSS file imports
  - Ensure: faq-replication.css is imported in main CSS
  - Ensure: No Bootstrap Italia CSS is imported
  - File: `resources/css/app.css`

- [ ] **7.11** Verify no unused CSS
  - Remove: Deprecated Bootstrap classes
  - Remove: Old FAQ-parity styles if replaced
  - File(s): All CSS files

### Documentation
- [ ] **7.12** Update component comments
  - Add: Tailwind classes used
  - Remove: Old Bootstrap references
  - File(s): All component templates

- [ ] **7.13** Create implementation summary
  - Document: Classes removed
  - Document: Classes added
  - Document: CSS lines changed
  - File: `docs/analysis/IMPLEMENTATION_SUMMARY.md`

### Testing & Quality Assurance
- [ ] **7.14** Run Tailwind build
  - Command: `npm run build` (or theme-specific build)
  - Status: Build succeeds without warnings
  - Verify: All CSS is generated

- [ ] **7.15** Test page in browser
  - Load: FAQ page (tests.domande-frequenti)
  - Verify: All styling is applied
  - Verify: No console errors
  - Verify: No CSS warnings

- [ ] **7.16** Test all interactive states
  - Accordion expand/collapse
  - Input focus states
  - Button hover states
  - Rating button selection

- [ ] **7.17** Cross-browser testing
  - Chrome: Full styling applied
  - Firefox: Full styling applied
  - Safari: Full styling applied
  - Edge: Full styling applied

- [ ] **7.18** Accessibility audit
  - WAVE tool: No errors
  - Focus indicators: Visible and correct
  - Color contrast: WCAG AA standard
  - Screen reader: All elements announced

### Final Verification
- [ ] **7.19** Compare final page to reference design
  - Screenshot: Final implementation
  - Screenshot: Reference design
  - Verify: Visual match is accurate
  - Verify: All details match

- [ ] **7.20** Performance verification
  - Lighthouse: No CSS-related warnings
  - Load time: No significant change
  - File size: CSS is optimized

**Estimated Tasks**: 20  
**Estimated CSS Lines**: 300-400 total  
**Status**: Not Started  
**Assigned To**: (TBD)

---

## IMPLEMENTATION NOTES

### Important Files
- **Component Templates**: 6 main components in `resources/views/components/blocks/`
- **CSS Files**: Primary in `resources/css/`
- **Config**: `tailwind.config.js` for theme customization

### Bootstrap Classes to Search
Use these patterns in global search to find all remaining Bootstrap:
- `\.container`, `\.row`, `\.col-`
- `\.btn`, `\.form-`, `\.input-group`
- `\.card`, `\.shadow`
- `\.align-`, `\.justify-`

### Tailwind Patterns to Replace With
- `\.container` → `max-w-3xl mx-auto px-4`
- `\.row` → `flex` or `grid`
- `\.col-` → `w-full lg:w-5/6`
- `\.btn` → `px-6 py-3 font-semibold cursor-pointer`
- `\.form-control` → `px-4 py-3 border border-gray-300 rounded`

### Testing Commands
```bash
# Build Tailwind CSS
npm run build

# Build theme specifically
cd laravel/Themes/Sixteen && npm run build

# Copy assets
npm run copy

# Serve and test
php artisan serve
```

---

## SUMMARY TABLE

| Phase | Component | Est. Hours | Est. CSS Lines | Status |
|-------|-----------|-----------|----------------|--------|
| 1 | Breadcrumb | 1-2 | 15-20 | ⬜ Not Started |
| 2 | Hero | 2-3 | 30-40 | ⬜ Not Started |
| 3 | Search Input | 2-3 | 50-60 | ⬜ Not Started |
| 4 | Accordion | 3-4 | 80-100 | ⬜ Not Started |
| 5 | Feedback | 2-3 | 40-50 | ⬜ Not Started |
| 6 | Contacts | 2-3 | 60-80 | ⬜ Not Started |
| 7 | Integration | 3-4 | - | ⬜ Not Started |
| **TOTAL** | **All** | **15-22** | **275-350** | ⬜ Not Started |

---

## SUCCESS CRITERIA

✅ **All criteria must be met for completion**:

1. ✅ No Bootstrap Italia classes in component templates
2. ✅ All styling achieved with Tailwind CSS
3. ✅ Visual appearance matches reference design
4. ✅ Responsive design works at all breakpoints
5. ✅ No console errors or CSS warnings
6. ✅ All interactive states functioning
7. ✅ Accessibility standards met (WCAG AA)
8. ✅ Documentation complete and accurate

---

**Checklist Version**: 1.0  
**Framework**: Laravel 12 + Tailwind CSS 3  
**Reference**: Design Comuni Italia FAQ  
**Date Created**: Implementation Phase 1  
**Last Updated**: Ready for execution
