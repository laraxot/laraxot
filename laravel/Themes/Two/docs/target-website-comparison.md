# Confronto Sito Locale vs Sito Target

## Data: 6 Febbraio 2026

## Siti Confrontati
- **Sito Locale**: http://127.0.0.1:8000/it
- **Sito Target**: https://lightseagreen-dogfish-560272.hostingersite.com/

## Differenze Principali

### 1. Struttura e Contenuto

#### Sito Locale (Attuale)
- Titolo: "Laravel"
- Struttura HTML di base con Filament framework
- Include cookie-consent e Filament assets
- Browser logger attivo per debugging
- Contenuto placeholder o generico

#### Sito Target (Obiettivo)
- Titolo: "Radioprotezione e Sicurezza Radiologica per Studi Dentistici e Veterinari"
- Sito professionale di radioprotezione
- Contenuto specifico per studi dentistici e veterinari
- Struttura completa con sezioni ben definite

### 2. Sezioni del Sito Target

#### Header/Navigation
- Logo "RP" (Radioprotezione)
- Menu di navigazione: Servizi, Settori, Controlli, Testimonianze, Contatti
- Bottone "Contattaci" in evidenza
- Fixed header con backdrop blur

#### Hero Section
- Titolo principale con gradient
- Sottotitolo descrittivo
- Due call-to-action: "Richiedi Preventivo" e "Scopri i Servizi"
- Background con gradiente blu e pattern radiale

#### Servizi (3 card principali)
1. **Controllo Radioprotezione** - Verifiche periodiche e straordinarie
2. **Controllo Elettromedicali** - Manutenzione preventiva (IEC 62353)
3. **Documentazione e Conformità** - Gestione documentazione obbligatoria

#### Perché Radioprotezione (4 punti chiave)
1. Rischi e Sanzioni
2. Obblighi Normativi (D.Lgs 101/2020, Direttiva 2013/59/Euratom)
3. Sicurezza Persone
4. Responsabilità Legale

#### Settori di Specializzazione
1. **Odontoiatria**
   - Radiografie Endorali
   - Ortopantomografia (OPT)
   - CBCT (Cone Beam)
2. **Medicina Veterinaria**
   - Radiologia Digitale e Analogica
   - Biosicurezza Radiologica
   - Fluoroscopia e Arco a C

#### Cosa Controlliamo (5 aree)
1. Dosimetria
2. Schermature
3. Apparecchiature Radiologiche
4. Sistemi di Protezione
5. Documentazione e Registri

#### Testimonianze (4 recensioni)
1. Dr. Roberto Magni - Centro Odontoiatrico Magni, Padova
2. Dr.ssa Elena Visentin - Clinica Veterinaria San Marco, Mestre
3. Dr. Paolo Verdi - Studio Odontoiatrico Verdi, Treviso
4. Dr.ssa Giulia Bianchi - VetLife Ambulatorio, Vicenza

#### Risorse Utili
1. Guida Radioprotezione Odontoiatrica (PDF)
2. Guida Radioprotezione Veterinaria (PDF)

#### Newsletter
- Form di iscrizione per aggiornamenti normativi

#### Footer
- Logo e descrizione
- Link ai servizi
- Link ai settori
- Contatti (email, telefono)

### 3. Palette Colori

#### Sito Target
- **Primario**: #1E5A96 (Blu scuro professionale)
- **Secondario**: #2D8659 (Verde per settore veterinario)
- **Accento**: #E67E22 (Arancione per call-to-action)
- **Gradienze**: 
  - Hero: da #1E5A96 via #164575 a #0d2d4d
  - Newsletter: da #2D8659 a #247049
- **Backgrounds**: bianco (#ffffff), grigio chiaro (#f9fafb), grigio scuro (#111827)

### 4. Typography
- Font principale: Inter, -apple-system, BlinkMacSystemFont
- Gerarchia chiara con H1-H4
- Testo leggibile con leading adeguato

### 5. Images
Immagini da Unsplash:
- Radiologia veterinaria: https://images.unsplash.com/photo-1660220617553-95cb021c0a5e
- Medical equipment: https://images.unsplash.com/photo-1657778752500-9da406aa813f
- Doctor portraits (4 diverse immagini)

### 6. Assets Tecnici

#### JavaScript
- File: index-8281de30.js
- React 18.3.1 (minified)
- React DOM
- Vite build system

#### CSS
- File: index-17d8d2a5.css
- Tailwind CSS v4
- Custom colors
- Utility classes complete

### 7. Features UX/UI

#### Animazioni
- Hover effects su card
- Scroll behavior smooth
- Transitions su bottoni e link
- Backdrop blur su header

#### Responsive Design
- Mobile-first approach
- Grid system responsive
- Hidden menu su mobile (da implementare)

#### Accessibilità
- Contrast ratio adeguati
- Tag semantici HTML5
- Aria labels (dove necessario)

## File Statici Creati

Tutti i file sono stati salvati in `/var/www/_bases/base_techplanner_fila5/laravel/Themes/Two/Main_files/`:

1. **index.html** - Versione statica completa del sito target
2. **index-8281de30.js** - JavaScript minificato da target
3. **index-17d8d2a5.css** - CSS Tailwind da target
4. **images/** - Directory con tutte le immagini:
   - radiologia-veterinaria.jpg
   - medical-equipment.jpg
   - dr-roberto-magni.jpg
   - dr-elena-visentin.jpg
   - dr-paolo-verdi.jpg
   - dr-giulia-bianchi.jpg

## Prossimi Passi per Implementazione

### 1. Aggiornare il Layout Principale
- Creare nuovo layout Blade per Theme Two
- Integrare header con navigazione
- Implementare hero section

### 2. Creare Componenti Reutilizzabili
- Card servizio
- Card testimonianza
- Sezione controllo
- Form newsletter

### 3. Integrare Tailwind CSS
- Aggiornare colors nel config Tailwind
- Verificare che tutte le utility siano disponibili

### 4. Gestire le Immagini
- Spostare immagini in public_html/themes/Two/images/
- Aggiornare i path nelle viste Blade

### 5. Implementare JavaScript
- Convertire React a Livewire/Volt se necessario
- O mantenere React se già presente nel tema

### 6. Traduzioni
- Creare file di traduzione italiano
- Preparare struttura per multi-lingua (inglese, tedesco)

### 7. Responsive Testing
- Testare su mobile, tablet, desktop
- Correggere eventuali problemi di layout

### 8. Performance Optimization
- Minify CSS/JS
- Lazy loading immagini
- Implementare caching

## Note Tecniche

### React vs Filament/Livewire
Il sito target usa React 18, ma il progetto TechPlanner usa:
- Filament per admin
- Livewire 3.x per frontend interattivo
- Volt 1.x per componenti reattivi
- Folio per routing

**Decisione**: Convertire il layout React a Blade + Livewire/Volt per mantenere coerenza architetturale.

### Tailwind CSS
Il sito target usa Tailwind CSS v4, che deve essere configurato in:
- `laravel/Themes/Two/tailwind.config.js`
- Assicurarsi che i custom colors siano definiti

### Asset Management
Con Vite:
- Source: `laravel/Themes/Two/resources/`
- Output: `public_html/themes/Two/assets/`
- Comandi: `npm run build`, `npm run copy`

## Checklist Implementazione

- [ ] Creare nuovo layout Blade base
- [ ] Configurare Tailwind colors custom
- [ ] Implementare header con navigazione
- [ ] Creare hero section
- [ ] Implementare sezione servizi
- [ ] Implementare sezione "Perché radioprotezione"
- [ ] Implementare sezione settori
- [ ] Implementare sezione controlli
- [ ] Implementare sezione testimonianze
- [ ] Implementare sezione risorse
- [ ] Implementare newsletter form
- [ ] Implementare footer
- [ ] Test responsive
- [ ] Test accessibilità
- [ ] Performance optimization
- [ ] Build assets con Vite
- [ ] Deploy e test finale

## Riferimenti

- Sito target: https://lightseagreen-dogfish-560272.hostingersite.com/
- Documentazione Tailwind: https://tailwindcss.com/
- Documentazione Livewire: https://livewire.laravel.com/
- Documentazione Volt: https://laravelvolt.com/
- Documentazione Filament: https://filamentphp.com/