# PHPStan full-tree: bootstrap ripetutamente bloccato da import stripping — 2026-09-20

Richiesta: `phpstan analyse Modules` full-tree, sistemare tutte le segnalazioni, coordinandosi con altri agenti AI concorrenti attivi sullo stesso repo.

## Problema

9 tentativi consecutivi di full-tree run fino a questo punto, ognuno fallito nel bootstrap (o in fatal error subito dopo) su un file diverso di `Modules/User`, sempre stessa firma: blocco `use ...;` rimosso mentre il corpo classe continua a referenziare i nomi non qualificati. Vedi story `Modules/User/docs/stories/8.4.phpstan-bootstrap-import-stripping-corruption.story.md` per dettaglio completo file-per-file e metodo di riparazione.

Memoria tecnica: `pattern_import_stripping_corruption_vs_legit_cleanup.md` — include il metodo per distinguere corruzione reale da pulizia import-inutilizzati legittima (falso positivo trovato su `Modules/Cms` PageContentResource/SectionResource, lasciati intatti).

## File riparati questa sessione (Modules/User)

- `Filament/Resources/SocialProviderResource/Pages/EditSocialProvider.php`
- `Filament/Resources/UserResource/Pages/BaseListUsers.php`
- `Filament/Resources/UserResource/Pages/BaseEditUser.php`
- `Filament/Resources/UserResource/Pages/ViewUser.php`
- `Listeners/LogoutListener.php`
- `Http/Livewire/Auth/Register.php`
- `Filament/Resources/TeamResource/RelationManagers/UsersRelationManager.php`

Auto-riparati da altri agenti concorrenti prima del mio intervento (verificati puliti, non toccati):
- `Filament/Resources/TeamResource/Pages/EditTeam.php`
- `Filament/Resources/TenantResource/Pages/EditTenant.php`
- `Models/Traits/HasTeams.php`
- `Contracts/HasAuthentications.php`

## Stato

`Modules/User` resta il modulo piu' conteso (altri agenti attivi in scrittura continua). Continuo a rilanciare il run full-tree in background finche' il bootstrap non supera la fase di discovery; ogni fallimento viene diagnosticato e, se il file e' fuori dallo scope attivo di altri, riparato da HEAD con verifica diff mirata (mai `git checkout --`).

Nessun commit ancora effettuato (in attesa di un run pulito prima di consolidare + per non collidere con gli agenti concorrenti che stanno ancora scrivendo su questi stessi file).
