# Componenti del Tema Sixteen

## Panoramica

I componenti del tema Sixteen sono progettati per implementare le **Linee Guida di Design per i Servizi Digitali della Pubblica Amministrazione** utilizzando **Tailwind CSS**. Ogni componente è ottimizzato per accessibilità, usabilità e conformità agli standard italiani.

## Layout Components

### App Layout
Layout principale per le applicazioni web della PA.

```blade
<x-pub_theme::layout.app>
    <x-slot name="header">
        <x-pub_theme::header.main>
            <x-slot name="logo">
                <x-pub_theme::logo />
            </x-slot>
            <x-slot name="navigation">
                <x-pub_theme::navigation.main />
            </x-slot>
        </x-pub_theme::header.main>
    </x-slot>
    
    <x-slot name="sidebar">
        <x-pub_theme::sidebar.main />
    </x-slot>
    
    <x-slot name="footer">
        <x-pub_theme::footer.main />
    </x-slot>
    
    <!-- Main content -->
    <main class="flex-1">
        {{ $slot }}
    </main>
</x-pub_theme::layout.app>
```

**Proprietà:**
- `sidebar-collapsed`: Controlla lo stato della sidebar
- `header-fixed`: Fissa l'header in cima
- `sidebar-fixed`: Fissa la sidebar

### Container Layout
Container responsive per contenuti centrati.

```blade
<x-pub_theme::layout.container size="lg">
    <div class="prose prose-lg max-w-none">
        {{ $slot }}
    </div>
</x-pub_theme::layout.container>
```

**Sizes disponibili:**
- `sm`: max-width 640px
- `md`: max-width 768px
- `lg`: max-width 1024px
- `xl`: max-width 1280px
- `2xl`: max-width 1536px

## Form Components

### Input Field
Campo di input con validazione e accessibilità.

```blade
<x-pub_theme::form.input
    name="email"
    type="email"
    label="Indirizzo Email"
    placeholder="Inserisci la tua email"
    help="L'email verrà utilizzata per accedere al sistema"
    required
    :error="$errors->first('email')"
/>
```

**Proprietà:**
- `name`: Nome del campo (obbligatorio)
- `type`: Tipo di input (text, email, password, etc.)
- `label`: Etichetta del campo
- `placeholder`: Testo placeholder
- `help`: Testo di aiuto
- `required`: Campo obbligatorio
- `disabled`: Campo disabilitato
- `error`: Messaggio di errore

### Select Field
Campo select con opzioni dinamiche.

```blade
<x-pub_theme::form.select
    name="provincia"
    label="Provincia"
    :options="$province"
    placeholder="Seleziona una provincia"
    searchable
    :error="$errors->first('provincia')"
/>
```

**Proprietà:**
- `options`: Array di opzioni o Collection
- `searchable`: Abilita ricerca nelle opzioni
- `multiple`: Selezione multipla
- `max-items`: Numero massimo di elementi selezionabili

### Textarea Field
Campo di testo multi-riga.

```blade
<x-pub_theme::form.textarea
    name="descrizione"
    label="Descrizione"
    placeholder="Inserisci una descrizione dettagliata"
    rows="4"
    maxlength="500"
    :error="$errors->first('descrizione')"
/>
```

### Checkbox Field
Campo checkbox con label personalizzabile.

```blade
<x-pub_theme::form.checkbox
    name="privacy"
    label="Accetto la privacy policy"
    required
    :error="$errors->first('privacy')"
/>
```

### Radio Group
Gruppo di radio button.

```blade
<x-pub_theme::form.radio-group
    name="tipo_utente"
    label="Tipo di utente"
    :options="[
        'admin' => 'Amministratore',
        'user' => 'Utente',
        'guest' => 'Ospite'
    ]"
    :error="$errors->first('tipo_utente')"
/>
```

## Button Components

### Primary Button
Pulsante per azioni principali.

```blade
<x-pub_theme::button.primary
    type="submit"
    :disabled="$isLoading"
    wire:loading.attr="disabled"
>
    <x-pub_theme::icon.spinner wire:loading />
    Conferma Operazione
</x-pub_theme::button.primary>
```

### Loading Feedback Contract

Per azioni asincrone avviate da click, il tema espone componenti di spinner/progress feedback riusabili, ma la proprietà dello stato resta nel componente di dominio che esegue il lavoro. Esempio: geolocalizzazione browser in `Geo::AddressInput` usa spinner del tema senza spostare la logica geo dentro Sixteen.

**Varianti:**
- `primary`: Azione principale (blu)
- `secondary`: Azione secondaria (grigio)
- `success`: Azione positiva (verde)
- `warning`: Azione di avviso (giallo)
- `danger`: Azione pericolosa (rosso)
- `info`: Azione informativa (azzurro)

### Icon Button
Pulsante con icona.

```blade
<x-pub_theme::button.icon
    variant="primary"
    icon="heroicon-o-plus"
    aria-label="Aggiungi elemento"
/>
```

### Button Group
Gruppo di pulsanti correlati.

```blade
<x-pub_theme::button.group>
    <x-pub_theme::button.secondary>
        Annulla
    </x-pub_theme::button.secondary>
    <x-pub_theme::button.primary>
        Salva
    </x-pub_theme::button.primary>
</x-pub_theme::button.group>
```

## Alert Components

### Success Alert
Alert per operazioni completate con successo.

```blade
<x-pub_theme::alert.success>
    <x-slot name="icon">
        <x-pub_theme::icon.check-circle />
    </x-slot>
    Operazione completata con successo
</x-pub_theme::alert.success>
```

### Warning Alert
Alert per avvisi e informazioni importanti.

```blade
<x-pub_theme::alert.warning>
    <x-slot name="icon">
        <x-pub_theme::icon.exclamation-triangle />
    </x-slot>
    Attenzione: alcuni dati potrebbero essere incompleti
</x-pub_theme::alert.warning>
```

### Error Alert
Alert per errori e problemi.

```blade
<x-pub_theme::alert.error>
    <x-slot name="icon">
        <x-pub_theme::icon.x-circle />
    </x-slot>
    Si è verificato un errore durante l'operazione
</x-pub_theme::alert.error>
```

### Info Alert
Alert per informazioni generali.

```blade
<x-pub_theme::alert.info>
    <x-slot name="icon">
        <x-pub_theme::icon.information-circle />
    </x-slot>
    Informazione importante per l'utente
</x-pub_theme::alert.info>
```

## Card Components

### Basic Card
Card semplice per contenuti.

```blade
<x-pub_theme::card>
    <x-slot name="header">
        <h3 class="text-lg font-semibold">Titolo Card</h3>
    </x-slot>
    
    <div class="prose">
        Contenuto della card con formattazione tipografica.
    </div>
    
    <x-slot name="footer">
        <x-pub_theme::button.group>
            <x-pub_theme::button.secondary>Annulla</x-pub_theme::button.secondary>
            <x-pub_theme::button.primary>Salva</x-pub_theme::button.primary>
        </x-pub_theme::button.group>
    </x-slot>
</x-pub_theme::card>
```

### Interactive Card
Card con interazioni.

```blade
<x-pub_theme::card.interactive>
    <x-slot name="header">
        <h3 class="text-lg font-semibold">Card Interattiva</h3>
        <x-pub_theme::button.icon
            variant="ghost"
            icon="heroicon-o-ellipsis-vertical"
            size="sm"
        />
    </x-slot>
    
    Contenuto della card interattiva
</x-pub_theme::card.interactive>
```

## Navigation Components

### Main Navigation
Navigazione principale dell'applicazione.

```blade
<x-pub_theme::navigation.main>
    <x-pub_theme::navigation.item
        href="/dashboard"
        icon="heroicon-o-home"
        :active="request()->is('dashboard*')"
    >
        Dashboard
    </x-pub_theme::navigation.item>
    
    <x-pub_theme::navigation.item
        href="/users"
        icon="heroicon-o-users"
        :active="request()->is('users*')"
    >
        Utenti
    </x-pub_theme::navigation.item>
</x-pub_theme::navigation.main>
```

### Breadcrumb
Navigazione a breadcrumb.

```blade
<x-pub_theme::navigation.breadcrumb>
    <x-pub_theme::navigation.breadcrumb-item href="/">
        Home
    </x-pub_theme::navigation.breadcrumb-item>
    <x-pub_theme::navigation.breadcrumb-item href="/users">
        Utenti
    </x-pub_theme::navigation.breadcrumb-item>
    <x-pub_theme::navigation.breadcrumb-item>
        Dettagli Utente
    </x-pub_theme::navigation.breadcrumb-item>
</x-pub_theme::navigation.breadcrumb>
```

## Table Components

### Data Table
Tabella dati con ordinamento e paginazione.

```blade
<x-pub_theme::table.data>
    <x-slot name="header">
        <x-pub_theme::table.header-cell sortable="name">
            Nome
        </x-pub_theme::table.header-cell>
        <x-pub_theme::table.header-cell sortable="email">
            Email
        </x-pub_theme::table.header-cell>
        <x-pub_theme::table.header-cell sortable="created_at">
            Data Creazione
        </x-pub_theme::table.header-cell>
        <x-pub_theme::table.header-cell>
            Azioni
        </x-pub_theme::table.header-cell>
    </x-slot>
    
    @foreach($users as $user)
        <x-pub_theme::table.row>
            <x-pub_theme::table.cell>
                {{ $user->name }}
            </x-pub_theme::table.cell>
            <x-pub_theme::table.cell>
                {{ $user->email }}
            </x-pub_theme::table.cell>
            <x-pub_theme::table.cell>
                {{ $user->created_at->format('d/m/Y') }}
            </x-pub_theme::table.cell>
            <x-pub_theme::table.cell>
                <x-pub_theme::button.icon
                    variant="ghost"
                    icon="heroicon-o-pencil"
                    size="sm"
                />
            </x-pub_theme::table.cell>
        </x-pub_theme::table.row>
    @endforeach
</x-pub_theme::table.data>
```

## Modal Components

### Basic Modal
Modal semplice per contenuti.

```blade
<x-pub_theme::modal
    name="confirm-delete"
    title="Conferma Eliminazione"
    description="Sei sicuro di voler eliminare questo elemento? Questa azione è irreversibile."
>
    <x-slot name="footer">
        <x-pub_theme::button.group>
            <x-pub_theme::button.secondary wire:click="closeModal">
                Annulla
            </x-pub_theme::button.secondary>
            <x-pub_theme::button.danger wire:click="delete">
                Elimina
            </x-pub_theme::button.danger>
        </x-pub_theme::button.group>
    </x-slot>
</x-pub_theme::modal>
```

### Form Modal
Modal con form integrato.

```blade
<x-pub_theme::modal.form
    name="edit-user"
    title="Modifica Utente"
    wire:submit="save"
>
    <x-pub_theme::form.input
        name="name"
        label="Nome"
        wire:model="form.name"
    />
    
    <x-pub_theme::form.input
        name="email"
        type="email"
        label="Email"
        wire:model="form.email"
    />
    
    <x-slot name="footer">
        <x-pub_theme::button.group>
            <x-pub_theme::button.secondary wire:click="closeModal">
                Annulla
            </x-pub_theme::button.secondary>
            <x-pub_theme::button.primary type="submit">
                Salva
            </x-pub_theme::button.primary>
        </x-pub_theme::button.group>
    </x-slot>
</x-pub_theme::modal.form>
```

## Icon Components

### Heroicons
Icone da Heroicons.

```blade
<x-pub_theme::icon.home />
<x-pub_theme::icon.user class="w-6 h-6" />
<x-pub_theme::icon.arrow-right class="text-blue-600" />
```

### Custom Icons
Icone personalizzate del tema.

```blade
<x-pub_theme::icon.logo class="w-8 h-8" />
<x-pub_theme::icon.flag-italy class="w-4 h-4" />
```

## Utility Components

### Loading Spinner
Indicatore di caricamento.

```blade
<x-pub_theme::loading.spinner />
<x-pub_theme::loading.spinner size="lg" />
<x-pub_theme::loading.spinner color="primary" />
```

### Empty State
Stato vuoto per liste o contenuti.

```blade
<x-pub_theme::empty-state
    icon="heroicon-o-document"
    title="Nessun documento trovato"
    description="Non ci sono documenti da visualizzare al momento."
>
    <x-pub_theme::button.primary>
        Crea Primo Documento
    </x-pub_theme::button.primary>
</x-pub_theme::empty-state>
```

### Badge
Badge per etichette e stati, basato su Bootstrap Italia.

#### Badge Base
Badge generico con varianti di colore:

```blade
<x-pub_theme::badge>Default</x-pub_theme::badge>
<x-pub_theme::badge variant="primary">Primary</x-pub_theme::badge>
<x-pub_theme::badge variant="secondary">Secondary</x-pub_theme::badge>
<x-pub_theme::badge variant="success">Success</x-pub_theme::badge>
<x-pub_theme::badge variant="danger">Danger</x-pub_theme::badge>
<x-pub_theme::badge variant="warning">Warning</x-pub_theme::badge>
<x-pub_theme::badge variant="info">Info</x-pub_theme::badge>
```

**Proprietà:**
- `variant`: Variante di colore (primary, secondary, success, danger, warning, info, light, dark)
- `pill`: Rende il badge arrotondato (boolean)
- `as-link`: Trasforma il badge in un link (boolean)
- `href`: URL del link quando `as-link` è true
- `sr-text`: Testo per screen reader

#### Badge Status (Ticket)
Badge specializzato per visualizzare lo stato dei ticket, integrato con `TicketStatusEnum`:

```blade
<x-pub_theme::badge.status :status="$ticket->status" />
```

Il componente supporta automaticamente tutti gli stati definiti in `Modules\Fixcity\Enums\TicketStatusEnum`:
- `draft`: Bozza (grigio)
- `pending`: In attesa (giallo)
- `assigned`: Assegnato (blu)
- `in_review`: In revisione (blu)
- `in_progress`: In corso (arancione)
- `on_hold`: In pausa (rosso)
- `approved`: Approvato (verde)
- `rejected`: Rifiutato (rosso)
- `resolved`: Risolto (verde)
- `closed`: Chiuso (grigio)
- `reopened`: Riaperto (rosa)
- `open`: Aperto (giallo)

Le traduzioni sono gestite automaticamente tramite `fixcity::ticket.fields.status.options.*`

#### Badge Priority (Ticket)
Badge specializzato per visualizzare la priorità dei ticket, integrato con `TicketPriorityEnum`:

```blade
<x-pub_theme::badge.priority :priority="$ticket->priority" />
```

Il componente supporta automaticamente tutti i livelli di priorità definiti in `Modules\Fixcity\Enums\TicketPriorityEnum`:
- `low`: Bassa (info/blu)
- `medium`: Media (giallo)
- `high`: Alta (arancione)
- `critical`: Critica (rosso)
- `urgent`: Urgente (rosso intenso)

Le traduzioni sono gestite automaticamente tramite `fixcity::ticket.fields.priority.options.*`

#### Esempio Completo
Utilizzo dei badge nel contesto di una card ticket:

```blade
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ $ticket->name }}</h5>
        
        <div class="mb-3">
            <x-pub_theme::badge.status :status="$ticket->status" />
            <x-pub_theme::badge.priority :priority="$ticket->priority" />
        </div>
        
        <p class="card-text">{{ $ticket->content }}</p>
    </div>
</div>
```

## Customizzazione

### Override Componenti
Per personalizzare un componente, creare un file nella directory `resources/views/components/` del tema:

```blade
{{-- resources/views/components/sixteen/button/primary.blade.php --}}
<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
]) }}>
    {{ $slot }}
</button>
```

### Variabili CSS
Definire variabili CSS personalizzate:

```css
:root {
    --sixteen-primary: #0066cc;
    --sixteen-secondary: #666666;
    --sixteen-success: #28a745;
    --sixteen-warning: #ffc107;
    --sixteen-danger: #dc3545;
}
```

## Accessibilità

Tutti i componenti sono progettati per essere accessibili:

- **ARIA labels** per screen reader
- **Focus management** per navigazione da tastiera
- **Contrasto colori** conforme a WCAG 2.1 AA
- **Struttura semantica** corretta
- **Testo alternativo** per icone e immagini

## Best Practices

1. **Utilizzare sempre le traduzioni** per testi visibili
2. **Mantenere coerenza** nell'uso dei componenti
3. **Testare su diversi dispositivi** e browser
4. **Verificare accessibilità** con screen reader
5. **Ottimizzare performance** con lazy loading quando necessario

---

**Versione**: 1.0.0  
**Ultimo aggiornamento**: Gennaio 2025  
**Compatibilità**: Laravel 10+, Filament 3.x, Tailwind CSS 3.x 
