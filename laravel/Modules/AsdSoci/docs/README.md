# AsdSoci

Verticale **demo** per gestione soci di associazione sportiva. Il modulo contiene solo pagine Filament: niente
modelli, niente migrazioni, niente tabelle. Serve a mostrare il flusso al cliente,
non a gestirne i dati.

Pannello dedicato su `/asd-soci` con 7 pagine. L'accesso passa da
`AccountFeatures::canAccessPanel()`; i ruoli riconosciuti sono asd_admin e affini.

## Perche' e' un modulo e non una cartella di pagine

Nel gestionale sorgente viveva in `app/Filament/AsdSoci` con il suo PanelProvider in
`app/Providers/Filament`. Qui la regola e' che ogni verticale sia un modulo: cosi' si
attiva e si disattiva da `modules_statuses.json`, porta con se' viste e traduzioni, e
non sporca l'applicazione con dominio che riguarda un solo cliente.

## Documentazione collegata

- [import-status.md](import-status.md)
