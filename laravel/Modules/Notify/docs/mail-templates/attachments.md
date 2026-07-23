---
title: "Gestione degli Allegati nelle Email"
type: concept
tags: [attachments]
created: 2026-07-14
updated: 2026-07-14
qmd: "attachments gestione degli allegati nelle email"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./email-best-practices-1.md"
  - "./email-best-practices.md"
  - "./email-layouts-best-practices-1.md"
  - "./email-layouts-best-practices.md"
  - "./email-templates-best-practices-1.md"
  - "./email-templates-best-practices.md"
  - "./email-templates-guide-1.md"
  - "./email-templates-guide.md"
---

# Gestione degli Allegati nelle Email

## Implementazione Corretta

### Formato Corretto
```php
$attachments = [
    [
        'path' => 'public_html/images/avatars/default-3.svg',
        'path' => 'public_html/images/avatars/default-3.svg',
        'path' => 'public_html/images/avatars/default-3.svg',
        'as' => 'logo.png',
        'mime' => 'image/png'
    ],
    [
        'path' => 'public_html/images/avatars/default-3.svg',
        'path' => 'public_html/images/avatars/default-3.svg',
        'path' => 'public_html/images/avatars/default-3.svg',
        'as' => 'logo.png',
        'mime' => 'image/png'
    ]
];
```

### Caratteristiche Chiave
1. **Struttura Array**
   - Array di array associativi
   - Ogni allegato è un array separato
   - Chiavi: path, as, mime

2. **Percorsi**
   - Possono essere assoluti o relativi
   - Devono essere accessibili dal server
   - Verificare i permessi dei file

3. **Integrazione con SpatieEmail**
   ```php
   // Creare l'istanza dell'email
   $email = new SpatieEmail($user, 'due');
   
   // Aggiungere gli allegati
   $email->addAttachments($attachments);
   
   // Inviare l'email
   Mail::to($data['to'])
       ->locale('it')
       ->send($email);
   ```

## Best Practices Verificate

### 1. Organizzazione File
- Mantenere gli allegati in una posizione accessibile
- Utilizzare percorsi coerenti
- Verificare i permessi dei file

### 2. Gestione MIME Types
- Specificare sempre il MIME type corretto
- Verificare la compatibilità con i client email
- Documentare i tipi supportati

### 3. Performance
- Ottimizzare le dimensioni dei file
- Considerare l'impatto sulla velocità di invio
- Monitorare l'uso della memoria

## Esempi di Implementazione

### Esempio Base
```php
$attachments = [
    [
        'path' => '/path/to/file.pdf',
        'as' => 'documento.pdf',
        'mime' => 'application/pdf'
    ]
];

$email = new SpatieEmail($user, 'template');
$email->addAttachments($attachments);

Mail::to($user->email)
    ->locale('it')
    ->send($email);
```

### Esempio con Multipli Allegati
```php
$attachments = [
    [
        'path' => '/path/to/image.png',
        'as' => 'logo.png',
        'mime' => 'image/png'
    ],
    [
        'path' => '/path/to/document.pdf',
        'as' => 'fattura.pdf',
        'mime' => 'application/pdf'
    ]
];

$email = new SpatieEmail($user, 'template');
$email->addAttachments($attachments);

Mail::to($user->email)
    ->locale('it')
    ->send($email);
```

## Troubleshooting

### Problemi Comuni
1. **File non trovato**
   - Verificare il percorso del file
   - Controllare i permessi
   - Verificare l'accessibilità

2. **MIME type errato**
   - Utilizzare il MIME type corretto
   - Verificare l'estensione
   - Testare in diversi client

3. **Dimensioni eccessive**
   - Comprimere i file
   - Verificare i limiti
   - Considerare link di download

## Note Importanti
- Testare in ambiente di sviluppo
- Verificare la compatibilità
- Documentare i tipi supportati
- Implementare gestione errori
- Monitorare le performance 
