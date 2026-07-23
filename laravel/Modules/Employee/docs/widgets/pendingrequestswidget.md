# PendingRequestsWidget (LE MIE RICHIESTE IN ATTESA)

## Overview
Widget che mostra lo stato delle richieste personali dell'utente (ferie, permessi, rimborsi, etc.). Quando non ci sono richieste pendenti, mostra un messaggio di conferma che tutto è stato gestito dall'amministratore.

## UI Components
- **Titolo**: "LE MIE RICHIESTE IN ATTESA"
- **Stato vuoto**: 
  - Icona celebrativa (✨ o 🎉)
  - Testo: "Tutte le tue richieste sono state gestite dall'amministratore"
  - Sottotesto: "Non devi preoccuparti di nulla."
- **Stato con richieste**:
  - Lista richieste con badge status
  - Data invio e tipo richiesta
  - Azioni rapide (visualizza, annulla)

## Data Requirements

### Database Tables
- `employee_requests` / `leave_requests`
  - `id`
  - `employee_id` (FK to users)
  - `type` (leave, permit, expense, document, training)
  - `title`
  - `description`
  - `status` (pending, approved, rejected, under_review)
  - `submitted_at`
  - `reviewed_at`
  - `reviewed_by` (FK to users - HR/Manager)
  - `priority` (low, medium, high, urgent)
  - `metadata` (JSON - dati specifici per tipo)

### Request Types
- `leave`: Richieste ferie/permessi
- `expense`: Rimborsi spese
- `document`: Richieste documenti
- `training`: Richieste formazione
- `equipment`: Richieste attrezzature
- `change`: Richieste modifiche contrattuali

### Model: EmployeeRequest
```php
class EmployeeRequest extends Model
{
    public function employee(): BelongsTo
    public function reviewer(): BelongsTo
    public function getTypeLabel(): string // Traduzione tipo
    public function getStatusBadge(): string // Badge HTML con colori
    public function getStatusColor(): string // Colore per UI
    public function scopePending(): Builder
    public function scopeForEmployee(): Builder
    public function canBeCancelled(): bool // Logic cancellazione
}
```

## Widget Implementation

### Class: PendingRequestsWidget extends XotBaseWidget
- **Sort**: 3 (quarto widget)
- **Polling**: 2min (aggiornamento stato richieste)
- **View**: `employee::filament.widgets.pending-requests-widget`

### Methods
- `mount()`: Carica richieste utente
- `getPendingRequests()`: Recupera richieste pending/under_review
- `cancelRequest(int $requestId)`: Annulla richiesta se possibile
- `viewRequest(int $requestId)`: Apre dettaglio richiesta
- `submitNewRequest()`: Quick action nuova richiesta

### Properties
- `array $pendingRequests`: Lista richieste pending
- `int $requestsCount`: Contatore per badge
- `bool $hasRequests`: Flag presenza richieste
- `string $emptyStateMessage`: Messaggio stato vuoto

## Frontend (Blade Template)

### Layout Structure
```blade
<x-filament-widgets::widget>
    <div class="p-6">
        <h3 class="widget-title">LE MIE RICHIESTE IN ATTESA</h3>
        
        @if($hasRequests)
            <!-- Lista richieste -->
            <div class="requests-list">
                @foreach($pendingRequests as $request)
                    <div class="request-item">
                        <div class="request-header">
                            <span class="request-type">{{ $request->type_label }}</span>
                            {!! $request->status_badge !!}
                        </div>
                        
                        <div class="request-content">
                            <h4>{{ $request->title }}</h4>
                            <p class="description">{{ Str::limit($request->description, 100) }}</p>
                            <span class="submitted-date">
                                Inviata {{ $request->submitted_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="request-actions">
                            <button wire:click="viewRequest({{ $request->id }})" class="btn-view">
                                Visualizza
                            </button>
                            @if($request->canBeCancelled())
                                <button wire:click="cancelRequest({{ $request->id }})" class="btn-cancel">
                                    Annulla
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Quick action -->
            <div class="quick-actions">
                <button wire:click="submitNewRequest" class="btn-primary">
                    + Nuova richiesta
                </button>
            </div>
            
        @else
            <!-- Stato vuoto -->
            <div class="empty-state-success">
                <div class="celebration-icon">
                    <x-heroicon-o-check-circle class="w-16 h-16 text-green-500" />
                </div>
                
                <div class="success-message">
                    <h4>Tutte le tue richieste sono state gestite dall'amministratore</h4>
                    <p class="subtitle">Non devi preoccuparti di nulla.</p>
                </div>
                
                <div class="cta-section">
                    <button wire:click="submitNewRequest" class="btn-outline">
                        Invia nuova richiesta
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
```

### Status Badge Colors
- **Pending**: Badge giallo/arancione (⏳)
- **Under Review**: Badge blu (👁️)
- **Approved**: Badge verde (✅)
- **Rejected**: Badge rosso (❌)

## Business Logic

### Request Status Flow
1. **Submitted** → Employee invia richiesta
2. **Pending** → In attesa revisione
3. **Under Review** → HR/Manager sta valutando
4. **Approved/Rejected** → Decisione finale

### Cancellation Rules
```php
public function canBeCancelled(): bool
{
    return $this->status === 'pending' 
        && $this->submitted_at->diffInHours() < 24
        && $this->type !== 'urgent';
}
```

### Priority Logic
- **High/Urgent**: Mostrate per prime
- **Medium**: Ordine cronologico
- **Low**: Ultime nella lista

## Integration Points
- **HR Module**: Gestione approvazioni
- **Notifications**: Aggiornamenti stato
- **Calendar**: Link a eventi correlati
- **Document System**: Allegati richieste

## Actions & Interactions
- `viewRequest(id)`: Modal/page dettaglio richiesta
- `cancelRequest(id)`: Annullamento con conferma
- `submitNewRequest()`: Wizard nuova richiesta
- `trackRequest(id)`: Timeline stato richiesta

## Empty State Variations
- **All Processed**: Messaggio celebrativo attuale
- **No Requests**: "Non hai ancora inviato richieste"
- **Loading**: Skeleton placeholder
- **Error**: Messaggio retry con supporto

## Permissions & Security
- **Employee**: Vede solo le proprie richieste
- **Manager**: Può vedere richieste team (read-only in questo widget)
- **HR**: Accesso completo ma non da questo widget
- **RBAC**: Controllo azioni basato su ruoli

## Performance Considerations
- **Query Optimization**: Include relationships necessarie
- **Caching**: Cache leggera per 1-2 minuti
- **Lazy Loading**: Carica dettagli on-demand
- **Pagination**: Se > 10 richieste

## Notifications Integration
- **Real-time**: Aggiornamento status via broadcasting
- **Email**: Conferme azioni importanti  
- **Push**: Notifiche mobile app

## Testing Strategy
- Unit test business logic cancellazione
- Feature test UI interactions
- Integration test notifiche
- E2E test workflow completo richiesta