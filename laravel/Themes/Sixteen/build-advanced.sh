#!/bin/bash

# Build avanzato tema Sixteen
# Script per automatizzare il processo di build con ottimizzazioni

set -e  # Exit on error

echo "🚀 Build avanzato tema Sixteen"
echo "================================"

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funzione per log colorato
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Verifica prerequisiti
log_info "Verifica prerequisiti..."

if ! command -v node &> /dev/null; then
    log_error "Node.js non trovato. Installare Node.js 18+"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    log_error "npm non trovato. Installare npm"
    exit 1
fi

NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 18 ]; then
    log_error "Node.js versione $NODE_VERSION trovata. Richiesta versione 18+"
    exit 1
fi

log_success "Prerequisiti verificati"

# Pulizia cache e file temporanei
log_info "Pulizia cache e file temporanei..."
rm -rf node_modules/.vite
rm -rf public/*
rm -rf dist/

log_success "Pulizia completata"

# Installazione dipendenze
log_info "Installazione dipendenze..."
if [ -f "package-lock.json" ]; then
    npm ci
else
    npm install
fi

log_success "Dipendenze installate"

# Linting e formattazione
log_info "Esecuzione linting e formattazione..."
if npm run lint &> /dev/null; then
    log_success "Linting completato"
else
    log_warning "Problemi di linting trovati, tentativo di correzione automatica..."
    npm run lint:fix || log_warning "Impossibile correggere automaticamente tutti i problemi"
fi

# Formattazione codice
npm run format || log_warning "Formattazione non completata"

# Build produzione
log_info "Build produzione..."
npm run build:production

if [ -f "public/manifest.json" ]; then
    log_success "Build produzione completato"
else
    log_error "Build produzione fallito"
    exit 1
fi

# Analisi bundle
log_info "Analisi bundle..."
npm run build:analyze || log_warning "Analisi bundle non disponibile"

# Test build
log_info "Test build..."
if npm run test &> /dev/null; then
    log_success "Test completati"
else
    log_warning "Test non disponibili o falliti"
fi

# Copia asset
log_info "Copia asset in directory finale..."
if npm run copy; then
    log_success "Asset copiati"
else
    log_warning "Impossibile copiare asset automaticamente"
fi

# Verifica finale
log_info "Verifica finale build..."

if [ -f "public/manifest.json" ]; then
    MANIFEST_SIZE=$(wc -c < "public/manifest.json")
    log_success "Manifest generato (${MANIFEST_SIZE} bytes)"
    
    ASSET_COUNT=$(find public/assets -name "*.js" -o -name "*.css" | wc -l)
    log_success "Asset generati: $ASSET_COUNT file"
    
    TOTAL_SIZE=$(du -sh public/ | cut -f1)
    log_success "Dimensione totale build: $TOTAL_SIZE"
    
    echo ""
    echo "🎉 Build completato con successo!"
    echo "📁 Asset disponibili in: public/"
    echo "📄 Manifest: public/manifest.json"
    echo "📊 Dimensione totale: $TOTAL_SIZE"
    echo "🔗 Per visualizzare: npm run preview"
    
else
    log_error "Verifica finale fallita - manifest.json non trovato"
    exit 1
fi

# Informazioni aggiuntive
echo ""
log_info "Informazioni aggiuntive:"
echo "  • Per sviluppo: npm run dev"
echo "  • Per preview: npm run preview"
echo "  • Per analisi bundle: npm run bundle-report"
echo "  • Per watch mode: npm run copy:watch"

log_success "Build avanzato completato!"
