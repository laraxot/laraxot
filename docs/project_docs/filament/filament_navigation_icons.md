# Icone di Navigazione in Filament

## Utilizzo degli SVG nelle Navigation Icons

Le icone di navigazione in Filament utilizzano SVG personalizzati per una migliore qualità e consistenza visiva.

### Posizionamento degli SVG
Gli SVG personalizzati devono essere posizionati nella cartella `resources/svg` del modulo:
```
ModuloName/
├── resources/
│   ├── svg/
│   │   ├── icon.svg        # Icona principale del modulo
│   │   ├── event.svg       # SVG semplice senza prefisso
│   │   ├── profile.svg     # SVG semplice senza prefisso
│   │   └── ...
```

### Riferimento agli SVG nei File di Traduzione
```php
return [
    'navigation' => [
        'icon' => 'modulename-event',    // IMPORTANTE: Usare il prefisso del modulo in lowercase
        'group' => 'Sistema',
        'label' => 'Eventi',
        'sort' => 12,
    ],
];
```

### Convenzioni di Naming CORRETTE
1. **File SVG**
   - ✅ Usare nomi semplici e diretti: `event.svg`
   - ✅ Usare kebab-case se necessario: `user-profile.svg`
   - ✅ Mantenere i nomi concisi
   - ❌ NON includere il prefisso del modulo nel nome file: `gdpr-event.svg`
   - ❌ NON usare nomi complessi: `gdpr_event_monitor.svg`

2. **Riferimenti nelle Traduzioni**
   - ✅ Usare il prefisso del modulo: `gdpr-event`
   - ✅ Modulo e nome separati da trattino: `job-monitor`
   - ✅ Tutto in lowercase: `xot-cache`
   - ❌ NON omettere il prefisso del modulo
   - ❌ NON usare maiuscole: `GDPR-Event`

### Esempi per Moduli Specifici

1. **Modulo GDPR**
   ```php
   // File: event.svg
   'icon' => 'gdpr-event'    // CORRETTO
   'icon' => 'event'         // ERRATO: manca il prefisso del modulo
   ```

2. **Modulo Job**
   ```php
   // File: monitor.svg
   'icon' => 'job-monitor'   // CORRETTO
   'icon' => 'monitor'       // ERRATO: manca il prefisso del modulo
   ```

3. **Modulo Xot**
   ```php
   // File: cache.svg
   'icon' => 'xot-cache'     // CORRETTO
   'icon' => 'cache'         // ERRATO: manca il prefisso del modulo
   ```

### Best Practices per gli SVG
1. **Naming dei File**
   - Usare nomi descrittivi ma concisi per gli SVG
   - NON includere il prefisso del modulo nel nome file
   - Usare kebab-case solo se necessario per chiarezza
   - Mantenere coerenza in tutto il modulo

2. **Riferimenti nelle Traduzioni**
   - SEMPRE includere il prefisso del modulo in lowercase
   - Usare il trattino come separatore
   - Il riferimento deve essere: `modulename-filename`

3. **Struttura SVG**
   - Mantenere dimensioni consistenti (24x24 consigliato)
   - Usare viewBox="0 0 24 24"
   - Ottimizzare gli SVG per dimensioni ridotte

4. **Accessibilità**
   - Includere attributi aria-hidden="true"
   - Aggiungere role="img" quando appropriato
   - Fornire descrizioni significative

### Note Importanti
1. Il nome del file SVG deve essere semplice e senza prefisso
2. Il riferimento nelle traduzioni DEVE includere il prefisso del modulo
3. Usare sempre lowercase per i riferimenti
4. Testare sempre che l'icona venga caricata correttamente
5. In caso di dubbi, controllare questa documentazione
