# Case-Insensitive File Conflicts

Su Linux i file `Foo.md` e `foo.md` coesistono; su Windows/macOS collidono. Una sola variante per coppia.

File duplicati rilevati nel modulo `UI`:
## Risolto (2026-08-18)
Uniformare ciascuna coppia scegliendo un'unica versione (in genere `README.md`, `CONTRIBUTING.md`, ecc.) e rimuovere i duplicati.
Su Linux i file `Foo.md` e `foo.md` coesistono; su Windows/macOS collidono. Una sola variante per coppia.
## Risolto (2026-08-18)

| Coppia | Tenuto | Perché |
|---|---|---|
| `.github/CONTRIBUTING.md` / `contributing.md` | `CONTRIBUTING.md` | GitHub legge quel nome; il minuscolo era lo stesso testo con marker |
| `.github/SECURITY.md` / `security.md` | `SECURITY.md` | identici; GitHub |
| `docs/filament/ListRecords.md` / `listrecords.md` | `listrecords.md` | docs kebab-case; contenuto canonico ripulito dai marker |
| `docs/CHANGELOG.md` / `changelog.md` | `changelog.md` | docs minuscolo; semantic-release |
| `docs/wiki/SCHEMA.md` / `schema.md` | `schema.md` | SCHEMA era stub/marker verso schema.md |
| Altri `ON-DEMAND-PATTERN.md`, `AGENTS.md`, `00-INDEX.md`, … | variante minuscola | identici o superset; nessun contenuto unico nel maiuscolo |

`docs/README.md` resta l'eccezione maiuscola prevista.

## Ancora aperti

Nessun marker di conflitto a inizio riga nel repo (`git grep`). I Feature Pest Filament del modulo User possono contendere il database di testing con altri suite in parallelo.
