# Christmas Email Layout Template

**Date**: 2025-12-18  
**Theme**: Sixteen  
**Status**: ✅ Completed  
**Type**: Email Layout

## Overview

This document describes the Christmas-themed email layout template (`christmas.html`) created for the Sixteen theme. The template is designed to provide a festive email experience during the holiday season with Christmas styling, animations, and a closure notice message.

## Features

### ✅ Christmas-Themed Design
- **Color Scheme**: Christmas red (`#C8102E`) and green (`#008000`) theme
- **Visual Elements**: Christmas decorations (🎄, 🎁, ⛄, 🎅)
- **Animations**: Floating effects for visual interest
- **Gradients**: Festive gradient backgrounds

### ✅ Holiday Message Integration
- **Greeting Banner**: Prominent Christmas and New Year greeting
- **Closure Notice**: Clear notification about closure from December 24 to January 7
- **Bilingual Support**: Fully supports the existing template variables

### ✅ Responsive Design
- **Mobile Compatible**: Adapts to all screen sizes
- **Email Client Support**: Compatible with major email clients
- **Accessibility**: Includes proper semantic markup

## Template Structure

### File Location
```
Themes/Sixteen/resources/mail-layouts/christmas.html
```

### Key Sections
1. **Header**: Christmas-themed with company logo
2. **Greeting Banner**: Festive holiday message
3. **Closure Notice**: Prominent closure dates information
4. **Content Area**: `{{{ body }}}` placeholder for dynamic content
5. **Footer**: Company information with Christmas styling

## Usage

### Integration with SpatieEmail

#### Metodo 1: Automatico con Zen Context Engine (Approccio Consigliato)

Il template viene utilizzato automaticamente tramite l'azione `GetMailLayoutAction` che si interfaccia con `GetThemeContextAction` per selezionare il layout appropriato:

```php
// File: Modules/Notify/app/Emails/SpatieEmail.php
use Modules\Notify\Actions\Mail\GetMailLayoutAction;

public function getHtmlLayout(): string
{
    // Il sistema Zen rileva 'christmas' e cerca 'christmas.html'
    return app(GetMailLayoutAction::class)->execute();
}
```

Durante il periodo natalizio (1 Dicembre - 10 Gennaio), l'azione selezionerà automaticamente `christmas.html` (ex `natale.html`).

Durante il periodo natalizio (1 Dicembre - 10 Gennaio), l'azione selezionerà automaticamente `christmas.html`.

#### Metodo 2: Manuale (Legacy)

Se si desidera selezionare manualmente il layout natalizio:

```php
// File: Modules/Notify/app/Emails/SpatieEmail.php

public function getHtmlLayout(): string
{
    $xot = XotData::make();
    $pub_theme = $xot->pub_theme;
    $pubThemePath = base_path('Themes/'.$pub_theme);

    // Layout natalizio
    $pathToLayout = $pubThemePath.'/resources/mail-layouts/christmas.html';

    return file_get_contents($pathToLayout);
}
```

### Template Variables
- `{{{ body }}}` - Main content placeholder
- `{{ subject }}` - Email subject
- `{{ preheader_text }}` - Preheader text
- `{{ logo_header }}` - Company logo
- `{{ company_name }}` - Company name
- `{{ company_address }}` - Company address
- `{{ facebook_url }}` - Facebook URL
- `{{ twitter_url }}` - Twitter URL
- `{{ linkedin_url }}` - LinkedIn URL
- `{{ site_url }}` - Website URL
- `{{ unsubscribe_url }}` - Unsubscribe URL
- `{{ year }}` - Current year

## Design Elements

### Visual Features
- **Gradient Headers**: Christmas-themed color gradients
- **Festive Decorations**: Christmas symbols throughout
- **Floating Animations**: Subtle movement effects
- **Border Accents**: Red border for visual emphasis

### Accessibility
- **Skip Links**: Proper accessibility markup
- **Semantic HTML**: Proper heading hierarchy
- **Contrast**: High contrast for readability
- **Screen Reader Support**: Proper ARIA labels

## Architecture Compliance

### ✅ DRY Principle
- Reuses existing CSS variables and structure from base template
- Maintains compatibility with existing styling system
- Follows same template variable system

### ✅ KISS Principle
- Simple, clean design with festive elements
- Easy to maintain and update
- Clear separation of content and presentation

## Implementation Notes

### CSS Variables
The template uses the same CSS variable system as the base template but with Christmas-themed colors:
- `--color-primary`: `#C8102E` (Christmas Red)
- `--color-secondary`: `#008000` (Christmas Green)

### Animations
- Floating effects for decorative elements
- Smooth transitions for interactive elements
- Disabled for print and reduced motion preferences

## Quality Assurance

### ✅ Cross-Client Compatibility
- Tested with major email client CSS support
- Uses inline styles where necessary
- Avoids unsupported CSS features

### ✅ Responsive Behavior
- Adapts to mobile and desktop views
- Maintains readability on small screens
- Preserves visual hierarchy

## Maintenance

### Updating the Template
To update the closure dates or greeting message, modify the relevant sections in the HTML file.

### Seasonal Usage
This template is intended for use during the Christmas season (typically December-early January).

## Related Files

- `base.html` - Base template that provided the structure
- `christmas-elegant.html` - Template natalizio elegante con neve e stelle
- `christmas-festive.html` - Template natalizio festoso con luci animate
- `SpatieEmail.php` - Class that uses the theme's mail layouts

## Template Disponibili

### christmas-elegant.html - Elegante
- Stile raffinato e professionale
- Background notturno elegante
- 15 snowflakes + 8 stelle animate
- Font serif (Georgia)
- Ideale per comunicazioni ufficiali

### christmas-festive.html - Festoso
- Stile allegro e vivace
- Background festivo rosso-verde
- 20 snowflakes + 20 luci animate
- Font sans-serif (Arial)
- Ideale per newsletter e auguri informali

## Future Enhancements

### Potential Improvements
- **Background Images**: Aggiungere immagini di sfondo natalizie (data URI)
- **More Themes**: Additional seasonal variations
- **Interactive Elements**: Holiday-themed interactive components (limitato da email client support)

---

**Created by**: iFlow CLI  
**Compliance**: DRY + KISS + Accessibility standards  
**Theme**: Sixteen