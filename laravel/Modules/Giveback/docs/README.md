# Giveback

Verticale **demo** per piattaforma giveback (cashback solidale). Il modulo contiene solo pagine Filament: niente
modelli, niente migrazioni, niente tabelle. Serve a mostrare il flusso al cliente,
non a gestirne i dati.

Pannello dedicato su `/giveback-platform` con 9 pagine. L'accesso passa da
`AccountFeatures::canAccessPanel()`; i ruoli riconosciuti sono giveback_admin, giveback_merchant, giveback_support.

## Perche' e' un modulo e non una cartella di pagine

Nel gestionale sorgente viveva in `app/Filament/GivebackPlatform` con il suo PanelProvider in
`app/Providers/Filament`. Qui la regola e' che ogni verticale sia un modulo: cosi' si
attiva e si disattiva da `modules_statuses.json`, porta con se' viste e traduzioni, e
non sporca l'applicazione con dominio che riguarda un solo cliente.

## Documentazione collegata

- [import-status.md](import-status.md)
