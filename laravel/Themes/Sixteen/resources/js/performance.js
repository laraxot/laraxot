/**
 * Performance Manager
 * 
 * Gestisce l'ottimizzazione delle performance,
 * il lazy loading e il monitoring delle metriche.
 */

class PerformanceManager {
    constructor() {
        this.metrics = {
            fcp: null, // First Contentful Paint
            lcp: null, // Largest Contentful Paint
            fid: null, // First Input Delay
            cls: null, // Cumulative Layout Shift
            ttfb: null, // Time to First Byte
            loadTime: null
        };
        
        this.observers = new Map();
        this.lazyImages = new Set();
        this.intersectionObserver = null;
        
        this.init();
    }
    
    /**
     * Inizializza il Performance Manager
     */
    init() {
        this.setupPerformanceObserver();
        this.setupLazyLoading();
        this.setupResourceHints();
        this.setupCriticalCSS();
        this.monitorCoreWebVitals();
    }
    
    /**
     * Configura l'osservatore delle performance
     */
    setupPerformanceObserver() {
        if ('PerformanceObserver' in window) {
            // Osserva le metriche di navigazione
            const navObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    this.handleNavigationEntry(entry);
                });
            });
            
            try {
                navObserver.observe({ entryTypes: ['navigation'] });
            } catch (e) {
                console.warn('[Performance] Navigation observer not supported');
            }
            
            // Osserva le metriche di paint
            const paintObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    this.handlePaintEntry(entry);
                });
            });
            
            try {
                paintObserver.observe({ entryTypes: ['paint'] });
            } catch (e) {
                console.warn('[Performance] Paint observer not supported');
            }
        }
    }
    
    /**
     * Configura il lazy loading
     */
    setupLazyLoading() {
        // Lazy loading per immagini
        this.setupLazyImages();
        
        // Lazy loading per componenti
        this.setupLazyComponents();
        
        // Lazy loading per script
        this.setupLazyScripts();
    }
    
    /**
     * Configura lazy loading per immagini
     */
    setupLazyImages() {
        if ('IntersectionObserver' in window) {
            this.intersectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            // Trova tutte le immagini lazy
            document.querySelectorAll('img[data-src]').forEach(img => {
                this.lazyImages.add(img);
                this.intersectionObserver.observe(img);
            });
        } else {
            // Fallback: carica tutte le immagini
            document.querySelectorAll('img[data-src]').forEach(img => {
                this.loadImage(img);
            });
        }
    }
    
    /**
     * Carica un'immagine lazy
     */
    loadImage(img) {
        if (img.dataset.src) {
            img.src = img.dataset.src;
            img.classList.add('loaded');
            
            // Rimuovi dall'osservatore
            if (this.intersectionObserver) {
                this.intersectionObserver.unobserve(img);
            }
            
            this.lazyImages.delete(img);
        }
    }
    
    /**
     * Configura lazy loading per componenti
     */
    setupLazyComponents() {
        // Lazy loading per componenti Alpine.js
        document.querySelectorAll('[x-data-lazy]').forEach(element => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadLazyComponent(entry.target);
                    }
                });
            });
            
            observer.observe(element);
        });
    }
    
    /**
     * Carica un componente lazy
     */
    loadLazyComponent(element) {
        const componentName = element.dataset.component;
        const componentPath = element.dataset.path;
        
        if (componentName && componentPath) {
            import(componentPath)
                .then(module => {
                    // Inizializza il componente
                    if (module.default) {
                        module.default(element);
                    }
                    
                    element.classList.add('loaded');
                })
                .catch(error => {
                    console.error('[Performance] Failed to load component:', error);
                });
        }
    }
    
    /**
     * Configura lazy loading per script
     */
    setupLazyScripts() {
        document.querySelectorAll('script[data-src]').forEach(script => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadScript(entry.target);
                    }
                });
            });
            
            observer.observe(script);
        });
    }
    
    /**
     * Carica uno script lazy
     */
    loadScript(script) {
        if (script.dataset.src) {
            const newScript = document.createElement('script');
            newScript.src = script.dataset.src;
            newScript.async = true;
            
            if (script.dataset.defer) {
                newScript.defer = true;
            }
            
            script.parentNode.insertBefore(newScript, script);
            script.remove();
        }
    }
    
    /**
     * Configura resource hints
     */
    setupResourceHints() {
        // Preload risorse critiche
        this.preloadCriticalResources();
        
        // Prefetch risorse future
        this.prefetchFutureResources();
        
        // DNS prefetch per domini esterni
        this.dnsPrefetchExternalDomains();
    }
    
    /**
     * Preload risorse critiche
     */
    preloadCriticalResources() {
        const criticalResources = [
            { href: '/css/app.css', as: 'style' },
            { href: '/js/app.js', as: 'script' },
            { href: '/fonts/titillium-web.woff2', as: 'font', type: 'font/woff2', crossorigin: 'anonymous' }
        ];
        
        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource.href;
            link.as = resource.as;
            
            if (resource.type) {
                link.type = resource.type;
            }
            
            if (resource.crossorigin) {
                link.crossOrigin = resource.crossorigin;
            }
            
            document.head.appendChild(link);
        });
    }
    
    /**
     * Prefetch risorse future
     */
    prefetchFutureResources() {
        // Prefetch pagine probabili
        const probablePages = ['/tickets', '/profile', '/settings'];
        
        probablePages.forEach(page => {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = page;
            document.head.appendChild(link);
        });
    }
    
    /**
     * DNS prefetch per domini esterni
     */
    dnsPrefetchExternalDomains() {
        const externalDomains = [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://cdn.jsdelivr.net'
        ];
        
        externalDomains.forEach(domain => {
            const link = document.createElement('link');
            link.rel = 'dns-prefetch';
            link.href = domain;
            document.head.appendChild(link);
        });
    }
    
    /**
     * Configura CSS critico
     */
    setupCriticalCSS() {
        // Inline critical CSS
        const criticalCSS = `
            body { font-family: 'Titillium Web', sans-serif; }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }
            .btn { display: inline-block; padding: 0.375rem 0.75rem; }
            .navbar { background: #0066cc; color: white; }
        `;
        
        const style = document.createElement('style');
        style.textContent = criticalCSS;
        document.head.insertBefore(style, document.head.firstChild);
    }
    
    /**
     * Monitora Core Web Vitals
     */
    monitorCoreWebVitals() {
        // First Contentful Paint
        this.measureFCP();
        
        // Largest Contentful Paint
        this.measureLCP();
        
        // First Input Delay
        this.measureFID();
        
        // Cumulative Layout Shift
        this.measureCLS();
    }
    
    /**
     * Misura First Contentful Paint
     */
    measureFCP() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    if (entry.name === 'first-contentful-paint') {
                        this.metrics.fcp = entry.startTime;
                        this.reportMetric('FCP', this.metrics.fcp);
                    }
                });
            });
            
            try {
                observer.observe({ entryTypes: ['paint'] });
            } catch (e) {
                console.warn('[Performance] FCP measurement not supported');
            }
        }
    }
    
    /**
     * Misura Largest Contentful Paint
     */
    measureLCP() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.metrics.lcp = lastEntry.startTime;
                this.reportMetric('LCP', this.metrics.lcp);
            });
            
            try {
                observer.observe({ entryTypes: ['largest-contentful-paint'] });
            } catch (e) {
                console.warn('[Performance] LCP measurement not supported');
            }
        }
    }
    
    /**
     * Misura First Input Delay
     */
    measureFID() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    this.metrics.fid = entry.processingStart - entry.startTime;
                    this.reportMetric('FID', this.metrics.fid);
                });
            });
            
            try {
                observer.observe({ entryTypes: ['first-input'] });
            } catch (e) {
                console.warn('[Performance] FID measurement not supported');
            }
        }
    }
    
    /**
     * Misura Cumulative Layout Shift
     */
    measureCLS() {
        if ('PerformanceObserver' in window) {
            let clsValue = 0;
            
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                });
                
                this.metrics.cls = clsValue;
                this.reportMetric('CLS', this.metrics.cls);
            });
            
            try {
                observer.observe({ entryTypes: ['layout-shift'] });
            } catch (e) {
                console.warn('[Performance] CLS measurement not supported');
            }
        }
    }
    
    /**
     * Gestisci entry di navigazione
     */
    handleNavigationEntry(entry) {
        if (entry.entryType === 'navigation') {
            this.metrics.ttfb = entry.responseStart - entry.requestStart;
            this.metrics.loadTime = entry.loadEventEnd - entry.navigationStart;
            
            this.reportMetric('TTFB', this.metrics.ttfb);
            this.reportMetric('Load Time', this.metrics.loadTime);
        }
    }
    
    /**
     * Gestisci entry di paint
     */
    handlePaintEntry(entry) {
        if (entry.name === 'first-contentful-paint') {
            this.metrics.fcp = entry.startTime;
            this.reportMetric('FCP', this.metrics.fcp);
        }
    }
    
    /**
     * Reporta una metrica
     */
    reportMetric(name, value) {
        console.log(`[Performance] ${name}: ${value.toFixed(2)}ms`);
        
        // Invia a analytics se disponibile
        if (window.gtag) {
            gtag('event', 'web_vitals', {
                name: name,
                value: Math.round(value),
                event_category: 'Performance'
            });
        }
    }
    
    /**
     * Ottieni tutte le metriche
     */
    getMetrics() {
        return { ...this.metrics };
    }
    
    /**
     * Ottieni score delle performance
     */
    getPerformanceScore() {
        const scores = {
            fcp: this.getFCPScore(this.metrics.fcp),
            lcp: this.getLCPScore(this.metrics.lcp),
            fid: this.getFIDScore(this.metrics.fid),
            cls: this.getCLSScore(this.metrics.cls)
        };
        
        const averageScore = Object.values(scores).reduce((sum, score) => sum + score, 0) / Object.keys(scores).length;
        
        return {
            scores,
            average: Math.round(averageScore),
            grade: this.getGrade(averageScore)
        };
    }
    
    /**
     * Score per FCP
     */
    getFCPScore(fcp) {
        if (fcp <= 1800) return 100;
        if (fcp <= 3000) return 75;
        return 50;
    }
    
    /**
     * Score per LCP
     */
    getLCPScore(lcp) {
        if (lcp <= 2500) return 100;
        if (lcp <= 4000) return 75;
        return 50;
    }
    
    /**
     * Score per FID
     */
    getFIDScore(fid) {
        if (fid <= 100) return 100;
        if (fid <= 300) return 75;
        return 50;
    }
    
    /**
     * Score per CLS
     */
    getCLSScore(cls) {
        if (cls <= 0.1) return 100;
        if (cls <= 0.25) return 75;
        return 50;
    }
    
    /**
     * Ottieni grade dalle performance
     */
    getGrade(score) {
        if (score >= 90) return 'A';
        if (score >= 80) return 'B';
        if (score >= 70) return 'C';
        if (score >= 60) return 'D';
        return 'F';
    }
}

// Inizializza Performance Manager quando il DOM è pronto
document.addEventListener('DOMContentLoaded', () => {
    window.performanceManager = new PerformanceManager();
});

// Esporta per uso globale
window.PerformanceManager = PerformanceManager;









