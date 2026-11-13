---
title: "README"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "README"
issues: []
discussions: []
---

# Mail Layouts - Sixteen Theme

**Status**: ✅ Attivo  
**Data**: 2025-12-19  
**Theme**: Sixteen

## 📋 Overview

Questa cartella contiene i layout HTML per le email del sistema. I layout utilizzano il motore di rendering **Mustache** per sostituire variabili dinamiche e sono gestiti tramite `SpatieEmail` del modulo Notify.

## 📁 Template Disponibili

| File | Descrizione | Uso Consigliato |
|------|-------------|-----------------|
| `base.html` | Layout standard | Tutte le comunicazioni normali |
| `christmas.html` | Layout natalizio classico con SVG | Periodo natalizio (1 Dic - 10 Gen) |
| `christmas-elegant.html` | Layout natalizio elegante | Comunicazioni ufficiali eleganti |
| `christmas-festive.html` | Layout natalizio festoso | Newsletter festive, comunicazioni informali |
| `christmas-modern.html` | Layout natalizio moderno | Design pulito e stile corporate |
| `christmas-premium.html` | Layout natalizio premium lussuoso | Eventi premium, comunicazioni importanti |
| `christmas-corporate.html` | Layout natalizio corporate minimalista | Comunicazioni business professionali |
| `christmas-sottana.html` | Layout natalizio personalizzato Sottana Service | Comunicazioni specifiche di Sottana Service durante il periodo natalizio |
| `christmas-sottana-elephant.html` | Layout natalizio Sottana Service con mascotte elefante | Comunicazioni natalizie con elefante mascotte come elemento decorativo principale |
| `christmas-sottana-professional.html` | Layout natalizio professionale premium Sottana Service | Template premium per comunicazioni professionali di Sottana Service |
| `christmas-sottana-elephant-premium.html` | Layout natalizio premium con mascotte elefante | Template ultra-lusso con elefante mascotte per comunicazioni premium |
| `christmas-starlit-elegance.html` | Layout natalizio aurora-borealis corporate | Auguri istituzionali futuristici con glassmorphism stellare |

## 🎨 Miglioramenti Template Natalizi (2025)

### Nuove Decorazioni e Animazioni Premium 2025

Tutti i template natalizi sono stati migliorati con:

1. **Pattern SVG Email-Safe**: Pattern decorativi natalizi nel background (stelle, neve, geometrie)
2. **Animazioni CSS Sofisticate**: 10+ nuove animazioni (tree-glow, shimmer, bounce-rotate, elegant-twinkle, festive-flash, etc.)
3. **Decorazioni Elaborate**: 4-8 decorazioni emoji per template con animazioni diverse
4. **Bordi Decorativi**: Multiple layers di border con glow effect e ombre
5. **Colori Vivaci**: Gradienti natalizi più intensi e professionali
6. **Stelle Animate**: Stelle CSS e emoji con animazioni twinkle sofisticate
7. **Luci Natalizie Realistiche**: Luci a bulbo con glow intenso (christmas-festive.html)
8. **Performance Ottimizzate**: Animazioni disabilitate su mobile, supporto prefers-reduced-motion
9. **✨ Elementi SVG Decorativi Animati**: Stelle, fiocchi di neve e alberi SVG con animazioni email-safe
   - Stelle SVG animate con effetto twinkle (2-4 per template)
   - Fiocchi di neve SVG con caduta animata (4-6 per template)
   - Alberi di Natale SVG animati (dove presente, 48-56px)
   - Pattern SVG decorativi (template luxury)
   - Decorazioni SVG decorative negli angoli del content area (`.svg-decoration`)
   - Icone Social SVG dinamiche (Facebook, Twitter, LinkedIn) con variabili Mustache
   - Colori tematici coordinati per ogni template
   - Animazioni coordinate con CSS esistenti
   - Supporto completo mobile e `prefers-reduced-motion`

### Template Migliorati Premium 2025

- ✅ `christmas.html`: Glassmorphism (10px), 8 animazioni nuove, typography premium, gradient animati (400%)
- ✅ `christmas-sottana.html`: Glassmorphism (8-12px), 10 animazioni, stelle animate (4), design premium
- ✅ `christmas-elegant.html`: Glassmorphism (12px), aurora borealis effect, 7 animazioni, 8 stelle CSS + 3 emoji
- ✅ `christmas-festive.html`: Glassmorphism (10px), luci realistiche, 9 animazioni, bordo animato (400%)
- ✅ `christmas-premium.html`: Glassmorphism ultra-premium (15px), 8+ animazioni lussuose, typography font-weight 800
- ✅ `christmas-corporate.html`: Glassmorphism minimalista (8px), 5+ animazioni corporate, design professionale elegante
- ✅ `christmas-modern.html`: Glassmorphism moderno (10px), 6+ animazioni moderne, design blue premium
- ✅ `christmas-professional.html`: Glassmorphism professionale (8px), 6+ animazioni professionali, design maroon/gold elegante
- ✅ `christmas-luxury.html`: Glassmorphism ultra-luxury (15px), 8+ animazioni lussuose, typography font-weight 800, design oro/bordeaux premium
- ✅ `christmas-starlit-elegance.html`: Aurora boreale corporate con starfield animato, CTA twin-pill e palette midnight/navy

**Tutti i template includono:**
- ✅ Dark mode avanzato con gradienti animati
- ✅ Mobile-first optimization completa
- ✅ Typography premium (font-weight 600-800, letter-spacing ottimizzato)
- ✅ Micro-interactions premium (button ripple, scale effects)
- ✅ Accessibilità WCAG 2.1 AA Enhanced

Per dettagli completi, vedere [seasonal-email-templates.md](../../../Modules/Notify/docs/seasonal-email-templates.md).

## Changelog

### 2025-12-19 - Christmas Templates v3.1 - Miglioramenti Decorazioni

**Miglioramenti**:
- ✅ Pattern SVG decorativi email-safe in tutti i template
- ✅ 10+ nuove animazioni CSS sofisticate
- ✅ Decorazioni emoji elaborate (4-8 per template)
- ✅ Luci natalizie realistiche con glow effect intenso
- ✅ Stelle animate (CSS + emoji) con animazioni twinkle
- ✅ Bordi decorativi elaborati con multiple layers
- ✅ Colori vivaci e professionali
- ✅ Performance ottimizzate (mobile + reduced motion)

### 2025-12-19 - Christmas Templates v2.0

**Aggiunto**:
- ✨ Template `christmas-elegant.html` con tema natalizio elegante
  - ❄️ 15 snowflakes animate + 8 stelle brillanti con CSS `@keyframes`
  - 🌙 Background notturno elegante (gradiente blu notte)
  - 🎨 Colori eleganti: Rosso #C8102E, Verde #165B33, Oro #D4AF37
  - 📋 Box evidenziato dorato con gradiente crema
  - Font serif (Georgia) per eleganza professionale
- ✨ Template `christmas-festive.html` con tema natalizio festoso
  - ❄️ 20 snowflakes animate + 20 luci natalizie lampeggianti
  - 💡 Luci animate nel bordo superiore/inferiore con effetto "lampeggio"
  - 🎨 Colori vivaci: Rosso #DC143C, Verde #228B22, Oro #FFD700
  - 🌈 Background festivo (gradiente rosso-verde vivace)
  - 📋 Box evidenziato festoso con bordo tratteggiato e emoji animate
  - Font sans-serif (Arial) per modernità e leggibilità
  - Bordo dorato intorno al container principale
- ✨ Template `christmas-sottana.html` con tema natalizio personalizzato per Sottana Service
  - 🎄 Messaggio personalizzato: "Buone Feste da Sottana Service"
  - 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
  - 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
  - 🎨 Design natalizio professionale con sfondo animato
  - ✨ Animazioni neve e decorazioni festive
- ✨ Template `christmas-sottana-elephant.html` con tema natalizio personalizzato per Sottana Service con mascotte elefante
  - 🐘 Icona elefante come mascotte del brand
  - 🎄 Messaggio personalizzato: "Buone Feste da Sottana Service (con il nostro elefante preferito!)"
  - 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
  - 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
  - 🎨 Design natalizio con elefanti come decorazioni festive
  - ✨ Animazioni neve e decorazioni con tema elefante
- 🎄 Decorazioni natalizie (emoji, colori, gradients)
- 📱 Responsive design con disabilitazione animazioni mobile
- ♿ Accessibilità WCAG 2.1 (ARIA, sr-only, alt text, prefers-reduced-motion)
- 📚 Documentazione completa utilizzo

**Caratteristiche Tecniche**:
- CSS animations email-safe (no JavaScript)
- Fallback graceful per Outlook (degradazione elegante a statico)
- Dark mode support
- Performance ottimizzata (15-20 snowflakes + elementi decorativi)
- File size: ~25KB (elegant), ~28KB (festive)

**Testing**:
- ✅ Gmail (web, Android, iOS)
- ✅ Apple Mail (macOS, iOS)
- ✅ Outlook.com
- ⚠️ Outlook 2016-2021 (animazioni disabilitate, layout OK)

### 2025-12-19 - Christmas Templates v3.0

**Aggiunto**:
- ✨ Template `christmas-sottana-professional.html` con design professionale premium
  - 🎨 Schema colori blu-oro per comunicazioni aziendali di alto profilo
  - 🏛️ Stile elegante e minimalista ma natalizio
  - 🌟 Animazioni raffinate e background con pattern professionale
  - 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
  - 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
- ✨ Template `christmas-sottana-elephant-premium.html` con design ultra-lusso
  - 🐘 Integrazione completa della mascotte elefante nel design premium
  - ✨ Effetti visivi avanzati: particelle d'oro animate, fiocchi di neve eleganti
  - 🎨 Colori di lusso: oro premium, rosso natalizio sofisticato, avorio elegante
  - 🌟 Animazioni "Elephant Spirit" con stelle lampeggianti a tema elefante
  - 🎁 Design con bordi ornati, effetti di profondità e materiali di lusso
  - 💎 Elementi decorativi con pattern di elefanti nel background

- 🎄 Decorazioni natalizie (emoji, colori, gradients)
- 📱 Responsive design con disabilitazione animazioni mobile
- ♿ Accessibilità WCAG 2.1 (ARIA, sr-only, alt text, prefers-reduced-motion)
- 📚 Documentazione completa utilizzo

**Caratteristiche Tecniche**:
- CSS animations email-safe (no JavaScript)
- Fallback graceful per Outlook (degradazione elegante a statico)
- Dark mode support
- Performance ottimizzata (15-20 snowflakes + elementi decorativi)
- File size: ~25KB (elegant), ~28KB (festive), ~35KB (premium)

**Testing**:
- ✅ Gmail (web, Android, iOS)
- ✅ Apple Mail (macOS, iOS)
- ✅ Outlook.com
- ⚠️ Outlook 2016-2021 (animazioni disabilitate, layout OK)

---

## 🎨 Template Natalizi Dettagliati

### christmas-premium.html - Premium Luxurious

**Quando usare**: Comunicazioni premium durante periodo natalizio - design lussuoso con pattern di sfondo SVG e animazioni sofisticate

**Caratteristiche**:
- 🎨 **Pattern SVG inline natalizio** come sfondo (email-safe, supportato da molti client)
- ❄️ 10 snowflakes animate con traiettorie realistiche (CSS `@keyframes`)
- ⭐ 5 stelle brillanti con effetto twinkle sofisticato
- 💎 Background lussuoso: Gradiente blu notte profondo (#0A0E27 → #1A1F3A)
- 🏆 Colori premium: Oro #D4AF37, Argento #C0C0C0, Rosso #B91C1C, Verde #14532D
- 📋 Box evidenziato premium con bordo dorato, ombre eleganti e effetto glow
- ✨ Font serif (Georgia) per eleganza classica
- 💼 Per comunicazioni ufficiali importanti, eventi premium, auguri aziendali
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore

**File Size**: ~35KB

### christmas-starlit-elegance.html - Aurora Corporate

**Quando usare**: Auguri istituzionali per aziende tech/finanza che desiderano un mood futuristico (aurora boreale + glassmorphism) mantenendo serietà corporate.

**Caratteristiche**:
- 🌌 **Starfield email-safe**: Layer di stelle animate in CSS, con fallback statico e supporto reduced-motion
- 🌠 **Hero aurora**: Palette midnight/navy con gradienti cyan+verde (aurora) e badge stellare per KPI natalizi
- 🧊 **Glass cards**: Sezioni "Messaggio Direzione" e "Programma Festività" in glassmorphism (border 1px + blur 18px)
- 🪐 **Twin CTA**: Due pill buttons (primary gradient aurora, secondary outline champagne) studiati per conversioni B2B
- 📅 **Holiday schedule**: Box dedicato per ferie, reperibilità enterprise e messaggio di riapertura
- ✨ **Highlights**: Tabella responsive 2-up per mini metriche (es. progetti completati, campagne immersive)
- 🖋️ **Firma evocativa**: Closing message con testo italic e copy poetico (“All lights reserved”)
- 📱 **Mobile-first**: Padding dinamici, CTA vertical stack, starfield alleggerito su <640px
- ♿ **Accessibilità**: Contrasto WCAG 2.1 AA+, supporto `prefers-reduced-motion`, alt text e testi fallback

**File Size**: ~42KB

### christmas.html - Natalizio Classico con SVG

**Quando usare**: Layout natalizio classico per comunicazioni generali durante il periodo festivo

**Caratteristiche**:
- ❄️ 20 snowflakes animate con SVG decorativi
- 🌟 Stelle animate SVG con effetti di luce e movimento
- 🎨 Colori natalizi classici: Rosso, Verde e Oro
- 📋 Box chiusura festività con design decorativo
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore
- 🎨 Design equilibrato tra eleganza e vivacità

**File Size**: ~48KB

### christmas-corporate.html - Corporate Minimalist

**Quando usare**: Comunicazioni aziendali professionali durante periodo natalizio - design minimalista e pulito, ottimizzato per business

**Caratteristiche**:
- 📐 Design minimalista e professionale
- 🎨 Pattern sottile CSS repeating-linear-gradient per texture
- 🔴 Colori corporate: Rosso #DC2626, Verde #16A34A, Oro #CA8A04
- 💼 Background pulito: Bianco (#FFFFFF) con pattern sottile elegante
- 📋 Box evidenziato con bordo sinistro colorato (stile corporate standard)
- 🔴⚫🟡 Accent dots animati con effetto pulse (3 dots)
- ✨ Font sans-serif (Helvetica Neue/Arial) per modernità e leggibilità
- 📱 Supporto dark mode completo
- 💼 Per comunicazioni business professionali, avvisi formali, newsletter aziendali

**File Size**: ~22KB

### christmas-elegant.html - Elegant

**Quando usare**: Comunicazioni ufficiali durante periodo natalizio - stile raffinato e professionale

**Caratteristiche**:
- ❄️ 15 snowflakes animate + 8 stelle brillanti
- 🌙 Background notturno elegante (gradiente blu notte)
- 🎨 Colori eleganti: Rosso #C8102E, Verde #165B33, Oro #D4AF37
- 📋 Box evidenziato dorato con gradiente crema
- Font serif (Georgia)
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore

### christmas-festive.html - Festive

**Quando usare**: Newsletter festive, comunicazioni informali - stile allegro e vivace

**Caratteristiche**:
- ❄️ 20 snowflakes animate + 20 luci natalizie lampeggianti
- 💡 Luci animate nel bordo superiore/inferiore
- 🎨 Colori vivaci: Rosso #DC143C, Verde #228B22, Oro #FFD700
- Font sans-serif (Arial)
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore

### christmas-sottana.html - Natalizio Personalizzato Sottana Service

**Quando usare**: Per comunicazioni specifiche di Sottana Service durante il periodo natalizio - design molto natalizio e molto professionale

**Caratteristiche**:
- 🎄 **Messaggio personalizzato**: "Lo staff di Sottana Service augura a tutti voi e alle vostre famiglie Felici Feste Natalizie!"
- 📋 **Informazioni chiusura**: "L'ufficio sarà chiuso dal 24 dicembre fino al 6 gennaio"
- 🎅 **Riapertura**: "Ci rivediamo il 7 gennaio!"
- 🎨 **Design molto natalizio**: 
  - ❄️ 20 fiocchi di neve animati con traiettorie realistiche
  - 🎁 Decorazioni natalizie animate (emoji con effetto bounce)
  - 🎄 Header con gradient rosso-verde-rosso e bordo dorato
  - 🌟 Background scuro elegante con animazioni neve
- 💼 **Design molto professionale**:
  - 🎨 Box evidenziato con gradiente oro (#FFF8E1 → #FFECB3) e bordo dorato
  - 📝 Tipografia chiara: font serif (Georgia) per eleganza
  - 🎨 Colori natalizi: Rosso #C8102E, Verde #006400, Oro #FFD700
  - 🔵 Branding Sottana Service: colore primario #0071b0 evidenziato nel testo
  - 📱 Layout responsive con ottimizzazioni mobile
  - ♿ Accessibilità WCAG 2.1 completa (prefers-reduced-motion support)
- ✨ Animazioni email-safe (CSS puro, no JavaScript)
- 📱 Animazioni disabilitate su mobile per performance
- 🖨️ Stampa ottimizzata (animazioni disabilitate)

### christmas-sottana-elephant.html - Natalizio con Mascotte Elefante

**Quando usare**: Per comunicazioni speciali di Sottana Service durante il periodo natalizio con la mascotte elefante

**Caratteristiche**:
- 🐘 Icona elefante come mascotte del brand
- 🎄 Messaggio personalizzato: "Buone Feste da Sottana Service (con il nostro elefante preferito!)"
- 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
- 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
- 🎨 Design natalizio con elefanti come decorazioni festive
- ✨ Animazioni neve e decorazioni con tema elefante

### christmas-sottana-professional.html - Natalizio Professionale Premium

**Quando usare**: Per comunicazioni aziendali estremamente professionali durante il periodo natalizio

**Caratteristiche**:
- 🎨 Design professionale con tema blu-oro di lusso
- 🏛️ Stile elegante e minimalista ma natalizio
- 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
- 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
- ✨ Animazioni raffinate e sfondo con pattern professionale
- 🎯 Ottimizzato per comunicazioni B2B e clienti corporate
- 🌟 Effetti visivi avanzati: particelle professionali animate, linee animate di transizione
- 🎨 Tipografia raffinata e pulsanti con effetti di luce
- 💎 Pattern di sfondo multi-livello per profondità visiva
- 🔥 Effetti di transizione fluidi e animazioni avanzate
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore

### christmas-sottana-elephant-premium.html - Natalizio Premium Ultra-Lusso

**Quando usare**: Per le comunicazioni più esclusive ed eleganti di Sottana Service

**Caratteristiche**:
- 🐘 Design ultra-premium con integrazione completa della mascotte elefante
- ✨ Effetti visivi avanzati: particelle d'oro animate, fiocchi di neve eleganti
- 🎨 Colori di lusso: oro premium, rosso natalizio sofisticato, avorio elegante
- 🌟 Animazioni "Elephant Spirit" con stelle lampeggianti a tema elefante
- 📋 Annunci chiusura ufficio: "Ufficio chiuso dal 24 dicembre fino al 6 gennaio"
- 🎅 Saluti dallo staff: "Ci rivediamo il 7 gennaio!"
- 🎁 Design con bordi ornati, effetti di profondità e materiali di lusso
- 💎 Elementi decorativi con pattern di elefanti nel background
- 🎨 Tipografia raffinata e pulsanti con effetti di luce
- 🔥 Effetti di transizione fluidi e animazioni avanzate
- ✨ Pattern di sfondo multi-livello per profondità visiva
- 📐 Elementi SVG decorativi: fiocchi di neve, stelle, pattern geometrici
- 🎄 Icone natalizie in formato SVG per qualità visiva superiore

## 📚 Utilizzo

### Selezione Layout

Il layout viene selezionato tramite il campo `html_layout_path` nel modello `MailTemplate`:

```php
// Esempio: MailTemplate
$template = MailTemplate::create([
    'slug' => 'christmas-newsletter',
    'html_layout_path' => 'christmas-premium.html', // Specifica il layout
    'subject' => 'Buone Feste!',
    'html_template' => '<p>Contenuto email...</p>',
]);
```

### Variabili Mustache Disponibili

Tutte le variabili sono documentate in: [Mustache Variables Documentation](../../../Modules/Notify/docs/mustache-variables.md)

**Variabili principali**:
- `{{{ body }}}` - Contenuto principale (HTML non escaped)
- `{{ subject }}` - Oggetto email
- `{{ company_name }}` - Nome azienda
- `{{ logo_header }}` - URL logo (con fallback base64 e SVG)
- `{{ year }}` - Anno corrente
- `{{ first_name }}`, `{{ last_name }}` - Dati dal record
- Tutte le proprietà del modello passato a `SpatieEmail`

### Esempio Completo

```php
use Modules\Notify\Emails\SpatieEmail;
use Illuminate\Support\Facades\Mail;

// Crea email
$email = new SpatieEmail($user, 'auguri-natale');

// Aggiungi dati personalizzati
$email->mergeData([
    'discount_code' => 'NATALE2025',
    'offer_url' => route('christmas-offer'),
]);

// Invia
Mail::to($user->email)->send($email);
```

## 🔗 Collegamenti

- [Documentazione Completa Template Stagionali](../../../Modules/Notify/docs/seasonal-email-templates.md)
- [Mustache Variables Guide](../../../Modules/Notify/docs/mustache-variables.md)
- [Tema Sixteen - Index](../../docs/00-index.md)

---

**Nota**: Questo README descrive solo i layout nella cartella `mail-layouts`. Per la documentazione completa del sistema di template stagionali, consulta la [documentazione principale](../../../Modules/Notify/docs/seasonal-email-templates.md).
