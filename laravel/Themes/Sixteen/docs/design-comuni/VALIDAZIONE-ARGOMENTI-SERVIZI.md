# Validazione Strutturale - Argomenti e Servizi

**Data**: 2026-04-07

---

## Argomenti

### Stato Attuale
```
main
└── div.container (1 solo container)
    └── sezioni multiple dentro
```

### Stato Atteso (Reference)
```
main
├── div#main-container.container
├── div.container
├── div.container.py-5
├── div#argomento.container.py-5
└── div.bg-primary
```

### Differenze Confermate
- ❌ Manca `id="main-container"`
- ❌ Solo 1 container invece di 5+
- ❌ Sezioni non separate

---

## Servizi

### Stato Attuale
```
main
├── div.row.justify-content-center (senza container!)
├── div.row.justify-content-center
├── div.row.justify-content-center
└── section#servizi
```

### Stato Atteso (Reference)
```
main
├── div#main-container.container
│   └── div.row.justify-content-center
├── div.container
│   └── div.row.justify-content-center
└── div.bg-grey-card
    └── div.container
```

### Differenze Confermate
- ❌ Prima riga è `div.row` senza container wrapper
- ❌ Manca `id="main-container"`
- ❌ Struttura completamente diversa

---

## Fix Richiesti

### Argomenti
1. Aggiungere `id="main-container"` al primo container
2. Separare ogni sezione nel proprio `div.container`
3. Aggiungere classi `title-xxlarge mb-4` ai titoli
4. Usare `row g-4` per le griglie

### Servizi
1. Wrappare la prima `div.row` in `div#main-container.container`
2. Separare sezioni in container distinti
3. Aggiungere sezione contatti con `bg-grey-card`

---

## Note

La struttura dei dati JSON è corretta, ma il rendering Blade non matcha la reference. Il problema è nel componente `<x-page>` o nel layout che renderizza i blocchi.

**File da investigare**:
- `resources/views/components/page.blade.php`
- `resources/views/layouts/app.blade.php`
- Componenti blocchi argomenti e servizi
