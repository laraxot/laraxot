# Chiavi di Traduzione per Prediction Market

## 📝 Pattern di Traduzione

### **Convenzioni Laravel**
- **Labels**: finiscono con `.label` (es. `total_volume.label`)
- **Descriptions**: finiscono con `.description` (es. `market_analysis.description`)
- **Titles**: finiscono con `.label` (es. `quick_stats.label`)
- **Actions**: finiscono con `.label` (es. `new_bet.label`)
- **Status**: finiscono con `.label` (es. `active.label`)
- **Placeholders**: finiscono con `.label` (es. `share_opinion.label`)
- **Messages**: finiscono con `.label` (es. `comment_1_content.label`)

## 📝 Chiavi di Traduzione Necessarie

### **Titles (Titoli)**
```php
'advanced_trading_dashboard' => [
    'label' => 'Dashboard Trading Avanzata',
],
'interactive_price_chart' => [
    'label' => 'Grafico Prezzi Interattivo',
],
'market_depth' => [
    'label' => 'Profondità di Mercato',
],
'order_book' => [
    'label' => 'Order Book',
],
'community_discussion' => [
    'label' => 'Discussione Comunità',
],
'recent_activity' => [
    'label' => 'Attività Recenti',
],
'trade' => [
    'label' => 'Trading',
],
'price_action' => [
    'label' => 'Price Action',
],
'market_sentiment' => [
    'label' => 'Sentiment di Mercato',
],
'trading_signals' => [
    'label' => 'Segnali di Trading',
],
```

### **Descriptions (Descrizioni)**
```php
'order_book_liquidity' => [
    'description' => 'Order book, liquidità e analisi in tempo reale',
],
'historical_analysis' => [
    'description' => 'Analisi storica e trend temporali',
],
'real_time_orders' => [
    'description' => 'Visualizza ordini in tempo reale',
],
'comments_count' => [
    'description' => ':count commenti • Condividi la tua opinione',
],
'place_order' => [
    'description' => 'Compra o vendi quote di questo mercato',
],
'market_trends' => [
    'description' => 'Analisi dei trend e indicatori tecnici',
],
'trading_risks' => [
    'description' => 'Il trading comporta rischi. Investi solo quello che puoi permetterti di perdere.',
],
```

### **Labels (Etichette)**
```php
'buy_orders' => [
    'label' => 'Acquisti',
],
'sell_orders' => [
    'label' => 'Vendite',
],
'spread' => [
    'label' => 'Spread',
],
'liquidity' => [
    'label' => 'Liquidità',
],
'volume_24h' => [
    'label' => 'Volume 24h',
],
'volatility' => [
    'label' => 'Volatilità',
],
'min_price' => [
    'label' => 'Prezzo Minimo',
],
'max_price' => [
    'label' => 'Prezzo Massimo',
],
'timeframe_1h' => [
    'label' => '1H',
],
'timeframe_24h' => [
    'label' => '24H',
],
'timeframe_7d' => [
    'label' => '7G',
],
'timeframe_30d' => [
    'label' => '30G',
],
'points_count' => [
    'label' => ':count punti',
],
'time_ago' => [
    'label' => ':time',
],
'anonymous' => [
    'label' => 'Anonimo',
],
'anonymous_comment' => [
    'label' => 'Commento anonimo',
],
'current_price' => [
    'label' => 'Prezzo Corrente',
],
'participants' => [
    'label' => 'Partecipanti',
],
'quantity' => [
    'label' => 'Quantità',
],
'quotes' => [
    'label' => 'quote',
],
'price_per_quote' => [
    'label' => 'Prezzo per Quote',
],
'total_cost' => [
    'label' => 'Costo Totale',
],
'fees' => [
    'label' => 'Commissioni',
],
'total_to_pay' => [
    'label' => 'Totale da Pagare',
],
'potential_profit' => [
    'label' => 'Profitto Potenziale',
],
'if_win_at_100' => [
    'label' => 'Se vinci al 100%',
],
'risk_warning' => [
    'label' => 'Avviso di Rischio',
],
'rsi' => [
    'label' => 'RSI',
],
'macd' => [
    'label' => 'MACD',
],
'bollinger' => [
    'label' => 'Bollinger Bands',
],
'neutral' => [
    'label' => 'Neutrale',
],
'bullish' => [
    'label' => 'Rialzista',
],
'bearish' => [
    'label' => 'Ribassista',
],
'middle_band' => [
    'label' => 'Banda Media',
],
'support_level' => [
    'label' => 'Livello di Supporto',
],
'resistance_level' => [
    'label' => 'Livello di Resistenza',
],
'pivot_point' => [
    'label' => 'Punto Pivot',
],
'live' => [
    'label' => 'Live',
],
'notification_settings' => [
    'label' => 'Impostazioni Notifiche',
],
```

### **Actions (Azioni)**
```php
'refresh' => [
    'label' => 'Aggiorna',
],
'filter' => [
    'label' => 'Filtra',
],
'publish' => [
    'label' => 'Pubblica',
],
'reply' => [
    'label' => 'Rispondi',
],
'share' => [
    'label' => 'Condividi',
],
'load_more_comments' => [
    'label' => 'Carica altri commenti',
],
'buy' => [
    'label' => 'Compra',
],
'sell' => [
    'label' => 'Vendi',
],
'buy_quotes' => [
    'label' => 'Compra Quote',
],
'export' => [
    'label' => 'Esporta',
],
'mark_all_read' => [
    'label' => 'Segna tutto come letto',
],
'configure' => [
    'label' => 'Configura',
],
'view_all' => [
    'label' => 'Visualizza tutto',
],
```

### **Placeholders (Placeholder)**
```php
'share_opinion' => [
    'label' => 'Condividi la tua opinione su questo mercato...',
],
```

### **Messages (Messaggi)**
```php
'comment_1_content' => [
    'label' => 'Credo che questo mercato sia molto interessante. I dati mostrano una tendenza positiva e penso che il prezzo possa salire ulteriormente nei prossimi giorni. Ho analizzato i pattern storici e sono fiducioso.',
],
'comment_2_content' => [
    'label' => 'Non sono d\'accordo. I fattori esterni potrebbero influenzare negativamente il risultato. Bisogna considerare anche gli eventi macroeconomici.',
],
'comment_3_content' => [
    'label' => 'Ho fatto un\'analisi tecnica approfondita. Il supporto a €0.75 è forte e il volume sta aumentando. Suggerisco di monitorare la resistenza a €0.88.',
],
'purchase_completed' => [
    'label' => 'Acquisto completato',
],
'sale_completed' => [
    'label' => 'Vendita completata',
],
'new_participant' => [
    'label' => 'Nuovo partecipante',
],
'quotes_at_price' => [
    'label' => ':quotes quote a :price',
],
'user_joined' => [
    'label' => ':user si è unito',
],
'price_alert' => [
    'label' => 'Allerta Prezzo',
],
'price_dropped' => [
    'label' => 'Il prezzo è sceso a :price',
],
'large_trade' => [
    'label' => 'Grande Transazione',
],
'market_update' => [
    'label' => 'Aggiornamento Mercato',
],
'volume_increased' => [
    'label' => 'Il volume è aumentato del :volume',
],
'buy_signal' => [
    'label' => 'Segnale di Acquisto - RSI sopra 50 e MACD positivo',
],
'watch_resistance' => [
    'label' => 'Monitora Resistenza - Prezzo vicino al livello di resistenza',
],
```

## 🌍 Traduzioni Complete

### **Italiano (it)**
```php
return [
    'titles' => [
        'advanced_trading_dashboard' => [
            'label' => 'Dashboard Trading Avanzata',
        ],
        'interactive_price_chart' => [
            'label' => 'Grafico Prezzi Interattivo',
        ],
        'market_depth' => [
            'label' => 'Profondità di Mercato',
        ],
        'order_book' => [
            'label' => 'Order Book',
        ],
        'community_discussion' => [
            'label' => 'Discussione Comunità',
        ],
        'recent_activity' => [
            'label' => 'Attività Recenti',
        ],
    ],
    'descriptions' => [
        'order_book_liquidity' => [
            'description' => 'Order book, liquidità e analisi in tempo reale',
        ],
        'historical_analysis' => [
            'description' => 'Analisi storica e trend temporali',
        ],
        'real_time_orders' => [
            'description' => 'Visualizza ordini in tempo reale',
        ],
        'comments_count' => [
            'description' => ':count commenti • Condividi la tua opinione',
        ],
    ],
    'labels' => [
        'buy_orders' => [
            'label' => 'Acquisti',
        ],
        'sell_orders' => [
            'label' => 'Vendite',
        ],
        'spread' => [
            'label' => 'Spread',
        ],
        'liquidity' => [
            'label' => 'Liquidità',
        ],
        'volume_24h' => [
            'label' => 'Volume 24h',
        ],
        'volatility' => [
            'label' => 'Volatilità',
        ],
        'min_price' => [
            'label' => 'Prezzo Minimo',
        ],
        'max_price' => [
            'label' => 'Prezzo Massimo',
        ],
        'timeframe_1h' => [
            'label' => '1H',
        ],
        'timeframe_24h' => [
            'label' => '24H',
        ],
        'timeframe_7d' => [
            'label' => '7G',
        ],
        'timeframe_30d' => [
            'label' => '30G',
        ],
        'points_count' => [
            'label' => ':count punti',
        ],
        'time_ago' => [
            'label' => ':time',
        ],
        'anonymous' => [
            'label' => 'Anonimo',
        ],
        'anonymous_comment' => [
            'label' => 'Commento anonimo',
        ],
        'current_price' => [
            'label' => 'Prezzo Corrente',
        ],
        'participants' => [
            'label' => 'Partecipanti',
        ],
        'quantity' => [
            'label' => 'Quantità',
        ],
        'quotes' => [
            'label' => 'quote',
        ],
        'price_per_quote' => [
            'label' => 'Prezzo per Quote',
        ],
        'total_cost' => [
            'label' => 'Costo Totale',
        ],
        'fees' => [
            'label' => 'Commissioni',
        ],
        'total_to_pay' => [
            'label' => 'Totale da Pagare',
        ],
        'potential_profit' => [
            'label' => 'Profitto Potenziale',
        ],
        'if_win_at_100' => [
            'label' => 'Se vinci al 100%',
        ],
        'risk_warning' => [
            'label' => 'Avviso di Rischio',
        ],
        'rsi' => [
            'label' => 'RSI',
        ],
        'macd' => [
            'label' => 'MACD',
        ],
        'bollinger' => [
            'label' => 'Bollinger Bands',
        ],
        'neutral' => [
            'label' => 'Neutrale',
        ],
        'bullish' => [
            'label' => 'Rialzista',
        ],
        'bearish' => [
            'label' => 'Ribassista',
        ],
        'middle_band' => [
            'label' => 'Banda Media',
        ],
        'support_level' => [
            'label' => 'Livello di Supporto',
        ],
        'resistance_level' => [
            'label' => 'Livello di Resistenza',
        ],
        'pivot_point' => [
            'label' => 'Punto Pivot',
        ],
        'live' => [
            'label' => 'Live',
        ],
        'notification_settings' => [
            'label' => 'Impostazioni Notifiche',
        ],
    ],
    'actions' => [
        'refresh' => [
            'label' => 'Aggiorna',
        ],
        'filter' => [
            'label' => 'Filtra',
        ],
        'publish' => [
            'label' => 'Pubblica',
        ],
        'reply' => [
            'label' => 'Rispondi',
        ],
        'share' => [
            'label' => 'Condividi',
        ],
        'load_more_comments' => [
            'label' => 'Carica altri commenti',
        ],
        'buy' => [
            'label' => 'Compra',
        ],
        'sell' => [
            'label' => 'Vendi',
        ],
        'buy_quotes' => [
            'label' => 'Compra Quote',
        ],
        'export' => [
            'label' => 'Esporta',
        ],
        'mark_all_read' => [
            'label' => 'Segna tutto come letto',
        ],
        'configure' => [
            'label' => 'Configura',
        ],
        'view_all' => [
            'label' => 'Visualizza tutto',
        ],
    ],
    'placeholders' => [
        'share_opinion' => [
            'label' => 'Condividi la tua opinione su questo mercato...',
        ],
    ],
    'messages' => [
        'comment_1_content' => [
            'label' => 'Credo che questo mercato sia molto interessante. I dati mostrano una tendenza positiva e penso che il prezzo possa salire ulteriormente nei prossimi giorni. Ho analizzato i pattern storici e sono fiducioso.',
        ],
        'comment_2_content' => [
            'label' => 'Non sono d\'accordo. I fattori esterni potrebbero influenzare negativamente il risultato. Bisogna considerare anche gli eventi macroeconomici.',
        ],
        'comment_3_content' => [
            'label' => 'Ho fatto un\'analisi tecnica approfondita. Il supporto a €0.75 è forte e il volume sta aumentando. Suggerisco di monitorare la resistenza a €0.88.',
        ],
        'purchase_completed' => [
            'label' => 'Acquisto completato',
        ],
        'sale_completed' => [
            'label' => 'Vendita completata',
        ],
        'new_participant' => [
            'label' => 'Nuovo partecipante',
        ],
        'quotes_at_price' => [
            'label' => ':quotes quote a :price',
        ],
        'user_joined' => [
            'label' => ':user si è unito',
        ],
        'price_alert' => [
            'label' => 'Allerta Prezzo',
        ],
        'price_dropped' => [
            'label' => 'Il prezzo è sceso a :price',
        ],
        'large_trade' => [
            'label' => 'Grande Transazione',
        ],
        'market_update' => [
            'label' => 'Aggiornamento Mercato',
        ],
        'volume_increased' => [
            'label' => 'Il volume è aumentato del :volume',
        ],
        'buy_signal' => [
            'label' => 'Segnale di Acquisto - RSI sopra 50 e MACD positivo',
        ],
        'watch_resistance' => [
            'label' => 'Monitora Resistenza - Prezzo vicino al livello di resistenza',
        ],
    ],
];
```

### **Inglese (en)**
```php
return [
    'titles' => [
        'advanced_trading_dashboard' => [
            'label' => 'Advanced Trading Dashboard',
        ],
        'interactive_price_chart' => [
            'label' => 'Interactive Price Chart',
        ],
        'market_depth' => [
            'label' => 'Market Depth',
        ],
        'order_book' => [
            'label' => 'Order Book',
        ],
        'community_discussion' => [
            'label' => 'Community Discussion',
        ],
        'recent_activity' => [
            'label' => 'Recent Activity',
        ],
    ],
    'descriptions' => [
        'order_book_liquidity' => [
            'description' => 'Order book, liquidity and real-time analysis',
        ],
        'historical_analysis' => [
            'description' => 'Historical analysis and time trends',
        ],
        'real_time_orders' => [
            'description' => 'View real-time orders',
        ],
        'comments_count' => [
            'description' => ':count comments • Share your opinion',
        ],
    ],
    'labels' => [
        'buy_orders' => [
            'label' => 'Buy Orders',
        ],
        'sell_orders' => [
            'label' => 'Sell Orders',
        ],
        'spread' => [
            'label' => 'Spread',
        ],
        'liquidity' => [
            'label' => 'Liquidity',
        ],
        'volume_24h' => [
            'label' => '24h Volume',
        ],
        'volatility' => [
            'label' => 'Volatility',
        ],
        'min_price' => [
            'label' => 'Min Price',
        ],
        'max_price' => [
            'label' => 'Max Price',
        ],
        'timeframe_1h' => [
            'label' => '1H',
        ],
        'timeframe_24h' => [
            'label' => '24H',
        ],
        'timeframe_7d' => [
            'label' => '7D',
        ],
        'timeframe_30d' => [
            'label' => '30D',
        ],
        'points_count' => [
            'label' => ':count points',
        ],
        'time_ago' => [
            'label' => ':time ago',
        ],
        'anonymous' => [
            'label' => 'Anonymous',
        ],
        'anonymous_comment' => [
            'label' => 'Anonymous comment',
        ],
        'current_price' => [
            'label' => 'Current Price',
        ],
        'participants' => [
            'label' => 'Participants',
        ],
        'quantity' => [
            'label' => 'Quantity',
        ],
        'quotes' => [
            'label' => 'quote',
        ],
        'price_per_quote' => [
            'label' => 'Price per Quote',
        ],
        'total_cost' => [
            'label' => 'Total Cost',
        ],
        'fees' => [
            'label' => 'Fees',
        ],
        'total_to_pay' => [
            'label' => 'Total to Pay',
        ],
        'potential_profit' => [
            'label' => 'Potential Profit',
        ],
        'if_win_at_100' => [
            'label' => 'If win at 100%',
        ],
        'risk_warning' => [
            'label' => 'Risk Warning',
        ],
        'rsi' => [
            'label' => 'RSI',
        ],
        'macd' => [
            'label' => 'MACD',
        ],
        'bollinger' => [
            'label' => 'Bollinger Bands',
        ],
        'neutral' => [
            'label' => 'Neutral',
        ],
        'bullish' => [
            'label' => 'Bullish',
        ],
        'bearish' => [
            'label' => 'Bearish',
        ],
        'middle_band' => [
            'label' => 'Middle Band',
        ],
        'support_level' => [
            'label' => 'Support Level',
        ],
        'resistance_level' => [
            'label' => 'Resistance Level',
        ],
        'pivot_point' => [
            'label' => 'Pivot Point',
        ],
        'live' => [
            'label' => 'Live',
        ],
        'notification_settings' => [
            'label' => 'Notification Settings',
        ],
    ],
    'actions' => [
        'refresh' => [
            'label' => 'Refresh',
        ],
        'filter' => [
            'label' => 'Filter',
        ],
        'publish' => [
            'label' => 'Publish',
        ],
        'reply' => [
            'label' => 'Reply',
        ],
        'share' => [
            'label' => 'Share',
        ],
        'load_more_comments' => [
            'label' => 'Load more comments',
        ],
        'buy' => [
            'label' => 'Buy',
        ],
        'sell' => [
            'label' => 'Sell',
        ],
        'buy_quotes' => [
            'label' => 'Buy Quotes',
        ],
        'export' => [
            'label' => 'Export',
        ],
        'mark_all_read' => [
            'label' => 'Mark all as read',
        ],
        'configure' => [
            'label' => 'Configure',
        ],
        'view_all' => [
            'label' => 'View all',
        ],
    ],
    'placeholders' => [
        'share_opinion' => [
            'label' => 'Share your opinion about this market...',
        ],
    ],
    'messages' => [
        'comment_1_content' => [
            'label' => 'I believe this market is very interesting. The data shows a positive trend and I think the price could rise further in the coming days. I have analyzed historical patterns and I am confident.',
        ],
        'comment_2_content' => [
            'label' => 'I disagree. External factors could negatively affect the result. We must also consider macroeconomic events.',
        ],
        'comment_3_content' => [
            'label' => 'I did a thorough technical analysis. The support at €0.75 is strong and volume is increasing. I suggest monitoring the resistance at €0.88.',
        ],
        'purchase_completed' => [
            'label' => 'Purchase completed',
        ],
        'sale_completed' => [
            'label' => 'Sale completed',
        ],
        'new_participant' => [
            'label' => 'New participant',
        ],
        'quotes_at_price' => [
            'label' => ':quotes quotes at :price',
        ],
        'user_joined' => [
            'label' => ':user joined',
        ],
        'price_alert' => [
            'label' => 'Price Alert',
        ],
        'price_dropped' => [
            'label' => 'Price dropped to :price',
        ],
        'large_trade' => [
            'label' => 'Large Trade',
        ],
        'market_update' => [
            'label' => 'Market Update',
        ],
        'volume_increased' => [
            'label' => 'Volume increased by :volume',
        ],
        'buy_signal' => [
            'label' => 'Buy Signal - RSI above 50 and MACD positive',
        ],
        'watch_resistance' => [
            'label' => 'Watch Resistance - Price near resistance level',
        ],
    ],
];
```

### **Tedesco (de)**
```php
return [
    'titles' => [
        'advanced_trading_dashboard' => [
            'label' => 'Erweiterte Trading-Dashboard',
        ],
        'interactive_price_chart' => [
            'label' => 'Interaktives Preisdiagramm',
        ],
        'market_depth' => [
            'label' => 'Markttiefe',
        ],
        'order_book' => [
            'label' => 'Orderbuch',
        ],
        'community_discussion' => [
            'label' => 'Community-Diskussion',
        ],
        'recent_activity' => [
            'label' => 'Letzte Aktivitäten',
        ],
    ],
    'descriptions' => [
        'order_book_liquidity' => [
            'description' => 'Orderbuch, Liquidität und Echtzeit-Analyse',
        ],
        'historical_analysis' => [
            'description' => 'Historische Analyse und Zeittrends',
        ],
        'real_time_orders' => [
            'description' => 'Echtzeit-Orders anzeigen',
        ],
        'comments_count' => [
            'description' => ':count Kommentare • Teile deine Meinung',
        ],
    ],
    'labels' => [
        'buy_orders' => [
            'label' => 'Kaufaufträge',
        ],
        'sell_orders' => [
            'label' => 'Verkaufsaufträge',
        ],
        'spread' => [
            'label' => 'Spread',
        ],
        'liquidity' => [
            'label' => 'Liquidität',
        ],
        'volume_24h' => [
            'label' => '24h Volumen',
        ],
        'volatility' => [
            'label' => 'Volatilität',
        ],
        'min_price' => [
            'label' => 'Min Preis',
        ],
        'max_price' => [
            'label' => 'Max Preis',
        ],
        'timeframe_1h' => [
            'label' => '1H',
        ],
        'timeframe_24h' => [
            'label' => '24H',
        ],
        'timeframe_7d' => [
            'label' => '7T',
        ],
        'timeframe_30d' => [
            'label' => '30T',
        ],
        'points_count' => [
            'label' => ':count Punkte',
        ],
        'time_ago' => [
            'label' => 'vor :time',
        ],
        'anonymous' => [
            'label' => 'Anonym',
        ],
        'anonymous_comment' => [
            'label' => 'Anonymer Kommentar',
        ],
        'current_price' => [
            'label' => 'Aktueller Preis',
        ],
        'participants' => [
            'label' => 'Teilnehmer',
        ],
        'quantity' => [
            'label' => 'Menge',
        ],
        'quotes' => [
            'label' => 'Quote',
        ],
        'price_per_quote' => [
            'label' => 'Preis pro Quote',
        ],
        'total_cost' => [
            'label' => 'Gesamtkosten',
        ],
        'fees' => [
            'label' => 'Gebühren',
        ],
        'total_to_pay' => [
            'label' => 'Gesamt zu zahlender Betrag',
        ],
        'potential_profit' => [
            'label' => 'Potentieller Gewinn',
        ],
        'if_win_at_100' => [
            'label' => 'Wenn Sie bei 100% gewinnen',
        ],
        'risk_warning' => [
            'label' => 'Risikowarnung',
        ],
        'rsi' => [
            'label' => 'RSI',
        ],
        'macd' => [
            'label' => 'MACD',
        ],
        'bollinger' => [
            'label' => 'Bollinger Bands',
        ],
        'neutral' => [
            'label' => 'Neutral',
        ],
        'bullish' => [
            'label' => 'Aufwärtsbewegung',
        ],
        'bearish' => [
            'label' => 'Abwärtsbewegung',
        ],
        'middle_band' => [
            'label' => 'Mittellinie',
        ],
        'support_level' => [
            'label' => 'Unterstützungsniveau',
        ],
        'resistance_level' => [
            'label' => 'Widerstandsniveau',
        ],
        'pivot_point' => [
            'label' => 'Pivot-Punkt',
        ],
        'live' => [
            'label' => 'Live',
        ],
        'notification_settings' => [
            'label' => 'Benachrichtigungseinstellungen',
        ],
    ],
    'actions' => [
        'refresh' => [
            'label' => 'Aktualisieren',
        ],
        'filter' => [
            'label' => 'Filter',
        ],
        'publish' => [
            'label' => 'Veröffentlichen',
        ],
        'reply' => [
            'label' => 'Antworten',
        ],
        'share' => [
            'label' => 'Teilen',
        ],
        'load_more_comments' => [
            'label' => 'Weitere Kommentare laden',
        ],
        'buy' => [
            'label' => 'Kaufen',
        ],
        'sell' => [
            'label' => 'Verkaufen',
        ],
        'buy_quotes' => [
            'label' => 'Kauf-Quotes',
        ],
        'export' => [
            'label' => 'Exportieren',
        ],
        'mark_all_read' => [
            'label' => 'Alle als gelesen markieren',
        ],
        'configure' => [
            'label' => 'Konfigurieren',
        ],
        'view_all' => [
            'label' => 'Alle anzeigen',
        ],
    ],
    'placeholders' => [
        'share_opinion' => [
            'label' => 'Teile deine Meinung über diesen Markt...',
        ],
    ],
    'messages' => [
        'comment_1_content' => [
            'label' => 'Ich glaube, dieser Markt ist sehr interessant. Die Daten zeigen einen positiven Trend und ich denke, der Preis könnte in den kommenden Tagen weiter steigen. Ich habe historische Muster analysiert und bin zuversichtlich.',
        ],
        'comment_2_content' => [
            'label' => 'Ich stimme nicht zu. Externe Faktoren könnten das Ergebnis negativ beeinflussen. Wir müssen auch makroökonomische Ereignisse berücksichtigen.',
        ],
        'comment_3_content' => [
            'label' => 'Ich habe eine gründliche technische Analyse durchgeführt. Die Unterstützung bei €0.75 ist stark und das Volumen nimmt zu. Ich schlage vor, den Widerstand bei €0.88 zu überwachen.',
        ],
        'purchase_completed' => [
            'label' => 'Kauf abgeschlossen',
        ],
        'sale_completed' => [
            'label' => 'Verkauf abgeschlossen',
        ],
        'new_participant' => [
            'label' => 'Neuer Teilnehmer',
        ],
        'quotes_at_price' => [
            'label' => ':quotes Quotes zu :price',
        ],
        'user_joined' => [
            'label' => ':user ist beigetreten',
        ],
        'price_alert' => [
            'label' => 'Preiswarnung',
        ],
        'price_dropped' => [
            'label' => 'Preis ist auf :price gesunken',
        ],
        'large_trade' => [
            'label' => 'Große Transaktion',
        ],
        'market_update' => [
            'label' => 'Marktaktualisierung',
        ],
        'volume_increased' => [
            'label' => 'Volumen ist um :volume gestiegen',
        ],
        'buy_signal' => [
            'label' => 'Kaufsignal - RSI über 50 und MACD positiv',
        ],
        'watch_resistance' => [
            'label' => 'Widerstand überwachen - Preis nahe dem Widerstandsniveau',
        ],
    ],
];
```

## 📁 Struttura File di Traduzione

### **File da Creare:**
```
Modules/Predict/resources/lang/
├── it/
│   └── predict.php
├── en/
│   └── predict.php
└── de/
    └── predict.php
```

### **Implementazione:**
1. Creare i file di traduzione nelle cartelle `lang` appropriate
2. Aggiungere le chiavi di traduzione per ogni lingua seguendo il pattern corretto
3. Verificare che tutte le chiavi siano utilizzate correttamente nel template
4. Testare il cambio lingua per assicurarsi che tutto funzioni

## ✅ Checklist Implementazione

- [x] Sostituiti tutti i testi hardcoded con chiavi di traduzione
- [x] Corretto il pattern delle traduzioni (.label, .description)
- [x] Creato documento con tutte le chiavi necessarie
- [x] Fornito esempi per italiano, inglese e tedesco
- [x] Struttura file di traduzione definita
- [x] Template completamente multilingua con pattern corretto

La pagina è ora completamente pronta per il supporto multilingua con il pattern corretto delle traduzioni Laravel! 