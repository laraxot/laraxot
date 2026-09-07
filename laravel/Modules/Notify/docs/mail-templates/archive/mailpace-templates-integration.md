---
title: "Integrazione MailPace Templates"
type: concept
tags: [mailpace, templates, integration]
created: 2026-07-14
updated: 2026-07-14
qmd: "mailpace-templates-integration integrazione mailpace templates"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./email-best-practices.md"
  - "./email-layouts-best-practices.md"
  - "./email-templates-best-practices.md"
  - "./email-templates-guide.md"
  - "./email-templates-update.md"
  - "./filament-slug-generation.md"
  - "./filament-ui-enhancements.md"
  - "./html-email-compatibility.md"
---

# Integrazione MailPace Templates

## Panoramica

<<<<<<< HEAD
<<<<<<< HEAD
Questo documento descrive l'integrazione dei template email [mailpace/templates](https://github.com/mailpace/templates) nel modulo Notify di SaluteOra. Questi template offrono un design moderno basato su TailwindCSS con supporto nativo per la modalità scura.
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Questo documento descrive l'integrazione dei template email [mailpace/templates](https://github.com/mailpace/templates) nel modulo Notify di SaluteOra. Questi template offrono un design moderno basato su TailwindCSS con supporto nativo per la modalità scura.
>>>>>>> a988596b (first)
Questo documento descrive l'integrazione dei template email [mailpace/templates](https://github.com/mailpace/templates) nel modulo Notify di <nome progetto>. Questi template offrono un design moderno basato su TailwindCSS con supporto nativo per la modalità scura.

## Template Disponibili

MailPace offre i seguenti template transazionali:

1. **Welcome** - Email di benvenuto per nuovi utenti
2. **Email Confirmation** - Conferma dell'indirizzo email
3. **Password Reset** - Ripristino password
4. **Receipt** - Ricevuta per acquisti
5. **Security Alert** - Avviso di sicurezza
6. **Account Deleted** - Notifica di eliminazione account

## Vantaggi dell'Utilizzo

- **Responsive Design** - Ottimizzati per tutti i dispositivi e client email
- **Dark Mode** - Supporto nativo per la modalità scura
- **Accessibilità** - Design accessibile e leggibile
- **Performance** - Ottimizzati per caricamento veloce
- **Personalizzazione** - Facilmente personalizzabili con Maizzle

<<<<<<< HEAD
<<<<<<< HEAD
## Integrazione 
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
## Integrazione 
>>>>>>> a988596b (first)
## Integrazione

### Struttura della Directory

```
<<<<<<< HEAD
<<<<<<< HEAD
/var/www/html/saluteora/laravel/Modules/Notify/resources/mail-layouts/
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
/var/www/html/saluteora/laravel/Modules/Notify/resources/mail-layouts/
>>>>>>> a988596b (first)
[project-root]/laravel/Modules/Notify/resources/mail-layouts/
├── default.html       # Layout base per la maggior parte delle email
├── main.html          # Alternativa semplificata
├── marketing.html     # Layout ottimizzato per email marketing
└── notification.html  # Layout specifico per notifiche
```

### Processo di Integrazione

1. **Installazione delle Dipendenze**
   ```bash
   npm i -g @maizzle/cli
   cd /percorso/templates
   npm install
   ```

2. **Personalizzazione dei Template**
   ```bash
   npm run dev          # Avvia ambiente di sviluppo
   # Modifica i template secondo necessità
   npm run build        # Genera i template ottimizzati
   ```

3. **Copia dei Template Generati**
   Copia i file HTML dalla directory `dist/` alla directory `resources/mail-layouts/` del modulo Notify.

## Utilizzo dei Template

### Nel Codice

```php
// In un mailable di Laravel
public function build()
{
    return $this->view('notify::emails.welcome')
                ->subject('Benvenuto su '.config('app.name'))
                ->with([
                    'name' => $this->user->name,
                    'actionUrl' => $this->actionUrl,
                ]);
}
```

### Con Spatie/Laravel-Mail-Template

```php
// Nel controller
use Modules\Notify\Models\MailTemplate;

$mailTemplate = MailTemplate::findBySlug('welcome-email');
$mailTemplate->send($user->email, [
<<<<<<< HEAD
<<<<<<< HEAD
    'name' => $user->name, 
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    'name' => $user->name, 
>>>>>>> a988596b (first)
    'name' => $user->name,
    'action_url' => $actionUrl
]);
```

## Linee Guida per la Personalizzazione

1. **Mantieni la Struttura Base** - Non modificare la struttura HTML base per garantire compatibilità
2. **Usa Variabili** - Utilizza variabili Blade per contenuti dinamici
3. **Test Cross-Client** - Testa i template su diversi client email
<<<<<<< HEAD
<<<<<<< HEAD
4. **Segui le Convenzioni di Branding** - Usa i colori e font definiti per SaluteOra
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
4. **Segui le Convenzioni di Branding** - Usa i colori e font definiti per SaluteOra
>>>>>>> a988596b (first)
4. **Segui le Convenzioni di Branding** - Usa i colori e font definiti per <nome progetto>

## Riferimenti

- [Documentazione Maizzle](https://maizzle.com/docs/)
- [Repository MailPace Templates](https://github.com/mailpace/templates)
- [Guida Spatie Email](../spatie-email-usage-guide-1.md)
- [Implementazione Slug Field](./slug-field-implementation-1.md)
