# Dati Contatto e Brand - Sottana Service

## Dati Aziendali Corretti

### Contatti
| Tipo | Valore |
|------|--------|
| Email | studio@sottana.com |
| Telefono Fisso | +39 041 455552 |
| Telefono Mobile | +39 347 58 96 127 |
| Indirizzo | via Vanzo 86/A, 31021 Mogliano Veneto TV |
| P.IVA | 05532540266 |
|REA | TV - 451911 |

### Coordinate Geografiche (Nominatim)
- **Latitudine**: 45.5633
- **Longitudine**: 12.2506
- Indirizzo: Via Vanzo 86/A, 31021 Mogliano Veneto TV

### Produzione e validazione
- **Sito live**: https://sottana.net
- **Contatti**: https://sottana.net/it/contatti
- **Deploy**: push su branch `master` → auto-deploy; verificare contatti e mappa dopo il deploy

### Brand
| Campo | Valore |
|-------|--------|
| Nome | Sottana Service |
| Logo | Elefante stilizzato SVG (nel header) |

### Mappa
- **Implementazione**: PNG statica (se presente) oppure iframe Google Maps embed (gratuito, non API)
- **Link navigazione**: Google Maps con coordinate (gratuito, non API)
- **Regola**: MAI usare Google Maps API a pagamento; screenshot manuale da Google Maps UI o iframe embed consentiti
- **Componente**: `pub_theme::components.blocks.map.static-clickable`

## File Modificati

### 1. Lang Files
- `Themes/Two/lang/it/header.php` - brand name
- `Themes/Two/lang/en/header.php` - brand name

### 2. Blade Components
- `Themes/Two/resources/views/components/sections/header.blade.php` - fix @include
- `Themes/Two/resources/views/components/sections/footer/v1.blade.php`:
  - Email: studio@sottana.com
  - Telefono fisso: +39 041 455552
  - Telefono mobile: +39 347 58 96 127
  - Supporto tipo "phone_mobile" e "mobile"
  - Mappa Google Maps embed

### 3. JSON Config
- `config/local/techplanner/database/content/sections/footer.json`:
  - Dati contatto aggiornati
  - Sezione map aggiunta
- `config/local/techplanner/database/content/sections/header.json`:
  - Brand: Sottana Service

## Note Tecniche

### Formato Telefoni
- Visualizzazione: +39 041 455552
- Link tel: +39041455552 (senza spazi)

### Tipi Contatto nel JSON
- `type: "phone"` - telefono fisso
- `type: "phone_mobile"` o `"mobile"` - cellulare

### Mappa
- PNG statica (opzionale) in `public/modules/techplanner/images/map-via-vanzo.png`
- Fallback: iframe Google Maps embed (gratuito)
- Link navigazione: coordinate 45.5633, 12.2506
- Validazione produzione: https://sottana.net/it/contatti

## Collegamenti
- [Deployment e validazione](./deployment-and-validation.md)
- [Mappa statica implementazione](./mappa-statica-implementazione-completa.md)
- [Genera mappa manuale](../../Modules/TechPlanner/docs/genera-mappa-manuale.md)
