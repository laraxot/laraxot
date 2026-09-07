# Design: Ticket Wizard Widget (Filament)

## Obiettivo

Unificare le 4 pagine di creazione ticket in un singolo Filament Wizard Widget:
- `segnalazione-01-privacy` → Step 1: Auth/Privacy (gia presente)
- `segnalazione-02-dati` → Step 2: Data (gia presente)
- `segnalazione-03-riepilogo` → Step 3: Summary (da aggiungere)
- `segnalazione-04-conferma` → Step 4: Confirmation (da aggiungere)

## Paradigma

- **Widget-based**: Il widget viene inserito nella pagina CMS come blocco
- **Forward-only**: Filament gestisce stato e navigazione tra step
- **Esistente**: Estendere `CreateTicketWidget` esistente

## Struttura

### File da modificare/creare

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
1. **`laravel/Modules/Fixcity/app/Filament/Widgets/CreateTicketWidget.php`**
   - Aggiungere Step 3 (Riepilogo) e Step 4 (Conferma)
   - Aggiornare `getFormSchema()` con 4 step

2. **`laravel/config/local/fixcity/database/content/pages/tests.ticket-create.json`**
<<<<<<< HEAD
=======
1. **`laravel/Modules/App/app/Filament/Widgets/CreateTicketWidget.php`**
   - Aggiungere Step 3 (Riepilogo) e Step 4 (Conferma)
   - Aggiornare `getFormSchema()` con 4 step

2. **`laravel/config/local/laraxot/database/content/pages/tests.ticket-create.json`**
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
   - Nuovo file JSON per la pagina
   - Definisce i blocchi della pagina

3. **`laravel/Themes/Sixteen/resources/views/components/blocks/tests/ticket-create.blade.php`**
   - Nuovo componente Blade per il blocco CMS

### Traduzioni

<<<<<<< HEAD
<<<<<<< HEAD
Pattern: `fixcity::ticket.steps.<item>.<tipo>`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
Pattern: `fixcity::ticket.steps.<item>.<tipo>`
>>>>>>> a988596b (first)
Pattern: `laraxot::ticket.steps.<item>.<tipo>`

```json
{
    "steps": {
        "privacy": { "label": "Privacy", "description": "..." },
        "data": { "label": "Dati", "description": "..." },
        "summary": { "label": "Riepilogo", "description": "..." },
        "confirmation": { "label": "Conferma", "description": "..." }
    }
}
```

## Step Details

### Step 1: Privacy (gia implementato)
- RichEditor: Privacy notice (readonly)
- Checkbox: Accetta termini (required)

### Step 2: Dati (gia implementato)
- Titolo, descrizione, tipo disservizio
- Luogo (mappa/indirizzo)
- Allegati (foto)
- Contatti (email, telefono)

### Step 3: Riepilogo (DA IMPLEMENTARE)
- Mostra tutti i dati inseriti
- Edit button per tornare a step specifici
- Submit per invio

### Step 4: Conferma (DA IMPLEMENTARE)
- Messaggio di successo
- Numero protocollo
- Link a segnalazioni utente
- Link a "Nuova segnalazione"

## Integrazione CMS

Il widget viene esposto come blocco CMS:
- **Tipo**: `filament-widget`
<<<<<<< HEAD
<<<<<<< HEAD
- **Widget**: `Modules\Fixcity\Filament\Widgets\CreateTicketWidget`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- **Widget**: `Modules\Fixcity\Filament\Widgets\CreateTicketWidget`
>>>>>>> a988596b (first)
- **Widget**: `Modules\App\Filament\Widgets\CreateTicketWidget`
- **Posizionamento**: Main content area

## Note

- Le 4 pagine vecchie rimangono (backward compatibility)
- Il widget usa la stessa logica di salvataggio esistente
- Le traduzioni estendono il pattern esistente

## TODO

1. Estendere `CreateTicketWidget` con Step 3-4
2. Creare file JSON per pagina `segnalazione-crea`
3. Creare componente Blade per il blocco
4. Aggiungere traduzioni mancanti
5. Testare il wizard completo