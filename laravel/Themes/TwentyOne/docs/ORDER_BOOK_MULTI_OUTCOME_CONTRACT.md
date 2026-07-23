# Order Book Multi Outcome Contract

Path: `laravel/Themes/TwentyOne/docs/ORDER_BOOK_MULTI_OUTCOME_CONTRACT.md`

## Scopo

Allineare il tema `TwentyOne` al contratto di dominio corretto del modulo `Predict`.

La UI non deve assumere che esistano due strutture distinte:

- mercato binario
- mercato multi-outcome

La UI deve assumere solo:

- una lista di outcome
- ciascun outcome con il proprio order book

## Contratto Atteso

```php
[
    'markets' => [
        [
            'id' => 1,
            'key' => 'max-verstappen',
            'title' => 'Max Verstappen',
            'price' => 47,
            'last_price' => 47,
            'spread' => 2,
            'bids' => [...],
            'asks' => [...],
        ],
    ],
]
```

## Regole UI

### 1. Loop sugli outcome

La view dell'order book deve iterare `markets[]`.

### 2. Nessuna branch primaria yes/no

Da evitare come contratto principale:

- `orderBook['yes']`
- `orderBook['no']`

Questa struttura puo esistere solo come compatibilita legacy temporanea.

### 3. Titolo outcome reale

L'intestazione della sezione deve mostrare il titolo reale dell'outcome.

Esempi:

- `SÌ` / `NO` se il mercato ha due opzioni nominate cosi
- `Max Verstappen`, `Lewis Hamilton`, `Charles Leclerc` per un mercato F1

### 4. Tema agnostico

Il tema non decide il tipo di mercato. Il tema riceve dati gia normalizzati dal modulo e li rende.

## Compatibilita Legacy

Campi come `sum_credit_yes` e `sum_credit_no` non devono guidare la UI. Se presenti, servono solo come fallback legacy o cache temporanea.

## Decisione

Per il tema `TwentyOne`, `yes/no` non e un tipo UI separato. E una lista di due opzioni nello stesso schema usato per tutti gli altri mercati.
