---
title: "Audit parità Models/Migrations/Seeders/Factories — 22 moduli"
type: handoff
tags: [chat, handoff, audit, models, migrations, seeders, factories]
created: 2026-07-16
updated: 2026-07-16
qmd: "audit parity models migrations seeders factories modules gap techplanner"
related:
  - ./INDEX.md
---

# Audit parità 1:1 Models/Migrations/Seeders/Factories

Sessione avviata da `start.txt` con due obiettivi: (1) aggiornare `docs/` di moduli/temi, (2) controllo
parità 1:1 Models↔Migrations↔Seeders↔Factories sui 22 moduli. Data la dimensione del task (22 moduli +
7 temi), questa sessione ha coperto **solo il punto 2, in sola lettura** (nessuna migration/seeder/factory
creata) — inventario grezzo via `ls` per modulo, nessuna cross-verifica campo-per-campo dentro le migration.
Il punto 1 (docs/ per modulo) resta non iniziato.

## Metodo

`ls app/Models`, `database/migrations`, `database/seeders`, `database/factories` per ciascun modulo.
Nota: molti moduli hanno file spuri (`.bak`, `.to_*`, `.wip`, `.no`, `.old*`, `.LARAXOT_CORRECT`, cartelle
`_archive_redundant`) che vanno esclusi dal conteggio modelli reali — non ho normalizzato tutto, serve
un secondo passaggio più preciso per contare esattamente "modelli concreti" vs BaseModel/Traits/Policies/Concerns.

## Gap grezzi individuati (da verificare/risolvere)

- **AI**: nessun Model/Migration/Factory, solo `AIDatabaseSeeder`. Verificare se il modulo è ancora scaffold vuoto.
- **Seo**: nessun Model concreto (solo `Scopes/`), nessuna migration/factory, solo `SeoDatabaseSeeder`. Verificare se scaffold vuoto o modulo dismesso.
- **TechPlanner**: 15 modelli concreti (Appointment, Client, Device, DeviceVerification, Event, LegalOffice,
  LegalRepresentative, Location, Machine, MedicalDirector, Participant, PhoneCall, Profile, Worker + BaseModel) ma
  **solo 1 seeder** (`PhoneCallSeeder`) — mancano seeder per Appointment, Client, Device, DeviceVerification, Event,
  LegalOffice, LegalRepresentative, Location, Machine, MedicalDirector, Participant, Profile, Worker (13 seeder mancanti).
  Factories quasi complete ma `EventFactoryFactory.php`, `LocationFactoryFactory.php`, `ParticipantFactoryFactory.php`
  hanno naming doppio "Factory" — probabile refuso da rinominare (`EventFactory`, `LocationFactory`, `ParticipantFactory`).
- **Blog**: file spuri in migrations (`.to_article`, `.to_article_model`, `.to_cms`, `_bak`) da ripulire/decidere
  se ancora pending; `Article.bak` e `BaseModel.php.backup-*` in Models da rimuovere se sync già completato.
- **Cms**: sotto-cartelle spurie dentro `database/migrations` (`Migrations/`), `database/seeders/Seeders/`,
  `database/factories/Factories/` — probabile duplicazione strutturale da investigare (namespace sbagliato?).
- **Gdpr**: migrations duplicate per `consents`/`treatments` (3 varianti ciascuna con date diverse) + cartella
  `_archive_redundant` — da consolidare, ma **non toccare** migration già applicate (forward-only).
- **Geo**: file `.bak1`, `,bak` (virgola, probabile typo) in migrations da ripulire.
- **Notify**: `NotificationLog.php.old3/.old4/.up`, `NotificationTemplateVersion.php.up` e cartelle `_archive_redundant`,
  `_bak` in migrations — cleanup necessario.
- **Rating**: 3 migration `create_ratings_table` in date diverse (2023, 2026_03, 2026_06) — verificare quale è quella
  reale applicata, le altre potrebbero essere dead code.
- **Tenant**: **nessuna migration nella cartella modulo** (`database/migrations` vuota) pur avendo 7+ modelli concreti
  (Domain, TenantDomain, Tenant, TenantSetting, TenantSubscription, DatabaseConfig, TestSushiModel) e relativi
  seeder/factory — le tabelle sono probabilmente create altrove (User module?) ma va verificato, potenziale gap serio.
- **User**: modulo enorme con molte migration duplicate per stessa tabella (roles, permissions, team_user, profiles,
  users, oauth_clients, model_has_roles) su date diverse — tipico di sync multi-repo, da consolidare con cautela
  (forward-only, mai toccare migration già applicate).
- **Employee**: 2 migration quasi identiche per `work_hours` (121400/121401) — verificare duplicato.

## Moduli con parità apparentemente OK (da confermare con verifica più fine)

Activity, Comment, Job, Lang, Media, UI sembrano avere corrispondenza ragionevole modelli↔migrations↔seeders↔factories
(nessun gap grosso individuato al primo passaggio).

## Non fatto in questa sessione

- Nessun file creato/modificato (migrations, seeders, factories, docs/ di modulo).
- Nessun controllo lock (`bashscripts/lock/check.sh`) eseguito, perché non è stata tentata alcuna scrittura.
- Temi (7) non auditati.
- docs/ dei moduli non toccati.

## Prossimi passi consigliati

1. Normalizzare l'inventario (escludere file spuri) per contare esattamente i gap reali.
2. Priorità: **TechPlanner** (13 seeder mancanti, coerente col progetto host) e **Tenant** (migrations assenti).
3. Chiarire i duplicati Cms/Gdpr/Rating/User prima di aggiungere nuove migration, per non peggiorare l'inconsistenza.
4. Riprendere il punto 1 (aggiornamento docs/ per modulo/tema) in una sessione dedicata — non iniziato qui.
