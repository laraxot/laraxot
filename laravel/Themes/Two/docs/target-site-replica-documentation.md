# Documentazione Replica Sito Target
## https://lightseagreen-dogfish-560272.hostingersite.com/

---

## 📁 File Structure

```
/laravel/Themes/Two/Main_files/
├── index.html                          # Replica statica completa
├── images/                             # Immagini scaricate
│   ├── radiologia-veterinaria.jpg
│   ├── medical-equipment.jpg
│   ├── dr-roberto-magni.jpg
│   ├── dr-elena-visentin.jpg
│   ├── dr-paolo-verdi.jpg
│   ├── dr-giulia-bianchi.jpg
│   └── ...
```

## 🏗️ Analisi Componenti

### 1. Header Navigation
- **Stile**: Fixed top, transparent with backdrop-blur.
- **Transizione**: Su scroll diventa solido/scuro (da implementare con Alpine.js).
- **Branding**: Logo circolare con iniziali (TP) + Titolo dinamico.
- **Menu**: Links orizzontali centrati, CTA a destra.

### 2. Hero Section
- **Sfondo**: Immagine `hero-bg.jpg` con overlay gradiente scuro.
- **Contenuto**: Titolo H1 grande, sottotitolo leggibile, primary CTA + secondary link.
- **Stats**: Row di statistiche/certificazioni alla base della hero.

### 3. Blocchi Servizi
- Grid di card con icone moderne.
- Hover effect con micro-animazioni (già presenti nel tema, da affinare).

### 4. Testimonials
- Layout a 4 card (2x2) con avatar circolari e 3 righe di metadata (Nome, Ruolo, Compagnia/Location).

## 🛠️ Roadmap Replicazione

1. [x] **Fix Backend Logic**: Risolto bug `HasBlocks` per recupero locale corretto.
2. [ ] **Header Parity**:
   - Integrare Alpine.js per gestiore lo stato dello scroll (`scrolled = window.pageYOffset > 50`).
   - Sincronizzare `header.json` con i link del target.
3. [ ] **Hero Block**:
   - Creare/Aggiornare `hero/simple.blade.php`.
   - Popolare `home.json` con testi e stats reali del target.
4. [ ] **Asset Management**:
   - Assicurarsi che le immagini siano caricate in `Themes/Two/resources/images` e copiate in `public_html`.
5. [ ] **SEO & Marketing**:
   - Ottimizzare MetaTags e Titles.
   - Predisporre slot per Adsense (da documentare in `docs/adsense-integration-guide.md`).

## 🚀 Migliorie Proposte
- **Dark Mode**: Supporto nativo per tema scuro.
- **Performance**: Lazy loading per le immagini pesanti (Unsplash assets).
- **SEO**: Schema.org JSON-LD per `MedicalBusiness` o `LocalBusiness`.