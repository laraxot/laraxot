# ModelWithUserContract

## Descrizione
Questa interfaccia estende `ModelContract` aggiungendo funzionalità per la gestione delle relazioni con gli utenti nel sistema Laraxot.

## Struttura
```php
interface ModelWithUserContract extends ModelContract
{
    public function getUserId(): ?int;
    public function setUserId(?int $userId): self;
    public function user(): BelongsTo;
    public function hasUser(): bool;
    public function belongsToUser(int $userId): bool;
}
```

## Funzionalità
1. Gestione delle relazioni con gli utenti
2. Supporto per:
   - Proprietà degli elementi
   - Verifica appartenenza
   - Relazioni utente-elemento
   - Autorizzazioni basate su utente
3. Integrazione con:
   - Sistema di autenticazione
   - Gestione permessi
   - Policy e Gate

## Proprietà
- `user_id`: int|null - ID dell'utente proprietario
- `user`: UserContract|null - Relazione con l'utente proprietario
- `created_at`: Carbon|null - Data e ora di creazione
- `updated_at`: Carbon|null - Data e ora dell'ultima modifica

## Best Practices Implementate
1. Utilizzo di strict types
2. Documentazione PHPDoc completa
3. Supporto per PHPStan livello 9
4. Conforme alle convenzioni Laraxot/<nome progetto>
5. Gestione null-safety

## Schema Database
```sql
ALTER TABLE your_table ADD COLUMN user_id BIGINT UNSIGNED NULL;
ALTER TABLE your_table ADD FOREIGN KEY (user_id) REFERENCES users(id);
```

## Esempio di Utilizzo
```php
class Article extends Model implements ModelWithUserContract
{
    use HasUserTrait;

    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];
}
```

## Note di Sviluppo
- Implementare sempre la relazione `user()`
- Gestire correttamente i casi di soft delete
- Mantenere la consistenza dei dati nelle relazioni
- Documentare eventuali personalizzazioni

## Adozione restaurant_fila5 (2026-09-04)

- 392 occorrenze `use Modules\User\Models\User;` → `use Modules\Xot\Contracts\UserContract;` in app code
- `UserContract` ora type-hint preferito in BelongsTo, PHPDoc, Volt, Notifications
- Classe concreta `User` ancora richiesta per: `User::query()` / `User::create()` / extends / `Gate::policy()`
- Quando serve istanziare la classe a runtime, **sempre** via `XotData::make()->getUserClass()` (mai `User::class` letterale)
- Vedi [User docs: contract adoption](../../../../User/docs/wiki/concepts/user-profile-contract-adoption.md)

## Collegamenti
- [ModelContract](model-contract.md)
- [UserContract](./model-with-user-contract.md)
- [ProfileContract](../concepts/profile-migration-uuid-contract.md)
- [User docs: contract adoption](../../../../User/docs/wiki/concepts/user-profile-contract-adoption.md)
