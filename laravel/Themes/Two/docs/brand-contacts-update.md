# Dati Contatto e Brand - Marco Sottana Consulenza Sicurezza

## Dati Aziendali

### Brand
- **Nome**: Marco Sottana
- **Sottotitolo**: Consulenza Sicurezza
- **Logo**: Da definire

### Contatti
- **Email**: studio@sottana.com
- **Telefono Fisso**: +39 041 455552
- **Telefono Mobile**: +39 347 58 96 127
- **Indirizzo**: Via Vanzo 86/A, 31021 Mogliano Veneto TV
- **P.IVA**: 05532540266
- **REA**: TV - 451911

### Mappa
- Embed OpenStreetMap (gratuito, no API key)
- Link diretto a OpenStreetMap per navigazione

## File Modificati

### Lang Files
- `Themes/Two/lang/it/header.php` - brand name
- `Themes/Two/lang/en/header.php` - brand name

### Blade Components
- `Themes/Two/resources/views/components/sections/footer/v1.blade.php`:
  - Aggiornato telefono fisso: +39 041 455552
  - Aggiornato telefono mobile: +39 347 58 96 127
  - Aggiornato email: studio@sottana.com
  - Aggiunto supporto per visualizzazione mobile phone
  - Aggiunta mappa OpenStreetMap embed

- `Themes/Two/resources/views/components/blocks/map/embed.blade.php`:
  - Migrato da Google Maps a OpenStreetMap
  - Usa Nominatim per geocodifica
  - Linka a OpenStreetMap per navigazione

- `Themes/Two/resources/views/components/blocks/contact/info.blade.php`:
  - Migrato da Google Maps a OpenStreetMap

- `Themes/Two/resources/views/components/blocks/company/data.blade.php`:
  - Migrato da Google Maps a OpenStreetMap

- `Themes/Two/resources/views/components/sections/footer.blade.php`:
  - WhatsApp link dinamico basato su mobile
  - Rimosso placeholder XXX

- `Themes/Two/resources/views/components/blocks/contact/form.blade.php`:
  - Aggiornato placeholder telefono

### Fix Header Component
- `Themes/Two/resources/views/components/sections/header.blade.php`:
  - Cambiato da `<x-pub_theme::...>` a `@include()` per fix cache

## Note

### Formato Telefoni
- Formato italiano con spazi: +39 041 455552
- Link tel: +39041455552 (senza spazi)

### Mappa
- Utilizza iframe OpenStreetMap (gratuito)
- Linka a OpenStreetMap per navigazione completa
- Posizione: Via Vanzo 86/A, Mogliano Veneto
- Vantaggi OpenStreetMap:
  - Nessuna API key richiesta
  - Nessun costo
  - Privacy migliore
  - Open source

## Prossimi Passi
- [ ] Aggiornare config/local/techplanner/database/content/ con i dati corretti
- [ ] Verificare che la mappa punti alle coordinate corrette
- [ ] Testare i link telefonici
- [ ] Valutare integrazione con modulo Geo per coordinate precise
