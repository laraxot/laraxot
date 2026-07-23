# Filament ViewColumn vs TextColumn - Pattern e Best Practices

## Panoramica

Questa documentazione definisce quando utilizzare `ViewColumn` invece di `TextColumn` per le colonne delle tabelle Filament, con focus sul caso d'uso della colonna "Contatti" nel ClientResource.

## Quando Usare ViewColumn

### ✅ **ViewColumn è la scelta giusta quando:**

1. **Layout Complessi**: Più di 2-3 elementi di informazione da visualizzare
2. **HTML Strutturato**: Necessità di markup complesso con nested elements
3. **Logica di Presentazione**: Condizioni complesse per la visualizzazione
4. **Riutilizzabilità**: Template che può essere riutilizzato in altre risorse
5. **Manutenibilità**: Separazione netta tra controller e presentazione

### ❌ **TextColumn è sufficiente quando:**

1. **Dati Semplici**: Singolo valore o concatenazione semplice
2. **HTML Minimale**: Pochi tag HTML inline
3. **Logica Semplice**: Condizioni if/else basilari
4. **Uso Singolo**: Colonna specifica per una sola risorsa

## Implementazione ViewColumn

### Controller Setup

```php
// Modules/TechPlanner/app/Filament/Resources/ClientResource/Pages/ListClients.php

'contacts' => ViewColumn::make('contacts')
    ->view('techplanner::filament.tables.columns.contacts')
    ->searchable(['phone', 'email', 'pec', 'whatsapp', 'mobile', 'fax'])
    ->sortable(false)
    ->label('Contatti'),
```

### Blade Template Structure

```blade
{{-- Modules/TechPlanner/resources/views/filament/tables/columns/contacts.blade.php --}}

@php
    $record = $getRecord();
    $contacts = [];
@endphp

<div class="flex flex-wrap gap-1">
    @if($record->phone)
        <a href="tel:{{ $record->phone }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 mr-1 mb-1" 
           title="Telefono: {{ $record->phone }}">
            <x-heroicon-o-phone class="w-4 h-4 mr-1" />
            <span class="text-xs hidden sm:inline">{{ $record->phone }}</span>
        </a>
    @endif
    
    @if($record->email)
        <a href="mailto:{{ $record->email }}" 
           class="inline-flex items-center text-green-600 hover:text-green-800 mr-1 mb-1" 
           title="Email: {{ $record->email }}">
            <x-heroicon-o-envelope class="w-4 h-4 mr-1" />
            <span class="text-xs hidden sm:inline">{{ $record->email }}</span>
        </a>
    @endif
    
    {{-- Altri contatti... --}}
</div>

@if(empty(array_filter([$record->phone, $record->email, $record->pec, $record->whatsapp])))
    <span class="text-gray-400 text-xs">Nessun contatto</span>
@endif
```

## Vantaggi del Pattern ViewColumn

### 1. **Separazione delle Responsabilità**
- Controller: configurazione colonna e searchable fields
- Blade: logica di presentazione e styling
- Modello: dati e relazioni

### 2. **Riutilizzabilità**
```php
// Utilizzabile in altre risorse
'contacts' => ViewColumn::make('contacts')
    ->view('techplanner::filament.tables.columns.contacts'), // Stesso template!
```

### 3. **Manutenibilità**
- Modifiche al layout: solo template Blade
- Modifiche ai dati: solo controller
- Testing separato per logica e presentazione

### 4. **Performance**
- Caching automatico delle view Blade
- Compilazione template ottimizzata
- Riutilizzo template compilati

### 5. **Flessibilità**
- Supporto completo componenti Blade
- Accesso a helper Laravel
- Logica condizionale complessa

## Schema Colori e Icone Standard

```php
// Schema colori semantico per contatti
$contactTypes = [
    'phone' => ['color' => 'text-blue-600', 'icon' => 'heroicon-o-phone'],
    'mobile' => ['color' => 'text-purple-600', 'icon' => 'heroicon-o-device-phone-mobile'],
    'email' => ['color' => 'text-green-600', 'icon' => 'heroicon-o-envelope'],
    'pec' => ['color' => 'text-orange-600', 'icon' => 'heroicon-o-shield-check'],
    'whatsapp' => ['color' => 'text-green-500', 'icon' => 'heroicon-o-chat-bubble-left-right'],
    'fax' => ['color' => 'text-gray-500', 'icon' => 'heroicon-o-printer'],
];
```

## Best Practices

### ✅ **DO**

1. **Usare $getRecord()** per accedere al modello
2. **Gestire valori vuoti** con grazia
3. **Implementare responsive design** (hidden sm:inline)
4. **Aggiungere title attributes** per accessibilità
5. **Usare link cliccabili** (tel:, mailto:, whatsapp:)
6. **Mantenere coerenza** nei colori e icone

### ❌ **DON'T**

1. **Non usare logica complessa** nel template (spostare nel controller)
2. **Non hardcodare valori** (usare configurazioni)
3. **Non dimenticare searchable** nel controller
4. **Non usare inline styles** (solo classi Tailwind)
5. **Non duplicare codice** tra template simili

## Caso d'Uso: Colonna Contatti ClientResource

### Requisiti
- Visualizzare: phone, email, PEC, WhatsApp, mobile, fax
- Link cliccabili per ogni tipo di contatto
- Icone intuitive per identificazione rapida
- Layout responsive (mobile: solo icone, desktop: icone + testo)
- Gestione valori vuoti

### Implementazione
- **Controller**: ViewColumn con searchable su tutti i campi
- **Template**: Blade con loop sui tipi di contatto
- **Styling**: Tailwind CSS con schema colori semantico
- **Accessibilità**: Title attributes e ARIA labels

## Pattern Riutilizzabile

Questo pattern può essere applicato a:
- **SupplierResource**: Contatti fornitori
- **PartnerResource**: Contatti partner
- **VendorResource**: Contatti venditori
- **UserResource**: Contatti utenti
- **Qualsiasi risorsa** con informazioni di contatto multiple

## Testing

```php
// Test per template ViewColumn
public function test_contacts_column_displays_correctly()
{
    $client = Client::factory()->create([
        'phone' => '123456789',
        'email' => 'test@example.com',
    ]);
    
    $html = view('techplanner::filament.tables.columns.contacts', [
        'getRecord' => fn() => $client
    ])->render();
    
    $this->assertStringContains('tel:123456789', $html);
    $this->assertStringContains('mailto:test@example.com', $html);
}
```

## Conclusioni

Il pattern ViewColumn per colonne complesse offre:
- **Migliore organizzazione** del codice
- **Maggiore flessibilità** nella presentazione
- **Facilità di manutenzione** e testing
- **Riutilizzabilità** cross-resource
- **Performance ottimizzate** con caching Blade

Utilizzare questo pattern per tutte le colonne che richiedono layout complessi o logica di presentazione articolata.

---

**Documentazione creata**: 2025-08-01  
**Caso d'uso**: TechPlanner ClientResource contacts column  
**Pattern**: ViewColumn con Blade template personalizzato
