# 📧 Email Deliverability Best Practices – Sixteen Theme

**Theme**: Sixteen (owner)  
**Status**: ✅ CONFIGURATO E OPERATIVO  
**Data**: 2026-11-12  

> **Obiettivo**: Template email responsivi e ottimizzati per evitare lo Spam nel tema Sixteen.

---

## 📌 Panoramica

Il tema Sixteen gestisce template email per:
- Verifica email (`auth/verify`)
- Reset password
- Notifiche utente
- Email transazionali

---

## 📋 Best Practices per Template Email

### 1. Struttura HTML Pulita

```html
<!-- ✅ CORRETTO -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica Email</title>
</head>
<body>
    <p>Benvenuto su FixCity</p>
</body>
</html>
```

### 2. Testo Alternativo

Sempre includere una versione `text` dell'email per client che non supportano HTML.

### 3. Contenuto Anti-Spam

| ✅ OK | ❌ EVITARE |
|-------|------------|
| "Ciao, benvenuto su FixCity" | "FREE MONEY ACT NOW!!!" |
| Link a dominio verificato | Link accorciati bit.ly |
| Testo chiaro e conciso | MAIUSCOLE ESESSIVE |
| Unsubscribe link presente | Nessun modo per disiscriversi |

### 4. Header Email

```php
// In ogni Mailable
->withUnsubscribeHeader()
->withReplyTo('noreply@fixcity.eu')
->withFrom('noreply@fixcity.eu', 'FixCity')
```

---

## 📦 Test di Rendering

```bash
# Test invio via sendmail
php artisan notify:test-sendmail marco.sottana@gmail.com

# Test invio via Mailtrap
php artisan notify:test-mailtrap
```

---

*BMAD + ECC + Second Brain + Filament Blueprint – Version 1.0*