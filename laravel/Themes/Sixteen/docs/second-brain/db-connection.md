---
type: second-brain-initiative
tags: [database, mariadb, sqlite, fix, bmad, second-brain]
status: open
created: 2026-10-17
updated: 2026-10-17
initiative_id: kp-db-connection-fix
title: "Second Brain: DB Connection Fix"
qmd: "second brain: db connection fix sixteen"
---
# Second Brain: DB Connection Fix

- DB: MariaDB/MySQL (mariadb driver), non SQLite.
- .env: DB_CONNECTION=mariadb, DB_HOST=127.0.0.1, DB_PORT=3306, DB_DATABASE=fixcity_data.
- database.php default era `sqlite`; corretto a `mariadb`.
- Errore precedente: SQLite file non trovato su /mnt/nas07/var/www/_bases/base_fixcity_fila5/laravel/database/fixcity_data.sqlite (causato da query su events/blog).
- BMAD doc: laravel/Modules/Fixcity/docs/bmad/db-connection.md
- Filament Blueprint: nessuna modifica ai modelli; solo configurazione connessione DB.
- Verifica /it/tickets: HTTP 200 locale, componente migliorato, DB MariaDB attivo.
- Verifica /it/domande-frequenti (fixcity.eu): HTTP 200 (62388 byte), route fix applicato, nessun errore.
