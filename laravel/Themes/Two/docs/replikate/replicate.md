# Homepage Replication & Improvement Plan

## Target: https://lightseagreen-dogfish-560272.hostingersite.com/
## Our Site: http://127.0.0.1:8000/it

---

## Target Site Analysis (Screenshots taken Feb 2026)

### Section Structure (top to bottom)

1. **Sticky Navigation Bar**
   - Left: Brand name "Marco Sottana" + subtitle "Consulenza Sicurezza"
   - Center: Home | Chi Siamo | Servizi | Blog | FAQ | Contatti
   - Right: CTA button "Richiedi Consulenza" (green bg with phone icon)
   - Dark blue background (#1e3a5f approx), sticky on scroll

2. **Hero Section**
   - Full-width background image (doctor/technician working)
   - Dark overlay (~60% opacity)
   - Large bold title (white, ~48-60px)
   - Description paragraph (white/80%)
   - Two CTAs: Primary (green bg) + Secondary (outline white)
   - **Bottom stats bar**: 3 inline stats ("100% Conformità", "Certificato", "Rapido") with labels

3. **Services Cards** (3 columns)
   - White cards with colored top border (teal/emerald)
   - Circle icon at top
   - Title, description
   - "Scopri di più →" link
   - Clean shadow, rounded corners

4. **Why Critical Section**
   - Pre-title in green uppercase ("SICUREZZA E COMPLIANCE")
   - Bold centered title
   - Subtitle paragraph
   - 4 cards in a row: icon (colored bg), title, description

5. **Sectors/Specializations** (2 columns)
   - Two side-by-side cards with colored gradient headers
   - Left: "Odontoiatria" (teal/green gradient)
   - Right: "Medicina Veterinaria" (dark blue/teal gradient)
   - Each has list items with circle icons

6. **What We Check** (split layout)
   - Left: Title, description paragraph, highlighted callout box (orange/amber bg)
   - Right: Stacked list of items with colored icons

7. **Testimonials** (2x2 grid)
   - Avatar, name, company, location
   - Star rating (5 stars yellow)
   - Italic quoted text
   - Date at bottom
   - Quote icon (large, faded) at top-right

8. **Resources/Downloads**
   - Blue gradient background (similar to hero colors)
   - Title, description
   - 2 download cards with icon, title, description, orange CTA button

9. **Newsletter Signup**
   - Gradient background (dark blue → teal → green)
   - Mail icon
   - Title, description
   - Email input + "Iscriviti" button
   - Disclaimer text

10. **Footer**
    - Dark background
    - 4 columns: Brand info + social, Certifications, Services list, Contact info
    - Bottom bar: Copyright + Privacy/Terms links

---

## Current Site Issues

1. **Layout**: Uses sidebar layout (2-column) - should be full-width
2. **Navigation**: Shows "Laravel" text, no proper nav
3. **Hero**: Has glass panel overlay, DaisyUI classes - needs professional redesign
4. **Content**: Generic TechPlanner content, not domain-specific
5. **Block rendering**: Uses `ui::components.blocks.{type}` - blocks must match type names
6. **Missing blocks**: No sectors, checklist, resources/downloads blocks
7. **Styling**: Mix of DaisyUI and Tailwind - needs consistent Tailwind approach

---

## Implementation Plan

### Phase 1: Layout & Navigation
- [x] Remove sidebar from home.blade.php → full-width
- [x] Update navigation with professional sticky header

### Phase 2: Block Components (in Themes/Two/resources/views/components/blocks/)
- [x] hero/main.blade.php → Full-width hero with stats bar
- [x] services/cards.blade.php → 3 cards with colored top border
- [x] why-critical/grid.blade.php → Pre-title + 4 icon cards
- [x] sectors/cards.blade.php → NEW: 2-column specialty cards with lists
- [x] checklist/split.blade.php → NEW: Split layout with icon list
- [x] testimonials/carousel.blade.php → 2x2 grid with dates
- [x] resources/downloads.blade.php → NEW: Blue gradient + download cards
- [x] newsletter/signup.blade.php → Gradient + email form
- [x] cta/banner.blade.php → Gradient CTA section

### Phase 3: Content (home.json)
- [x] Update all content blocks with TechPlanner-specific content

### Phase 4: SEO & Marketing Improvements
- Proper meta tags, structured data
- Schema.org markup
- Open Graph tags
- Newsletter integration ready
- AdSense placeholder areas

---

## Color Palette (from target, adapted for TechPlanner)
- **Primary Dark**: #1e3a5f (navy blue - navigation, footer)
- **Primary**: #2563eb (blue - accents, links)
- **Accent**: #059669 (emerald - CTAs, highlights)
- **Accent Secondary**: #f97316 (orange - important CTAs)
- **Background**: #f8fafc (light gray sections)
- **Text Primary**: #111827 (near black)
- **Text Secondary**: #6b7280 (gray)

## Typography
- Headings: Inter/System font, bold/extrabold
- Body: Inter/System font, regular
- Hero title: 48-60px
- Section titles: 32-40px
- Body: 16-18px

---

## Improvements Over Target (our site should be BETTER)

1. **Performance**: Lazy loading images, optimized SVGs
2. **Animations**: Subtle scroll animations with Intersection Observer
3. **Accessibility**: WCAG 2.1 AA compliance, proper ARIA labels
4. **SEO**: Schema.org JSON-LD, proper heading hierarchy, meta descriptions
5. **Multilingual**: All content translatable via JSON, hreflang tags
6. **AdSense Ready**: Strategic ad placement areas
7. **Inbound Marketing**: Lead magnets, newsletter, resource downloads
8. **Mobile First**: Superior mobile experience
9. **Dark Mode**: Optional dark mode support
10. **Microinteractions**: Hover effects, smooth transitions

---

*Last updated: February 2026*
