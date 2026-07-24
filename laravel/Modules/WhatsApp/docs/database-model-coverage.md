# WhatsApp — Copertura Model / Migration / Seeder / Factory

Stato: ogni modello concreto ha migration, factory e seeder dedicati.

## Modelli concreti (`app/Models/`)

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| `WhatsAppContactIdentity` | `whatsapp_contact_identities` | OK | `WhatsAppContactIdentityFactory` | `WhatsAppContactIdentitySeeder` (nuovo) |
| `WhatsAppConversation` | `whatsapp_conversations` | OK (+ `add_proposal_tracking`) | `WhatsAppConversationFactory` | `WhatsAppConversationSeeder` (nuovo) |
| `WhatsAppMessage` | `whatsapp_messages` | OK | `WhatsAppMessageFactory` | `WhatsAppMessageSeeder` (nuovo) |

Le migration sono 4: 3 create-table + `2026_07_10_120000_add_proposal_tracking_to_whatsapp_conversations`
(ALTER su `whatsapp_conversations`, non introduce una nuova tabella/modello).

`WhatsAppDatabaseSeeder` ora orchestra i tre seeder via `$this->call([...])`, in ordine di dipendenza
(identity → conversation → message). `WhatsAppMessageSeeder` aggancia i messaggi alle conversation
esistenti (fallback: genera 3 conversation se la tabella è vuota).

## Skip motivati

| Elemento | Tipo | Motivo skip |
|---|---|---|
| `BaseModel` | classe astratta (`extends XotBaseModel`) | non istanziabile, nessuna tabella propria |
| migration `add_proposal_tracking...` | ALTER TABLE | modifica colonne su tabella esistente, nessun modello nuovo |

## Note

- Seeder idempotenti: guardia `->exists()` prima di generare dati (pattern `Modules/WorkOrder`).
- Namespace: `Modules\WhatsApp\Database\Seeders`, `Modules\WhatsApp\Database\Factories`.
