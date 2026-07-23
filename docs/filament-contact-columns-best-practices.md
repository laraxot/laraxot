# Filament Contact Columns - Best Practices e Implementazione

## 🎯 OBIETTIVO

Implementare una colonna "Contatti" nella tabella ClientResource che mostri in modo compatto e intuitivo le informazioni di contatto (phone, email, pec, whatsapp) con icone appropriate per una migliore UX.

## 📋 ANALISI REQUISITI

### Informazioni di Contatto da Visualizzare
- **Phone**: Numero di telefono principale
- **Email**: Indirizzo email principale  
- **PEC**: Posta Elettronica Certificata
- **WhatsApp**: Numero WhatsApp

### Requisiti UX
- **Icone intuitive** per identificare rapidamente il tipo di contatto
- **Layout compatto** per non occupare troppo spazio nella tabella
- **Informazioni cliccabili** (tel:, mailto:, whatsapp:)
- **Gestione valori vuoti** con grazia

## 🔍 STUDIO DOCUMENTAZIONE FILAMENT

### TextColumn con Icone
Filament supporta l'aggiunta di icone alle colonne di testo:

```php
TextColumn::make('email')
    ->icon('heroicon-m-envelope')
    ->iconPosition(IconPosition::Before)
```

### Rendering HTML Personalizzato
Per layout complessi, Filament permette HTML custom:

```php
TextColumn::make('contacts')
    ->html()
    ->formatStateUsing(function ($record) {
        return view('components.contact-icons', ['record' => $record])->render();
    })
```

### Layout Columns per Contenuto Strutturato
Filament offre layout columns per organizzare contenuti complessi:

```php
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
```

## 🎨 DESIGN PATTERN RACCOMANDATO

### Approccio 1: Colonna HTML Custom (RACCOMANDATO)
```php
TextColumn::make('contatti')
    ->label('Contatti')
    ->html()
    ->formatStateUsing(function (Client $record): string {
        $contacts = [];
        
        if ($record->phone) {
            $contacts[] = '<a href="tel:' . $record->phone . '" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                <span class="text-xs">' . $record->phone . '</span>
            </a>';
        }
        
        if ($record->email) {
            $contacts[] = '<a href="mailto:' . $record->email . '" class="inline-flex items-center text-green-600 hover:text-green-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
                <span class="text-xs">' . $record->email . '</span>
            </a>';
        }
        
        if ($record->pec) {
            $contacts[] = '<a href="mailto:' . $record->pec . '" class="inline-flex items-center text-purple-600 hover:text-purple-800">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v8a2 2 0 002 2h2.632l1.414 1.414a1 1 0 001.414 0L12.368 14H15a2 2 0 002-2V4a2 2 0 00-2-2H5zm5 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                <span class="text-xs">PEC</span>
            </a>';
        }
        
        if ($record->whatsapp) {
            $contacts[] = '<a href="https://wa.me/' . preg_replace('/[^0-9]/', '', $record->whatsapp) . '" class="inline-flex items-center text-green-500 hover:text-green-700" target="_blank">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                </svg>
                <span class="text-xs">WA</span>
            </a>';
        }
        
        return '<div class="flex flex-wrap gap-2">' . implode('', $contacts) . '</div>';
    })
    ->searchable(['phone', 'email', 'pec', 'whatsapp'])
    ->sortable(false)
```

### Approccio 2: Componente Blade Separato (ALTERNATIVO)
```php
TextColumn::make('contatti')
    ->label('Contatti')
    ->view('components.client-contacts')
```

Con componente Blade:
```blade
{{-- resources/views/components/client-contacts.blade.php --}}
<div class="flex flex-wrap gap-2">
    @if($getRecord()->phone)
        <a href="tel:{{ $getRecord()->phone }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs">
            <x-heroicon-m-phone class="w-4 h-4 mr-1"/>
            {{ $getRecord()->phone }}
        </a>
    @endif
    
    @if($getRecord()->email)
        <a href="mailto:{{ $getRecord()->email }}" 
           class="inline-flex items-center text-green-600 hover:text-green-800 text-xs">
            <x-heroicon-m-envelope class="w-4 h-4 mr-1"/>
            {{ $getRecord()->email }}
        </a>
    @endif
    
    @if($getRecord()->pec)
        <a href="mailto:{{ $getRecord()->pec }}" 
           class="inline-flex items-center text-purple-600 hover:text-purple-800 text-xs">
            <x-heroicon-m-chat-bubble-left-ellipsis class="w-4 h-4 mr-1"/>
            PEC
        </a>
    @endif
    
    @if($getRecord()->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $getRecord()->whatsapp) }}" 
           target="_blank"
           class="inline-flex items-center text-green-500 hover:text-green-700 text-xs">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                <!-- WhatsApp SVG path -->
            </svg>
            WA
        </a>
    @endif
</div>
```

## 🎨 PALETTE COLORI E ICONE

### Schema Colori Raccomandato
- **Phone**: `text-blue-600` (blu per telefono)
- **Email**: `text-green-600` (verde per email)
- **PEC**: `text-purple-600` (viola per PEC - distingue da email normale)
- **WhatsApp**: `text-green-500` (verde WhatsApp brand)

### Icone Heroicons Raccomandate
- **Phone**: `heroicon-m-phone`
- **Email**: `heroicon-m-envelope`
- **PEC**: `heroicon-m-chat-bubble-left-ellipsis` o `heroicon-m-shield-check`
- **WhatsApp**: SVG custom (brand icon)

## 🔧 IMPLEMENTAZIONE TECNICA

### Posizionamento nella Tabella
La colonna "Contatti" dovrebbe essere posizionata dopo le informazioni base del cliente ma prima delle colonne meno importanti:

```php
public function getTableColumns(): array
{
    $columns = [
        // ... colonne esistenti (distance, company_name, etc.)
        
        'contatti' => $this->getContactsColumn(),
        
        // ... altre colonne meno prioritarie
    ];
    
    return $columns;
}

private function getContactsColumn(): TextColumn
{
    return TextColumn::make('contatti')
        ->label('Contatti')
        ->html()
        ->formatStateUsing(function (Client $record): string {
            // Implementazione come sopra
        })
        ->searchable(['phone', 'email', 'pec', 'whatsapp'])
        ->sortable(false);
}
```

### Ottimizzazioni Performance
- **Eager Loading**: Non necessario (campi diretti del modello)
- **Caching**: Non necessario per dati di contatto
- **Lazy Loading**: Considerare per tabelle molto grandi

## 📱 CONSIDERAZIONI RESPONSIVE

### Mobile First
- Su mobile, mostrare solo icone senza testo
- Usare tooltip per identificare il tipo di contatto
- Stack verticale invece di orizzontale

```php
->formatStateUsing(function (Client $record): string {
    // Versione mobile-friendly con solo icone
    $contacts = [];
    
    if ($record->phone) {
        $contacts[] = '<a href="tel:' . $record->phone . '" 
                          class="inline-flex items-center text-blue-600 hover:text-blue-800"
                          title="Telefono: ' . $record->phone . '">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">...</svg>
            <span class="hidden sm:inline ml-1 text-xs">' . $record->phone . '</span>
        </a>';
    }
    
    // ... altri contatti con pattern simile
    
    return '<div class="flex flex-wrap gap-1 sm:gap-2">' . implode('', $contacts) . '</div>';
})
```

## ✅ VANTAGGI DELL'APPROCCIO

1. **UX Migliorata**: Informazioni di contatto facilmente identificabili
2. **Spazio Ottimizzato**: Una sola colonna invece di 4 separate
3. **Interattività**: Link cliccabili per azioni dirette
4. **Scalabilità**: Facile aggiungere nuovi tipi di contatto
5. **Accessibilità**: Title attributes per screen readers

## 🚀 PIANO DI IMPLEMENTAZIONE

### Fase 1: Preparazione
1. Verificare che i campi `phone`, `email`, `pec`, `whatsapp` esistano nel modello Client
2. Aggiornare il metodo `casts()` se necessario per i nuovi campi
3. Testare i dati esistenti per verificare formati

### Fase 2: Implementazione Base
1. Implementare la colonna con approccio HTML custom
2. Testare su dataset esistente
3. Verificare responsive design

### Fase 3: Ottimizzazioni
1. Aggiungere tooltip per mobile
2. Implementare validazione formati (telefono, email)
3. Aggiungere test unitari

### Fase 4: Documentazione
1. Aggiornare documentazione ClientResource
2. Creare esempi per altri moduli
3. Documentare pattern riutilizzabile

## 📚 REGOLE E BEST PRACTICES

### Regole da Seguire
1. **Sempre verificare** l'esistenza del valore prima di renderizzare
2. **Usare colori consistenti** per tipi di contatto simili
3. **Implementare accessibilità** con title attributes
4. **Testare su mobile** per usabilità
5. **Validare formati** per link funzionanti

### Pattern Riutilizzabile
Questo pattern può essere riutilizzato in altri moduli che hanno informazioni di contatto:
- `SupplierResource`
- `PartnerResource`  
- `VendorResource`
- etc.

---

**PRIORITÀ: MEDIA**  
**IMPATTO: MEDIO**  
**EFFORT: BASSO**  

*Ultimo aggiornamento: Agosto 2025*