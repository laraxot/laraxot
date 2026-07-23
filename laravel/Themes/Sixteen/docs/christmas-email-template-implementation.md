# Christmas Email Template Implementation

**Date**: 2025-12-19  
**Status**: ✅ Completed  
**Module**: Notify  
**Theme**: Sixteen

## Overview

Implementazione del template email natalizio per il tema Sixteen. Questo documento descrive l'implementazione tecnica del template `christmas.html` e il suo utilizzo nel sistema di email stagionali.

## Implementation Details

### File Structure
```
Themes/Sixteen/resources/mail-layouts/
├── base.html           # Template base
└── christmas.html      # Template natalizio (nuovo)
```

### Technical Features
- **CSS Animations**: 20 snowflakes animate con CSS `@keyframes`
- **Responsive Design**: Adatta per desktop e mobile
- **Email Compatibility**: Supporta i principali client email
- **Accessibility**: WCAG 2.1 compliant con ARIA labels
- **Dark Mode**: Supporto per modalità scura

### Christmas Elements
- **Colori**: Rosso (#C8102E), Verde (#006400), Oro (#FFD700)
- **Decorazioni**: Emoji festive (🎄, 🎅, 🎁), box chiusura studio
- **Font**: Georgia per eleganza natalizia
- **Background**: Sfondo scuro con effetto neve

## Integration

### SpatieEmail Integration
Il template è integrato automaticamente con il sistema `SpatieEmail`:

```php
// Modules/Notify/app/Emails/SpatieEmail.php
public function getHtmlLayout(): string
{
    return app(GetMailLayoutAction::class)->execute();
}
```

### Automatic Selection
Durante il periodo 1 Dicembre - 10 Gennaio, `GetMailLayoutAction` seleziona automaticamente `christmas.html`.

## Code Implementation

### HTML Structure
- Layout table-based (compatibile Outlook)
- CSS inline per massima compatibilità
- Reset CSS per normalizzazione rendering
- Variabili Mustache per contenuti dinamici

### Animations CSS
```css
@keyframes snowfall {
    0% { transform: translateY(-10%) translateX(0); opacity: 0.8; }
    100% { transform: translateY(100vh) translateX(20px); opacity: 0; }
}
```

### Mobile Optimization
- Animazioni disabilitate su mobile per performance
- Layout responsive con media queries
- Fallback graceful per client limitati

## Quality Assurance

### ✅ Test Effettuati
- [x] Apple Mail (macOS/iOS) - Animazioni OK
- [x] Gmail (web, Android, iOS) - Layout OK
- [x] Outlook.com - Layout OK
- [x] Outlook 2016-2021 - Layout OK (animazioni ignorate)
- [x] Mobile responsive - OK
- [x] Dark mode - OK
- [x] Accessibilità - OK

### ✅ Performance
- File size: ~25KB
- 20 snowflakes (ottimizzato)
- Mobile-optimized (animazioni disabilitate)

## Usage Examples

### Email di Chiusura Festiva
```php
// MailTemplate per chiusura natalizia
$template = MailTemplate::create([
    'slug' => 'closure-christmas-2025',
    'subject' => 'Chiusura Festività Natalizie',
    'html_template' => '<p>Gentile {{ first_name }}, lo studio sarà chiuso dal 24 Dic al 7 Gen.</p>',
]);

$email = new SpatieEmail($client, 'closure-christmas-2025');
Mail::to($client->email)->send($email);
```

### Newsletter Natalizia
```php
$template = MailTemplate::create([
    'slug' => 'christmas-newsletter',
    'subject' => '🎄 Offerta Speciale Natale',
    'html_template' => '<h2>Regali Speciali per i Nostri Clienti!</h2>',
]);

$email = new SpatieEmail($client, 'christmas-newsletter');
Mail::to($client->email)->send($email);
```

## Maintenance

### Aggiornamento Date
Modificare le date di chiusura nel box "Chiusura Festività" in `christmas.html`.

### Personalizzazione
- Colori: Modificare variabili CSS `--color-primary`, `--color-secondary`, `--color-accent`
- Decorazioni: Aggiungere/rimuovere emoji festive
- Messaggi: Personalizzare il box "Chiusura Festività"

## Risorse

- [christmas.html](../resources/mail-layouts/christmas.html) - Template HTML completo
- [seasonal-email-templates.md](../../../Modules/Notify/docs/seasonal-email-templates.md) - Documentazione sistema email stagionali
- [GetMailLayoutAction](../../../Modules/Notify/app/Actions/Mail/GetMailLayoutAction.php) - Azione di selezione layout

## Changelog

### 2025-12-19 - Versione 1.0
- ✨ Template `christmas.html` implementato
- ❄️ 20 snowflakes animate con CSS
- 🎄 Design natalizio completo
- 📱 Responsive + mobile optimization
- ♿ Accessibilità WCAG 2.1

---

**Implementato con DRY + KISS + Clean Code**  
**Creato per le festività 2025-2026** 🎄