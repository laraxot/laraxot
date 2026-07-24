# Email — Copertura Model / Migration / Seeder / Factory

Stato: ogni modello concreto ha migration, factory e seeder dedicati.

## Modelli concreti (`app/Models/`)

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| `EmailContactIdentity` | `email_contact_identities` | OK | `EmailContactIdentityFactory` | `EmailContactIdentitySeeder` (nuovo) |
| `EmailConversation` | `email_conversations` | OK | `EmailConversationFactory` | `EmailConversationSeeder` (nuovo) |
| `EmailMessage` | `email_messages` | OK | `EmailMessageFactory` | `EmailMessageSeeder` (nuovo) |

`EmailDatabaseSeeder` ora orchestra i tre seeder via `$this->call([...])`, in ordine di dipendenza
(identity → conversation → message). `EmailMessageSeeder` aggancia i messaggi alle conversation
esistenti (fallback: genera 3 conversation se la tabella è vuota).

## Skip motivati

| Elemento | Tipo | Motivo skip |
|---|---|---|
| `BaseModel` | classe astratta (`extends XotBaseModel`) | non istanziabile, nessuna tabella propria |

## Note

- Seeder idempotenti: guardia `->exists()` prima di generare dati (pattern `Modules/WorkOrder`).
- Namespace: `Modules\Email\Database\Seeders`, `Modules\Email\Database\Factories`.
