# Esempi di Utilizzo - Tema Sixteen

## Panoramica

Questa documentazione fornisce esempi pratici di utilizzo di tutti i componenti del tema Sixteen convertiti da Bootstrap Italia a Tailwind CSS. Ogni esempio include il codice completo e le varianti disponibili.

## Alert e Notifiche

### Alert Base con Varianti

```blade
<!-- Alert Informazione -->
<x-pub_theme::alerts.alert variant="info" title="Informazione">
    Questo è un messaggio informativo per l'utente.
</x-pub_theme::alerts.alert>

<!-- Alert Successo -->
<x-pub_theme::alerts.alert variant="success" title="Operazione completata">
    L'operazione è stata completata con successo.
</x-pub_theme::alerts.alert>

<!-- Alert Warning -->
<x-pub_theme::alerts.alert variant="warning" title="Attenzione">
    Attenzione: questa azione non può essere annullata.
</x-pub_theme::alerts.alert>

<!-- Alert Errore -->
<x-pub_theme::alerts.alert variant="danger" title="Errore">
    Si è verificato un errore durante l'operazione.
</x-pub_theme::alerts.alert>
```

### Alert Dismissible

```blade
<x-pub_theme::alerts.alert 
    variant="info" 
    dismissible="true"
    title="Messaggio importante"
>
    Questo alert può essere chiuso dall'utente.
</x-pub_theme::alerts.alert>
```

### Alert con Link

```blade
<x-pub_theme::alerts.alert-link 
    variant="success"
    href="/dettagli-operazione"
    link-text="Visualizza dettagli"
>
    Operazione completata. Clicca per vedere i dettagli.
</x-pub_theme::alerts.alert-link>
```

### Toast Notifications

```blade
<!-- Toast di successo -->
<x-pub_theme::alerts.toast 
    variant="success"
    position="top-right"
    duration="5000"
>
    Messaggio salvato con successo!
</x-pub_theme::alerts.toast>

<!-- Toast di errore -->
<x-pub_theme::alerts.toast 
    variant="danger"
    position="top-center"
    duration="8000"
>
    Errore durante il salvataggio.
</x-pub_theme::alerts.toast>
```

## Pulsanti e Azioni

### Pulsanti Base

```blade
<!-- Pulsante Primario -->
<x-pub_theme::buttons.button variant="primary" size="md">
    Conferma Azione
</x-pub_theme::buttons.button>

<!-- Pulsante Secondario -->
<x-pub_theme::buttons.button variant="secondary" size="md">
    Annulla
</x-pub_theme::buttons.button>

<!-- Pulsante Outline -->
<x-pub_theme::buttons.button variant="outline" size="md">
    Modifica
</x-pub_theme::buttons.button>

<!-- Pulsante Ghost -->
<x-pub_theme::buttons.button variant="ghost" size="md">
    Visualizza
</x-pub_theme::buttons.button>

<!-- Pulsante Pericolo -->
<x-pub_theme::buttons.button variant="danger" size="md">
    Elimina
</x-pub_theme::buttons.button>
```

### Pulsanti con Icone

```blade
<!-- Pulsante con icona a sinistra -->
<x-pub_theme::buttons.button 
    variant="primary" 
    icon="heroicon-o-plus"
    icon-position="left"
>
    Aggiungi Elemento
</x-pub_theme::buttons.button>

<!-- Pulsante con icona a destra -->
<x-pub_theme::buttons.button 
    variant="outline" 
    icon="heroicon-o-arrow-right"
    icon-position="right"
>
    Avanti
</x-pub_theme::buttons.button>
```

### Gruppi di Pulsanti

```blade
<x-pub_theme::buttons.button-group>
    <x-pub_theme::buttons.button-group-item variant="outline">
        <x-heroicon-o-chevron-left class="h-4 w-4" />
        Precedente
    </x-pub_theme::buttons.button-group-item>
    
    <x-pub_theme::buttons.button-group-item variant="primary">
        Pagina 1
    </x-pub_theme::buttons.button-group-item>
    
    <x-pub_theme::buttons.button-group-item variant="outline">
        Successivo
        <x-heroicon-o-chevron-right class="h-4 w-4" />
    </x-pub_theme::buttons.button-group-item>
</x-pub_theme::buttons.button-group>
```

## Card e Contenitori

### Card Base

```blade
<x-pub_theme::cards.card>
    <p class="text-gray-600">
        Questa è una card semplice con contenuto di testo.
    </p>
</x-pub_theme::cards.card>
```

### Card con Header e Footer

```blade
<x-pub_theme::cards.card with-header="true" with-footer="true">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Titolo Card</h3>
            <x-pub_theme::utilities.badge variant="success">Nuovo</x-pub_theme::utilities.badge>
        </div>
    </x-slot>
    
    <div class="space-y-4">
        <p class="text-gray-600">
            Contenuto principale della card con più elementi.
        </p>
        <div class="flex items-center space-x-2">
            <x-heroicon-o-calendar class="h-4 w-4 text-gray-400" />
            <span class="text-sm text-gray-500">15 Dicembre 2024</span>
        </div>
    </div>
    
    <x-slot name="footer">
        <div class="flex justify-end space-x-2">
            <x-pub_theme::buttons.button variant="outline" size="sm">
                Annulla
            </x-pub_theme::buttons.button>
            <x-pub_theme::buttons.button variant="primary" size="sm">
                Salva
            </x-pub_theme::buttons.button>
        </div>
    </x-slot>
</x-pub_theme::cards.card>
```

### Card con Overlay

```blade
<x-pub_theme::cards.card-overlay 
    image-src="/images/hero-bg.jpg"
    overlay-title="Servizi Digitali"
    overlay-subtitle="Soluzioni innovative per la PA"
    overlay-position="bottom"
    overlay-variant="dark"
>
    <div class="space-y-2">
        <p class="text-white opacity-90">
            Scopri le nostre soluzioni digitali per la pubblica amministrazione.
        </p>
        <x-pub_theme::buttons.button variant="primary" size="sm">
            Scopri di più
        </x-pub_theme::buttons.button>
    </div>
</x-pub_theme::cards.card-overlay>
```

## Form e Input

### Form Completo

```blade
<form class="space-y-6">
    <!-- Input Email -->
    <x-pub_theme::forms.input 
        name="email"
        type="email"
        label="Indirizzo email"
        placeholder="nome@esempio.it"
        help-text="Non condivideremo mai la tua email"
        required="true"
        icon-left="heroicon-o-envelope"
    />
    
    <!-- Input Password -->
    <x-pub_theme::forms.input 
        name="password"
        type="password"
        label="Password"
        placeholder="Inserisci la password"
        help-text="Minimo 8 caratteri"
        required="true"
        icon-left="heroicon-o-lock-closed"
        icon-right="heroicon-o-eye"
    />
    
    <!-- Select Paese -->
    <x-pub_theme::forms.select 
        name="country"
        label="Paese di residenza"
        placeholder="Seleziona un paese"
        :options="[
            'it' => 'Italia',
            'fr' => 'Francia',
            'de' => 'Germania',
            'es' => 'Spagna'
        ]"
        required="true"
    />
    
    <!-- Checkbox Termini -->
    <x-pub_theme::forms.checkbox 
        name="terms"
        label="Accetto i termini e condizioni"
        help-text="Devi accettare i termini per continuare"
        required="true"
    />
    
    <!-- Radio Genere -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Genere</label>
        <div class="space-y-2">
            <x-pub_theme::forms.radio 
                name="gender"
                value="male"
                label="Maschio"
            />
            <x-pub_theme::forms.radio 
                name="gender"
                value="female"
                label="Femmina"
            />
            <x-pub_theme::forms.radio 
                name="gender"
                value="other"
                label="Altro"
            />
        </div>
    </div>
    
    <!-- Switch Notifiche -->
    <x-pub_theme::forms.switch 
        name="notifications"
        label="Ricevi notifiche"
        help-text="Riceverai notifiche via email"
    />
    
    <!-- Textarea Descrizione -->
    <x-pub_theme::forms.textarea 
        name="description"
        label="Descrizione"
        placeholder="Inserisci una descrizione dettagliata..."
        rows="4"
        maxlength="500"
        help-text="Massimo 500 caratteri"
    />
    
    <!-- File Upload -->
    <x-pub_theme::forms.file-upload 
        name="documents"
        label="Carica documenti"
        accept=".pdf,.doc,.docx,.jpg,.png"
        multiple="true"
        preview="true"
        max-size="10MB"
        help-text="Formati supportati: PDF, DOC, DOCX, JPG, PNG"
    />
    
    <!-- Pulsanti Form -->
    <div class="flex justify-end space-x-3">
        <x-pub_theme::buttons.button variant="outline">
            Annulla
        </x-pub_theme::buttons.button>
        <x-pub_theme::buttons.button variant="primary">
            Salva
        </x-pub_theme::buttons.button>
    </div>
</form>
```

## Navigazione

### Navbar Completa

```blade
<x-pub_theme::navigation.navbar 
    brand="Logo Azienda"
    brand-href="/"
    variant="light"
    sticky="true"
>
    <div class="flex items-center space-x-8">
        <a href="/home" class="text-gray-700 hover:text-blue-600 transition-colors">
            Home
        </a>
        <a href="/servizi" class="text-gray-700 hover:text-blue-600 transition-colors">
            Servizi
        </a>
        <a href="/chi-siamo" class="text-gray-700 hover:text-blue-600 transition-colors">
            Chi siamo
        </a>
        <a href="/contatti" class="text-gray-700 hover:text-blue-600 transition-colors">
            Contatti
        </a>
    </div>
    
    <x-slot name="mobile">
        <div class="space-y-2">
            <a href="/home" class="block px-3 py-2 text-gray-700 hover:text-blue-600">
                Home
            </a>
            <a href="/servizi" class="block px-3 py-2 text-gray-700 hover:text-blue-600">
                Servizi
            </a>
            <a href="/chi-siamo" class="block px-3 py-2 text-gray-700 hover:text-blue-600">
                Chi siamo
            </a>
            <a href="/contatti" class="block px-3 py-2 text-gray-700 hover:text-blue-600">
                Contatti
            </a>
        </div>
    </x-slot>
</x-pub_theme::navigation.navbar>
```

### Breadcrumb

```blade
<x-pub_theme::navigation.breadcrumb 
    :items="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Servizi', 'href' => '/servizi'],
        ['label' => 'Digitalizzazione', 'href' => '/servizi/digitalizzazione'],
        ['label' => 'Piattaforma Web']
    ]"
/>
```

### Pagination

```blade
<x-pub_theme::navigation.pagination 
    :current-page="5"
    :total-pages="20"
    base-url="/prodotti"
    size="md"
    show-first-last="true"
    show-prev-next="true"
    :max-visible="7"
/>
```

## Layout

### Container e Grid

```blade
<x-pub_theme::layout.container>
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">I Nostri Servizi</h1>
            <p class="mt-4 text-lg text-gray-600">
                Soluzioni innovative per la pubblica amministrazione
            </p>
        </div>
        
        <!-- Grid di Card -->
        <x-pub_theme::layout.grid cols="3" gap="lg" responsive="true">
            <x-pub_theme::cards.card>
                <div class="text-center">
                    <x-heroicon-o-computer-desktop class="h-12 w-12 text-blue-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold mb-2">Digitalizzazione</h3>
                    <p class="text-gray-600">Processi digitali per la PA</p>
                </div>
            </x-pub_theme::cards.card>
            
            <x-pub_theme::cards.card>
                <div class="text-center">
                    <x-heroicon-o-shield-check class="h-12 w-12 text-green-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold mb-2">Sicurezza</h3>
                    <p class="text-gray-600">Soluzioni di sicurezza avanzate</p>
                </div>
            </x-pub_theme::cards.card>
            
            <x-pub_theme::cards.card>
                <div class="text-center">
                    <x-heroicon-o-chart-bar class="h-12 w-12 text-purple-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold mb-2">Analytics</h3>
                    <p class="text-gray-600">Analisi dati e reportistica</p>
                </div>
            </x-pub_theme::cards.card>
        </x-pub_theme::layout.grid>
    </div>
</x-pub_theme::layout.container>
```

## Feedback

### Progress Bar

```blade
<div class="space-y-6">
    <!-- Progress Base -->
    <x-pub_theme::feedback.progress 
        :value="75"
        :max="100"
        variant="primary"
        label="Completamento progetto"
        show-percentage="true"
    />
    
    <!-- Progress Animato -->
    <x-pub_theme::feedback.progress 
        :value="60"
        :max="100"
        variant="success"
        animated="true"
        striped="true"
        label="Caricamento file"
        show-percentage="true"
    />
    
    <!-- Progress con Varianti -->
    <x-pub_theme::feedback.progress 
        :value="90"
        :max="100"
        variant="warning"
        size="lg"
        label="Utilizzo spazio"
        show-percentage="true"
    />
</div>
```

### Spinner

```blade
<div class="space-y-6">
    <!-- Spinner Base -->
    <x-pub_theme::feedback.spinner 
        variant="primary"
        size="md"
        label="Caricamento in corso..."
    />
    
    <!-- Spinner Grande -->
    <x-pub_theme::feedback.spinner 
        variant="success"
        size="lg"
        label="Elaborazione dati..."
    />
    
    <!-- Spinner Senza Label -->
    <x-pub_theme::feedback.spinner 
        variant="primary"
        size="sm"
    />
</div>
```

## Utilità

### Badge

```blade
<div class="space-y-4">
    <!-- Badge Base -->
    <div class="flex items-center space-x-2">
        <x-pub_theme::utilities.badge variant="primary">
            Nuovo
        </x-pub_theme::utilities.badge>
        
        <x-pub_theme::utilities.badge variant="success">
            Completato
        </x-pub_theme::utilities.badge>
        
        <x-pub_theme::utilities.badge variant="warning">
            In attesa
        </x-pub_theme::utilities.badge>
        
        <x-pub_theme::utilities.badge variant="danger">
            Errore
        </x-pub_theme::utilities.badge>
    </div>
    
    <!-- Badge Pill -->
    <div class="flex items-center space-x-2">
        <x-pub_theme::utilities.badge variant="primary" pill="true">
            Pill Badge
        </x-pub_theme::utilities.badge>
        
        <x-pub_theme::utilities.badge variant="success" pill="true">
            Success Pill
        </x-pub_theme::utilities.badge>
    </div>
    
    <!-- Badge Dismissible -->
    <x-pub_theme::utilities.badge 
        variant="info" 
        dismissible="true"
    >
        Badge rimovibile
    </x-pub_theme::utilities.badge>
</div>
```

### Tooltip

```blade
<div class="space-y-4">
    <!-- Tooltip Top -->
    <x-pub_theme::utilities.tooltip 
        content="Informazioni aggiuntive"
        position="top"
        variant="dark"
    >
        <button class="px-4 py-2 bg-blue-600 text-white rounded">
            Hover me (top)
        </button>
    </x-pub_theme::utilities.tooltip>
    
    <!-- Tooltip Bottom -->
    <x-pub_theme::utilities.tooltip 
        content="Descrizione dettagliata"
        position="bottom"
        variant="light"
    >
        <button class="px-4 py-2 bg-green-600 text-white rounded">
            Hover me (bottom)
        </button>
    </x-pub_theme::utilities.tooltip>
    
    <!-- Tooltip Click -->
    <x-pub_theme::utilities.tooltip 
        content="Clicca per vedere il tooltip"
        position="right"
        trigger="click"
    >
        <button class="px-4 py-2 bg-purple-600 text-white rounded">
            Click me
        </button>
    </x-pub_theme::utilities.tooltip>
</div>
```

## Pagina Completa di Esempio

```blade
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esempio Tema Sixteen</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <x-pub_theme::navigation.navbar 
        brand="Tema Sixteen"
        brand-href="/"
        variant="light"
        sticky="true"
    >
        <div class="flex items-center space-x-8">
            <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
            <a href="/componenti" class="text-gray-700 hover:text-blue-600">Componenti</a>
            <a href="/documentazione" class="text-gray-700 hover:text-blue-600">Documentazione</a>
        </div>
    </x-pub_theme::navigation.navbar>
    
    <!-- Main Content -->
    <main class="py-8">
        <x-pub_theme::layout.container>
            <!-- Breadcrumb -->
            <x-pub_theme::navigation.breadcrumb 
                :items="[
                    ['label' => 'Home', 'href' => '/'],
                    ['label' => 'Componenti'],
                    ['label' => 'Esempi']
                ]"
            />
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Esempi di Utilizzo
                </h1>
                <p class="text-lg text-gray-600">
                    Questa pagina mostra esempi pratici di utilizzo dei componenti del tema Sixteen.
                </p>
            </div>
            
            <!-- Alert Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Alert e Notifiche</h2>
                <div class="space-y-4">
                    <x-pub_theme::alerts.alert variant="info" title="Informazione">
                        Questo è un esempio di alert informativo.
                    </x-pub_theme::alerts.alert>
                    
                    <x-pub_theme::alerts.alert variant="success" title="Successo">
                        Operazione completata con successo!
                    </x-pub_theme::alerts.alert>
                </div>
            </section>
            
            <!-- Form Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Form di Esempio</h2>
                <x-pub_theme::cards.card>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-pub_theme::forms.input 
                                name="nome"
                                label="Nome"
                                placeholder="Inserisci il tuo nome"
                                required="true"
                            />
                            
                            <x-pub_theme::forms.input 
                                name="cognome"
                                label="Cognome"
                                placeholder="Inserisci il tuo cognome"
                                required="true"
                            />
                        </div>
                        
                        <x-pub_theme::forms.input 
                            name="email"
                            type="email"
                            label="Email"
                            placeholder="nome@esempio.it"
                            required="true"
                            icon-left="heroicon-o-envelope"
                        />
                        
                        <x-pub_theme::forms.select 
                            name="categoria"
                            label="Categoria"
                            placeholder="Seleziona una categoria"
                            :options="[
                                'web' => 'Sviluppo Web',
                                'mobile' => 'Sviluppo Mobile',
                                'design' => 'Design',
                                'marketing' => 'Marketing'
                            ]"
                        />
                        
                        <x-pub_theme::forms.textarea 
                            name="messaggio"
                            label="Messaggio"
                            placeholder="Inserisci il tuo messaggio..."
                            rows="4"
                        />
                        
                        <x-pub_theme::forms.checkbox 
                            name="newsletter"
                            label="Iscriviti alla newsletter"
                            help-text="Riceverai aggiornamenti sui nostri servizi"
                        />
                        
                        <div class="flex justify-end space-x-3">
                            <x-pub_theme::buttons.button variant="outline">
                                Annulla
                            </x-pub_theme::buttons.button>
                            <x-pub_theme::buttons.button variant="primary">
                                Invia
                            </x-pub_theme::buttons.button>
                        </div>
                    </form>
                </x-pub_theme::cards.card>
            </section>
            
            <!-- Progress Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Barre di Progresso</h2>
                <div class="space-y-4">
                    <x-pub_theme::feedback.progress 
                        :value="75"
                        :max="100"
                        variant="primary"
                        label="Completamento progetto"
                        show-percentage="true"
                    />
                    
                    <x-pub_theme::feedback.progress 
                        :value="45"
                        :max="100"
                        variant="success"
                        animated="true"
                        label="Caricamento file"
                        show-percentage="true"
                    />
                </div>
            </section>
            
            <!-- Pagination -->
            <section class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-6">Paginazione</h2>
                <x-pub_theme::navigation.pagination 
                    :current-page="3"
                    :total-pages="15"
                    base-url="/esempi"
                    size="md"
                />
            </section>
        </x-pub_theme::layout.container>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <x-pub_theme::layout.container>
            <div class="text-center">
                <p>&copy; 2024 Tema Sixteen. Tutti i diritti riservati.</p>
            </div>
        </x-pub_theme::layout.container>
    </footer>
</body>
</html>
```

## Best Practices

### 1. Accessibilità
- Utilizzare sempre label appropriate per i form
- Includere attributi ARIA quando necessario
- Testare la navigazione da tastiera

### 2. Responsive Design
- Utilizzare le classi responsive di Tailwind
- Testare su diversi dispositivi
- Considerare l'usabilità mobile

### 3. Performance
- Utilizzare componenti leggeri
- Ottimizzare le immagini
- Minimizzare il JavaScript

### 4. Consistenza
- Utilizzare le stesse varianti in tutta l'applicazione
- Mantenere coerenza nei colori e spaziature
- Seguire le convenzioni di naming

---

**Ultimo aggiornamento**: Dicembre 2024
**Versione**: 1.0
**Compatibilità**: Tailwind CSS 3.x, Alpine.js 3.x, Laravel 10.x 
