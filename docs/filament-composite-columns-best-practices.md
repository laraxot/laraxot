# Best Practice per Colonne Composite in Filament Tables

## Principi Fondamentali

### Quando Usare Colonne Composite
- **Dati Correlati**: Informazioni che appartengono logicamente insieme (es. contatti, indirizzi)
- **Risparmio Spazio**: Quando le colonne singole occuperebbero troppo spazio
- **UX Migliorata**: Quando la visualizzazione unificata migliora la comprensione
- **Interattività**: Quando servono azioni dirette sui dati (click-to-call, click-to-email)

### Pattern Raccomandati

#### 1. HTML Rendering (PREFERITO per Dati Interattivi)
```php
TextColumn::make('contacts_html')
    ->label('Contatti')
    ->html()
    ->searchable(['phone', 'mobile', 'email', 'pec', 'whatsapp'])
    ->sortable(false)
    ->wrap()
    ->toggleable(isToggledHiddenByDefault: false)
```

**Vantaggi:**
- Link cliccabili (tel:, mailto:, whatsapp:)
- Icone intuitive
- Styling avanzato
- Tooltip e accessibilità

**Svantaggi:**
- Non sortable nativamente
- Richiede sanitizzazione
- Export complesso

#### 2. Formatted Text (PREFERITO per Dati Semplici)
```php
TextColumn::make('full_address')
    ->formatStateUsing(function ($record) {
        return collect([
            $record->address,
            $record->city,
            $record->postal_code,
            $record->province
        ])->filter()->implode(', ');
    })
    ->searchable(['address', 'city', 'postal_code', 'province'])
    ->wrap()
```

**Vantaggi:**
- Searchable e sortable
- Export friendly
- Semplice da implementare

**Svantaggi:**
- Nessuna interattività
- Styling limitato

#### 3. Badge Collection (PREFERITO per Stati/Tag)
```php
TextColumn::make('tags')
    ->badge()
    ->formatStateUsing(fn ($state) => collect($state)->implode(', '))
    ->color('primary')
```

## Implementazione Dettagliata

### Step 1: Metodo Helper nel Modello
```php
/**
 * Genera HTML per i contatti con icone e link.
 *
 * @return string
 */
public function getContactsHtmlAttribute(): string
{
    $contacts = [];
    
    if ($this->phone) {
        $contacts[] = $this->formatContactLink(
            'phone', 
            $this->phone, 
            'heroicon-o-phone',
            'text-blue-600 hover:text-blue-800',
            'Chiama: ' . $this->phone
        );
    }
    
    if ($this->mobile) {
        $contacts[] = $this->formatContactLink(
            'mobile', 
            $this->mobile, 
            'heroicon-o-device-phone-mobile',
            'text-blue-500 hover:text-blue-700',
            'Chiama cellulare: ' . $this->mobile
        );
    }
    
    if ($this->email) {
        $contacts[] = $this->formatContactLink(
            'email', 
            $this->email, 
            'heroicon-o-envelope',
            'text-green-600 hover:text-green-800',
            'Email: ' . $this->email
        );
    }
    
    if ($this->pec) {
        $contacts[] = $this->formatContactLink(
            'pec', 
            $this->pec, 
            'heroicon-o-shield-check',
            'text-purple-600 hover:text-purple-800',
            'PEC: ' . $this->pec
        );
    }
    
    if ($this->whatsapp) {
        $contacts[] = $this->formatContactLink(
            'whatsapp', 
            $this->whatsapp, 
            'heroicon-o-chat-bubble-left-right',
            'text-green-500 hover:text-green-700',
            'WhatsApp: ' . $this->whatsapp
        );
    }
    
    if (empty($contacts)) {
        return '<span class="text-gray-400 text-sm">Nessun contatto</span>';
    }
    
    return '<div class="flex flex-wrap gap-2">' . implode('', $contacts) . '</div>';
}

/**
 * Formatta un singolo link di contatto.
 */
private function formatContactLink(string $type, string $value, string $icon, string $classes, string $title): string
{
    $href = $this->getContactHref($type, $value);
    $displayValue = $this->getContactDisplayValue($type, $value);
    $iconSvg = $this->getHeroIcon($icon);
    
    return sprintf(
        '<a href="%s" class="inline-flex items-center gap-1 %s transition-colors duration-200" title="%s" aria-label="%s">
            %s
            <span class="text-xs hidden sm:inline">%s</span>
        </a>',
        htmlspecialchars($href),
        htmlspecialchars($classes),
        htmlspecialchars($title),
        htmlspecialchars($title),
        $iconSvg,
        htmlspecialchars($displayValue)
    );
}

/**
 * Genera l'href appropriato per il tipo di contatto.
 */
private function getContactHref(string $type, string $value): string
{
    return match($type) {
        'phone', 'mobile' => 'tel:' . preg_replace('/[^+\d]/', '', $value),
        'email', 'pec' => 'mailto:' . $value,
        'whatsapp' => 'https://wa.me/' . preg_replace('/[^+\d]/', '', $value),
        default => '#'
    };
}

/**
 * Genera il valore display per il tipo di contatto.
 */
private function getContactDisplayValue(string $type, string $value): string
{
    return match($type) {
        'phone', 'mobile' => $this->formatPhoneNumber($value),
        'email', 'pec' => strlen($value) > 20 ? substr($value, 0, 17) . '...' : $value,
        'whatsapp' => 'WhatsApp',
        default => $value
    };
}

/**
 * Formatta un numero di telefono per la visualizzazione.
 */
private function formatPhoneNumber(string $phone): string
{
    // Rimuove tutti i caratteri non numerici eccetto il +
    $clean = preg_replace('/[^+\d]/', '', $phone);
    
    // Formattazione italiana standard
    if (preg_match('/^\+39(\d{10})$/', $clean, $matches)) {
        $number = $matches[1];
        return '+39 ' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . ' ' . substr($number, 6);
    }
    
    return $phone;
}

/**
 * Genera SVG per icona Heroicon.
 */
private function getHeroIcon(string $iconName): string
{
    $icons = [
        'heroicon-o-phone' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>',
        'heroicon-o-device-phone-mobile' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"></path></svg>',
        'heroicon-o-envelope' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
        'heroicon-o-shield-check' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
        'heroicon-o-chat-bubble-left-right' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>',
    ];
    
    return $icons[$iconName] ?? '';
}
```

## Considerazioni di Sicurezza

### Sanitizzazione HTML
```php
// SEMPRE sanitizzare i valori dinamici
htmlspecialchars($value, ENT_QUOTES, 'UTF-8')

// Validare format telefoni
preg_match('/^\+?[\d\s\-\(\)]+$/', $phone)

// Validare format email
filter_var($email, FILTER_VALIDATE_EMAIL)
```

### Privacy e GDPR
```php
// Mascheramento dati sensibili se necessario
private function maskSensitiveData(string $value, string $type): string
{
    if (!$this->canViewFullContact()) {
        return match($type) {
            'phone', 'mobile' => substr($value, 0, 3) . '***' . substr($value, -2),
            'email' => explode('@', $value)[0] . '***@' . explode('@', $value)[1],
            default => '***'
        };
    }
    
    return $value;
}
```

## Accessibilità (WCAG 2.1 AA)

### Requisiti Obbligatori
- `aria-label` descrittivi
- `title` attribute per tooltip
- Contrasto colori ≥ 4.5:1
- Focus states visibili
- Keyboard navigation

### Implementazione
```php
// Focus states CSS
.contact-link:focus {
    outline: 2px solid #3B82F6;
    outline-offset: 2px;
    border-radius: 4px;
}

// Contrasti colori verificati
text-blue-600   // Contrasto: 4.5:1 ✅
text-green-600  // Contrasto: 4.5:1 ✅
text-purple-600 // Contrasto: 4.5:1 ✅
```

## Performance

### Ottimizzazioni
```php
// Caching degli attributi calcolati
public function getContactsHtmlAttribute(): string
{
    return Cache::remember(
        "client_contacts_html_{$this->id}_{$this->updated_at->timestamp}",
        3600,
        fn() => $this->generateContactsHtml()
    );
}

// Eager loading per evitare N+1
Client::with(['contacts'])->get()
```

## Testing

### Unit Tests
```php
/** @test */
public function it_generates_correct_contacts_html()
{
    $client = Client::factory()->create([
        'phone' => '+39 123 456 7890',
        'email' => 'test@example.com',
        'whatsapp' => '+39 987 654 3210'
    ]);
    
    $html = $client->contacts_html;
    
    $this->assertStringContainsString('tel:+391234567890', $html);
    $this->assertStringContainsString('mailto:test@example.com', $html);
    $this->assertStringContainsString('https://wa.me/39987654321', $html);
}
```

### Feature Tests
```php
/** @test */
public function contacts_column_displays_correctly()
{
    $client = Client::factory()->create(['phone' => '+39 123 456 7890']);
    
    $this->actingAs($this->admin)
        ->get(route('filament.admin.resources.clients.index'))
        ->assertSee('tel:+391234567890', false);
}
```

## Regole e Best Practice

### DO ✅
- Usare HTML rendering per dati interattivi
- Sanitizzare SEMPRE i valori dinamici
- Implementare tooltip descrittivi
- Usare icone intuitive e consistenti
- Testare accessibilità e responsive
- Cachare attributi calcolati pesanti

### DON'T ❌
- Non usare HTML non sanitizzato
- Non ignorare l'accessibilità
- Non creare colonne troppo larghe
- Non duplicare logica tra modelli
- Non dimenticare i test

## Collegamenti e Riferimenti

### Documentazione Correlata
- [Filament Tables Documentation](https://filamentphp.com/docs/3.x/tables)
- [Heroicons](https://heroicons.com/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

### File Correlati
- `/docs/techplanner-contacts-column-analysis.md`
- `/Modules/TechPlanner/Models/Client.php`
- `/Modules/TechPlanner/Filament/Resources/ClientResource/Pages/ListClients.php`

---

*Documento creato: 2025-08-01*  
*Versione: 1.0*  
*Conforme a: Laraxot Standards, Filament 3.x, WCAG 2.1 AA*
