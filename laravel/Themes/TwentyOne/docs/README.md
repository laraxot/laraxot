---
title: "TwentyOne Module Documentation"
type: documentation
tags: [module, documentation]
created: 2026-06-05
updated: 2026-06-05
---

# TwentyOne Theme Documentation

## Introduzione
TwentyOne è un tema moderno e performante per Laravel, basato su Tailwind CSS e Vite.
Questo tema è progettato per offrire una soluzione robusta e facilmente personalizzabile
per le applicazioni Laravel e per integrarsi con l'ecosistema moduli del progetto.

## Caratteristiche Principali
- 🎨 Design moderno con Tailwind CSS
- ⚡ Build system ottimizzato con Vite
- 📱 Responsive design
- 🔧 Facilmente personalizzabile
- 🚀 Performance ottimizzate

## Struttura del Tema
```
TwentyOne/
├── app/              # Componenti PHP del tema
├── docs/             # Documentazione
├── public/           # Assets pubblici
├── resources/        # Risorse frontend
├── scripts/          # Script di utilità
└── Main_files/       # File principali del tema
```

## Requisiti
- PHP >= 8.3
- Laravel 11.x (12-ready)
- Node.js >= 18.0
- NPM >= 9.0
- Compatibile con Filament 4.x (dove applicabile nelle integrazioni admin)

## Installazione
1. Clona il repository nella cartella `laravel/Themes/TwentyOne`
2. Esegui `composer install` per installare le dipendenze PHP
3. Esegui `npm install` per installare le dipendenze JavaScript
4. Esegui `npm run build` per compilare gli asset del tema in `public/`
5. Esegui `npm run copy` per pubblicare manifest e asset in `public_html/themes/TwentyOne`

## Configurazione
Il tema può essere configurato attraverso i seguenti file:
- `tailwind.config.js` - Configurazione Tailwind CSS
- `vite.config.js` - Configurazione Vite
- `theme.json` - Configurazioni specifiche del tema

## Sviluppo
Per iniziare lo sviluppo:
1. Esegui `npm run dev` per avviare il server di sviluppo Vite
2. Modifica i file nella cartella `resources`
3. Le modifiche verranno automaticamente compilate

## Build per Produzione
Per creare una build ottimizzata per la produzione:
```bash
npm run build
npm run copy
```

Nel progetto corrente il solo `npm run build` non basta: Laravel legge gli asset del tema dal path runtime `public_html/themes/TwentyOne`, quindi dopo ogni build serve la copia verso quella destinazione.

## Personalizzazione
Il tema può essere personalizzato in diversi modi:
1. Modificando i file nella cartella `resources`
2. Sovrascrivendo i componenti nella cartella `app`
3. Aggiungendo nuovi stili in `resources/css`

## Troubleshooting
Per problemi comuni, consulta la documentazione nella cartella `docs`:
- [Vite Manifest Error](vite_manifest_error.md)
- [Vite Error](vite-error.md)
- [Publishing](publishing.md)
- [Section Template Contract](section-template-contract.md)

## Documentazione correlata
- `laravel/Themes/TwentyOne/docs/` - Documentazione del tema
- `project_docs/` - Documentazione di progetto (architettura, sviluppo)
- `project_docs/roadmaps/` - Roadmap master e stato avanzamento

## LLM Wiki Workflow

- Canonical wiki layer: [../../../../docs/wiki/README.md](../../../../docs/wiki/README.md)
- Compiled index: [../../../../docs/wiki/index.md](../../../../docs/wiki/index.md)

## Contribuire
Le contribuzioni sono benvenute! Per contribuire:
1. Crea un fork del repository
2. Crea un branch per la tua feature
3. Invia una pull request

## Licenza
Questo tema è rilasciato sotto la licenza MIT. Vedi il file [LICENSE](../LICENSE) per maggiori dettagli. 


## Standard Rules & Workflow

- [[BMAD Method](../../../../docs/wiki/concepts/bmad-method.md)]
- [[Context Engineering](../../../../docs/wiki/concepts/context-engineering.md)]
- [[LLM Wiki Governance](../../../../docs/wiki/concepts/llm-wiki-governance.md)]

## Documentation

- [On-Demand Pattern](./ON-DEMAND-PATTERN.md) — Pattern per caricamento efficiente
- [QMD Setup](./QMD-SETUP.md) — Configurazione ricerca locale
- [Performance](./PERFORMANCE-OPTIMIZATION.md) — Metriche e best practice
- [Project Structure](./PROJECT-STRUCTURE.md) — Directory layout
