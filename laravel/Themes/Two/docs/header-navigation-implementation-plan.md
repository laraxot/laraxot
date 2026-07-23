# Header Navigation Implementation Plan

**Date**: 2026-02-07
**Status**: In Progress
**Module**: Theme Two
**Task**: Implement header navigation v1 with multi-language and login integration

## 📋 Analysis Summary

### Target Site Structure
Based on `Main_files/target-header-structure.html`, the header navigation includes:

1. **Brand Section**:
   - Logo: "Marco Sottana"
   - Subtitle: "Consulenza Sicurezza"
   - Link to homepage

2. **Navigation Items** (Desktop):
   - Home
   - Chi Siamo
   - Servizi
   - Blog
   - FAQ
   - Contatti
   - Each with hover underline effect

3. **CTA Button**:
   - Label: "Richiedi Consulenza"
   - Icon: Phone/SVG
   - Link: `/contatti`
   - Style: White button with blue text

4. **Mobile Menu**:
   - Hamburger menu button
   - Hidden on desktop, visible on mobile

### Current Implementation
- File: `laravel/Themes/Two/resources/views/components/sections/header/v1.blade.php`
- Current state: Empty (only includes v1 recursively)
- Need to implement full navigation

### Content Source
- File: `laravel/config/local/techplanner/database/content/sections/header.json`
- Contains navigation data for both Italian and English
- Structure: `blocks.{locale}[0].data.{items, brand, cta_label, cta_url}`

## 🎯 Requirements

### Multi-Language Support
- Study Lang module for translation patterns
- Use `@lang()` directive for translations
- Support both Italian (it) and English (en)
- Language selector integration

### Login Integration
- Guest users: Show "Login" and "Registrati" buttons
- Authenticated users: Show avatar with dropdown
- Dropdown options:
  - Profile
  - Settings
  - Logout
- Use Laravel's `@auth` / `@guest` directives

### Responsive Design
- Desktop: Full navigation + CTA button
- Mobile: Hamburger menu
- Smooth transitions

### Styling
- Follow Tailwind CSS conventions
- Match target site design
- Proper hover effects

## 🏗️ Implementation Plan

### Phase 1: Study & Documentation (Current)
- [x] Read header.json structure
- [x] Study Lang module documentation
- [x] Analyze target site HTML
- [x] Review existing header components
- [x] Document implementation plan

### Phase 2: Create Translation Files
- [ ] Create `laravel/Themes/Two/lang/it/header.php`
- [ ] Create `laravel/Themes/Two/lang/en/header.php`
- [ ] Add all navigation items translations
- [ ] Add CTA button translations
- [ ] Add dropdown menu translations

### Phase 3: Implement Header Component
- [ ] Create base header structure
- [ ] Implement brand/logo section
- [ ] Implement navigation menu
- [ ] Add CTA button
- [ ] Implement mobile menu button
- [ ] Add language selector

### Phase 4: Add Login Integration
- [ ] Implement guest state (login/register buttons)
- [ ] Implement authenticated state (avatar dropdown)
- [ ] Add dropdown menu options
- [ ] Style avatar and dropdown

### Phase 5: Test & Verify
- [ ] Test on desktop
- [ ] Test on mobile
- [ ] Test language switching
- [ ] Test login/logout flows
- [ ] Verify all links work

## 📚 Key Patterns & Best Practices

### Translation Pattern (from Lang module)
```php
// In blade template
{{ __('header::home.label') }}

// Translation file structure
return [
    'home' => [
        'label' => 'Home',
        'url' => '/it/pages/home',
    ],
];
```

### Authentication Pattern
```php
@guest
    <!-- Show login/register buttons -->
@else
    <!-- Show avatar with dropdown -->
@endguest
```

### Navigation Data Access
```php
// Access header.json data
$headerData = \Illuminate\Support\Facades\Config::get('local.techplanner.database.content.sections.header');
$locale = app()->getLocale();
$navData = $headerData['blocks'][$locale][0]['data'] ?? null;
```

## 🔗 Related Documentation
- [Lang Module README](../../../Modules/Lang/docs/README.md)
- [Folio + Volt Best Practices](../../../Modules/Lang/docs/folio-volt-best-practices.md)
- [Translation Patterns](../../../Modules/Lang/docs/traduzioni-navigation.md)
- [User Module README](../../../Modules/User/docs/README.md)

## ✅ Success Criteria
- [ ] Header matches target site design
- [ ] All links work correctly
- [ ] Multi-language support functional
- [ ] Login/logout integration working
- [ ] Responsive design on all devices
- [ ] No PHPStan errors
- [ ] Documentation updated

---

*Created by: iFlow CLI*
