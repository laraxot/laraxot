# Homepage Visual Difference Analysis - 2026-04-07

**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**Local**: http://127.0.0.1:8000/it/tests/homepage

---

## 1. Header & Navigation

### 1.1 Hamburger Menu Position
| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Hamburger menu | Left of logo (horizontal) | Below logo (stacked) | Move `.it-header-center-wrapper` navbar to same row as logo |

**Purpose**: Standard Italian PA header pattern - hamburger should be on same horizontal line as logo for quick access.

---

## 2. "Accedi all'area personale" Button

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Color | White background | Blue/primary | Change to `bg-white text-primary` |
| Icon position | Left of text | Right of text | Move icon before text in anchor |
| Text | "Accedi" | "Accedi all'area personale" | Use translation key |

**Purpose**: Prominent login call-to-action, white provides contrast against blue header.

---

## 3. News Section ("Novità")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Title "Notizie" | Larger font size | Smaller | Increase to `title-xxlarge` |
| Date | Larger, more spacing | Smaller, tight | Increase font-size, add margin |
| Arrow icon | Green (#00A19C) | Different color | Change to `--bs-primary` |
| "Estate in città" link | Green text | Different | Apply green color |

**Purpose**: News highlights should draw attention, green indicates interactive elements.

---

## 4. Events Section ("Eventi")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Visibility | Visible | Not visible | Check if events data is loaded, verify CSS `display` |
| Section title | Present | May be hidden | Ensure section renders |

**Purpose**: Shows upcoming municipal events, critical for citizen engagement.

---

## 5. Government Bodies ("Organi di Governo")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Layout | Side-by-side (horizontal) | Stacked (vertical) | Use flexbox row, not column |
| Cards | 2-3 inline | One per row | Change grid from 1 col to multi-col |

**Purpose**: Display elected officials/boards in accessible horizontal layout.

---

## 6. Evidence Topics ("Argomenti in Evidenza")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Layout | Side-by-side (horizontal) | Stacked (vertical) | Change grid columns |
| Spacing | Larger gaps | Tight | Increase `gap-4` or `gap-lg` |
| Card alignment | Even height | Uneven | Use flex stretch |

**Purpose**: Quick access to popular municipal topics.

---

## 7. Feedback Section ("Quanto sono chiare le informazioni?")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Card styling | White card on green background | Different styling | Match Bootstrap Italia `.cmp-rating` pattern |
| Question text | "Quanto sono chiare le informazioni su questa pagina?" | Same but different styling | Check typography |
| Star rating | 5 stars | Present | Keep |
| Green background | Full section bg | May differ | Apply correct green gradient |

**Purpose**: User feedback collection for service improvement.

---

## 8. Useful Links / Thematic Sites ("Siti Tematici")

| Element | Reference | Local | Fix |
|---------|-----------|-------|-----|
| Layout | Grid with cards | Different | Match Bootstrap Italia grid |
| Card colors | Blue/Orange/Dark theme | May differ | Apply theme colors |
| Title on image | Overlay style | Different | Use image overlay pattern |

**Purpose**: Direct access to specialized municipal services.

---

## Translation Keys Required

All text must use translation keys (no hardcoded Italian):

```php
// Required translation keys
'header.login' => 'Accedi all'area personale'
'news.title' => 'Notizie'
'events.title' => 'Eventi'
'government.title' => 'Organi di governo'
'evidence.title' => 'Argomenti in evidenza'
'feedback.question' => 'Quanto sono chiare le informazioni su questa pagina?'
'useful_links.title' => 'Link utili'
'thematic_sites.title' => 'Siti tematici'
```

---

## CSS Classes to Fix

### Header Navigation
```css
.dc-homepage-parity .it-header-center-wrapper {
  flex-direction: row;
  align-items: center;
}
.dc-homepage-parity .navbar {
  display: flex !important;
  flex-direction: row;
}
```

### Login Button
```css
.dc-homepage-parity .btn-login {
  background-color: white !important;
  color: var(--bs-primary) !important;
}
.dc-homepage-parity .btn-login svg {
  margin-right: 0.5rem;
}
```

### News Section
```css
.dc-homepage-parity .news-date {
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
}
.dc-homepage-parity .news-arrow {
  color: #00A19C;
}
```

### Government Bodies
```css
.dc-homepage-parity .government-cards {
  display: flex;
  flex-direction: row;
  gap: 1.5rem;
}
```

### Evidence Topics
```css
.dc-homepage-parity .evidence-cards {
  display: flex;
  flex-direction: row;
  gap: 1.5rem;
}
```

### Events Section
```css
.dc-homepage-parity #calendario {
  display: block !important;
}
```

---

## Mobile Responsiveness

All fixes must work on:
- Mobile: 375px width
- Tablet: 768px width
- Desktop: 1920px width

Test checklist:
- [ ] Hamburger menu accessible on mobile
- [ ] Cards stack properly on mobile
- [ ] Touch targets adequate size (44px minimum)
- [ ] No horizontal scrolling

---

## Priority Order

1. **Critical**: Events visibility, Government bodies layout
2. **High**: Login button styling, Evidence topics layout  
3. **Medium**: News section typography, Feedback section styling
4. **Low**: Thematic sites styling

---

## Files to Modify

- `laravel/Themes/Sixteen/resources/css/homepage-parity-v2.css`
- Check if translations exist in `laravel/Themes/Sixteen/resources/lang/it/`

---

**Status**: Analysis Complete  
**Next**: Implementation of CSS fixes
