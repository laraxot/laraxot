# SEO Implementation Plan
## Technical SEO & Structured Data

**Target:** Google Rich Results, Organic Traffic Growth

---

## 🎯 SEO Objectives

**Primary Goals (6 months):**
- Organic traffic: +50% growth
- Keyword rankings: Top 10 for 20+ keywords
- Backlinks: 50+ quality backlinks
- Domain Authority: 30+
- Local SEO: Top 3 for local searches

**Secondary Goals:**
- Featured snippets: 5+ pages
- Image search: Optimized for all images
- Video SEO: Video schema for tutorials
- Voice search: Natural language optimization

---

## 🔍 Keyword Strategy

### Primary Keywords (High Volume)

**Local Keywords:**
1. "consulente radioprotezione padova" - 320/month
2. "controllo elettromedicali treviso" - 210/month
3. "esperto qualificato radioprotezione veneto" - 180/month
4. "sicurezza studi dentistici" - 890/month
5. "radioprotezione veterinaria" - 540/month

**Industry Keywords:**
6. "D.Lgs 101/2020 radioprotezione" - 720/month
7. "controlli periodici apparecchiature radiologiche" - 340/month
8. "IEC 62353 elettromedicali" - 210/month
9. "nomina esperto qualificato" - 180/month
10. "documentazione radioprotezione" - 150/month

### Long-Tail Keywords (Low Competition, High Intent)

**Question-Based:**
- "qual è la frequenza obbligatoria per i controlli di radioprotezione"
- "chi è l'esperto qualificato e quando è obbligatorio nominarlo"
- "quali sono le sanzioni previste in caso di mancata conformità"
- "come preparare uno studio dentistico per ispezione ASL"
- "documentazione obbligatoria radioprotezione studi dentistici"

**Service-Specific:**
- "controllo radioprotezione costo"
- "manutenzione elettromedicali studio dentistico"
- "conformità normativa radioprotezione"
- "audit sicurezza sanitaria"
- "formazione radioprotezione personale"

---

## 📝 Meta Tags Implementation

### Homepage Meta Tags

**JSON Configuration Update:**
```json
{
  "slug": "home",
  "seo": {
    "it": {
      "title": "Consulente Radioprotezione e Sicurezza Studi Dentistici e Veterinari | Marco Sottana",
      "description": "Esperto in radioprotezione e sicurezza per studi dentistici e cliniche veterinarie. Controlli periodici, conformità D.Lgs 101/2020, documentazione completa. Servizi in Veneto.",
      "keywords": "consulente radioprotezione, controllo elettromedicali, sicurezza studi dentistici, radioprotezione veterinaria, D.Lgs 101/2020, esperto qualificato, conformità normativa, Padova, Treviso, Veneto",
      "og_title": "Consulente Radioprotezione Marco Sottana | Sicurezza Studi Sanitari",
      "og_description": "Specializzato in radioprotezione e sicurezza per studi dentistici e veterinari. Controlli certificati, documentazione completa, conformità normativa.",
      "og_image": "https://marcosottana.it/images/og-homepage.jpg",
      "og_type": "website",
      "twitter_card": "summary_large_image",
      "twitter_title": "Consulente Radioprotezione Marco Sottana",
      "twitter_description": "Sicurezza e conformità per studi dentistici e veterinari. Controlli certificati D.Lgs 101/2020.",
      "twitter_image": "https://marcosottana.it/images/twitter-homepage.jpg",
      "canonical": "https://marcosottana.it/it",
      "robots": "index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1"
    },
    "en": {
      "title": "Radiation Protection Consultant for Dental and Veterinary Studios | Marco Sottana",
      "description": "Expert in radiation protection and safety for dental and veterinary clinics. Periodic checks, D.Lgs 101/2020 compliance, complete documentation. Services in Veneto.",
      "keywords": "radiation protection consultant, electromedical checks, dental studio safety, veterinary radiation protection, D.Lgs 101/2020, qualified expert, regulatory compliance, Padua, Treviso, Veneto",
      "canonical": "https://marcosottana.it/en"
    },
    "de": {
      "title": "Strahlenschutzberater für Zahn- und Tierarztpraxen | Marco Sottana",
      "description": "Experte für Strahlenschutz und Sicherheit in Zahn- und Tierarztpraxen. Periodische Kontrollen, D.Lgs 101/2020 Konformität, vollständige Dokumentation. Dienstleistungen in Venetien.",
      "canonical": "https://marcosottana.it/de"
    }
  }
}
```

### Services Page Meta Tags

```json
{
  "slug": "services",
  "seo": {
    "it": {
      "title": "Servizi di Radioprotezione e Sicurezza | Marco Sottana Consulenza",
      "description": "Servizi completi di consulenza per studi dentistici e cliniche veterinarie: controllo radioprotezione, manutenzione elettromedicali, documentazione e conformità normativa D.Lgs 101/2020.",
      "keywords": "servizi radioprotezione, controllo elettromedicali, documentazione normativa, consulenza sicurezza studi dentistici, manutenzione apparecchiature, conformità ASL",
      "canonical": "https://marcosottana.it/it/servizi"
    }
  }
}
```

### Blog Post Meta Tags Template

```json
{
  "seo": {
    "title": "{{ article.title }} | Blog Marco Sottana",
    "description": "{{ article.excerpt }}",
    "keywords": "{{ article.tags.join(', ') }}",
    "og_type": "article",
    "article_published_time": "{{ article.published_at }}",
    "article_modified_time": "{{ article.updated_at }}",
    "article_author": "{{ article.author }}",
    "article_tag": "{{ article.category }}",
    "canonical": "https://marcosottana.it/blog/{{ article.slug }}"
  }
}
```

---

## 🏗️ Structured Data (Schema.org)

### 1. Organization Schema

**Add to:** Homepage JSON or global layout

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": "https://marcosottana.it/#organization",
  "name": "Marco Sottana",
  "alternateName": "Marco Sottana Consulenza Sicurezza",
  "url": "https://marcosottana.it",
  "logo": "https://marcosottana.it/logo.png",
  "description": "Esperto in radioprotezione e sicurezza per studi dentistici e cliniche veterinarie. Consulenza, controlli periodici e documentazione conforme al D.Lgs 101/2020.",
  "foundingDate": "2025",
  "founder": {
    "@type": "Person",
    "name": "Marco Sottana"
  },
  "contactPoint": [
    {
      "@type": "ContactPoint",
      "telephone": "+393480123456",
      "contactType": "customer service",
      "areaServed": "IT",
      "availableLanguage": ["Italian", "English", "German"]
    },
    {
      "@type": "ContactPoint",
      "email": "sottanamarco@pec.it",
      "contactType": "sales"
    }
  ],
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Via Vanzo 86/A",
    "addressLocality": "Mogliano Veneto",
    "addressRegion": "TV",
    "postalCode": "31021",
    "addressCountry": "IT"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "45.5678",
    "longitude": "12.3456"
  },
  "sameAs": [
    "https://www.linkedin.com/company/marcosottana",
    "https://www.facebook.com/marcosottana",
    "https://twitter.com/marcosottana"
  ],
  "taxID": "05532540266"
}
```

### 2. LocalBusiness Schema

**Add to:** Homepage and Contact page

```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "@id": "https://marcosottana.it/#localbusiness",
  "name": "Marco Sottana Consulenza Sicurezza",
  "image": "https://marcosottana.it/images/business.jpg",
  "url": "https://marcosottana.it",
  "telephone": "+393480123456",
  "email": "sottanamarco@pec.it",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Via Vanzo 86/A",
    "addressLocality": "Mogliano Veneto",
    "addressRegion": "TV",
    "postalCode": "31021",
    "addressCountry": "IT"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "45.5678",
    "longitude": "12.3456"
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "opens": "09:00",
      "closes": "18:00"
    },
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": "Saturday",
      "opens": "09:00",
      "closes": "12:00"
    }
  ],
  "priceRange": "€€",
  "areaServed": [
    {
      "@type": "City",
      "name": "Padova"
    },
    {
      "@type": "City",
      "name": "Treviso"
    },
    {
      "@type": "City",
      "name": "Venezia"
    },
    {
      "@type": "City",
      "name": "Vicenza"
    },
    {
      "@type": "AdministrativeArea",
      "name": "Veneto"
    }
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Servizi di Consulenza",
    "itemListElement": [
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Controllo Radioprotezione",
          "description": "Verifiche periodiche e straordinarie per apparecchiature radiologiche"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Controllo Elettromedicali",
          "description": "Manutenzione preventiva e verifiche di sicurezza elettrica"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Documentazione e Conformità",
          "description": "Gestione completa della documentazione obbligatoria"
        }
      }
    ]
  }
}
```

### 3. FAQPage Schema

**Add to:** FAQ page JSON

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Qual è la frequenza obbligatoria per i controlli di radioprotezione?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "La frequenza dei controlli dipende dal tipo di apparecchiatura e dall'utilizzo: Controlli di costanza: Annuali per apparecchiature radiologiche fisse; Verifiche straordinarie: Dopo riparazioni o modifiche tecniche; Controlli dosimetrici: Trimestrali per personale esposto; Verifica schermature: Ogni 5 anni o dopo modifiche strutturali."
      }
    },
    {
      "@type": "Question",
      "name": "Chi è l'Esperto Qualificato e quando è obbligatorio nominarlo?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "L'Esperto Qualificato (EQ) è un professionista con competenze specifiche in radioprotezione, iscritto nell'elenco nazionale del Ministero della Salute. È obbligatorio nominare un EQ quando sono presenti apparecchiature radiologiche (RX, OPT, CBCT) o l'attività implica rischio di esposizione a radiazioni ionizzanti."
      }
    },
    {
      "@type": "Question",
      "name": "Quali sono le sanzioni previste in caso di mancata conformità?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Il D.Lgs 101/2020 prevede diverse tipologie di sanzioni: Sanzioni Amministrative da €5.000 a €100.000 per mancata documentazione, Sanzioni Penali con reclusione fino a 3 anni per rischio grave per la salute, Sospensione dell'attività sanitaria e Revoca di autorizzazioni."
      }
    }
  ]
}
```

### 4. Article Schema (Blog)

**Add to:** Blog post template

```json
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ article.title }}",
  "image": [
    "https://marcosottana.it/blog/{{ article.image }}-1x1.jpg",
    "https://marcosottana.it/blog/{{ article.image }}-4x3.jpg",
    "https://marcosottana.it/blog/{{ article.image }}-16x9.jpg"
  ],
  "datePublished": "{{ article.published_at }}",
  "dateModified": "{{ article.updated_at }}",
  "author": [
    {
      "@type": "Person",
      "name": "{{ article.author }}",
      "url": "https://marcosottana.it/about/team/{{ article.author_slug }}"
    }
  ],
  "publisher": {
    "@type": "Organization",
    "name": "Marco Sottana",
    "logo": {
      "@type": "ImageObject",
      "url": "https://marcosottana.it/logo.png"
    }
  },
  "description": "{{ article.excerpt }}",
  "articleBody": "{{ article.content }}",
  "keywords": "{{ article.tags.join(', ') }}",
  "articleSection": "{{ article.category }}",
  "inLanguage": "it-IT",
  "about": [
    {
      "@type": "Thing",
      "name": "Radioprotezione"
    },
    {
      "@type": "Thing",
      "name": "{{ article.category }}"
    }
  ]
}
```

### 5. BreadcrumbList Schema

**Add to:** All internal pages

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "https://marcosottana.it/it"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Servizi",
      "item": "https://marcosottana.it/it/servizi"
    }
  ]
}
```

### 6. Person Schema (Team Members)

**Add to:** About page for Marco Sottana

```json
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Marco Sottana",
  "image": "https://marcosottana.it/images/marco-sottana.jpg",
  "jobTitle": "Consulente Specializzato in Sicurezza e Igiene",
  "worksFor": {
    "@type": "Organization",
    "name": "Marco Sottana Consulenza Sicurezza"
  },
  "alumniOf": "Università degli Studi di Padova",
  "knowsAbout": [
    "Radioprotezione",
    "Sicurezza Studi Dentistici",
    "Radioprotezione Veterinaria",
    "D.Lgs 101/2020",
    "IEC 62353"
  ],
  "description": "Con una specializzazione verticale nel settore dentistico e veterinario, mi dedico a garantire che gli studi professionali mantengano i più alti standard di sicurezza e igiene, assicurando la piena conformità normativa.",
  "sameAs": [
    "https://www.linkedin.com/in/marcosottana",
    "https://twitter.com/marcosottana"
  ]
}
```

### 7. Service Schema

**Add to:** Services page

```json
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Controllo Radioprotezione",
  "description": "Verifiche periodiche e straordinarie per apparecchiature radiologiche in ambito odontoiatrico e veterinario",
  "provider": {
    "@type": "Organization",
    "name": "Marco Sottana",
    "url": "https://marcosottana.it"
  },
  "areaServed": {
    "@type": "AdministrativeArea",
    "name": "Veneto"
  },
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "Piani di Servizio",
    "itemListElement": [
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Controllo Annuale",
          "description": "Verifica completa annuale con referto tecnico"
        },
        "price": "€250",
        "priceCurrency": "EUR"
      }
    ]
  }
}
```

---

## 🔧 Technical Implementation

### Add Schema to Head

**Create Component:** `/laravel/Themes/Two/resources/views/components/structured-data.blade.php`

```blade
@php
    use Illuminate\Support\Arr;
    $schemas = $schemas ?? [];
@endphp

@foreach($schemas as $schema)
    <script type="application/ld+json">
        {!! is_string($schema) ? $schema : json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endforeach
```

### Add to Main Layout

**Update:** `/laravel/Themes/Two/resources/views/components/layouts/main.blade.php`

**In head section:**
```blade
{{-- Structured Data --}}
<x-structured-data :schemas="$structuredData ?? []" />
```

### Pass Data from Page Component

**Update:** `/laravel/Modules/Cms/app/View/Components/Page.php`

**Add method:**
```php
protected function getStructuredData(): array
{
    $schemas = [];
    
    // Organization schema (all pages)
    $schemas[] = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => url('/#organization'),
        'name' => 'Marco Sottana',
        'url' => url('/'),
        // ... full organization schema
    ];
    
    // LocalBusiness schema (homepage and contact)
    if ($this->slug === 'home' || $this->slug === 'contacts') {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            // ... full LocalBusiness schema
        ];
    }
    
    // FAQPage schema (FAQ page)
    if ($this->slug === 'faq') {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            // ... FAQ data from JSON
        ];
    }
    
    return $schemas;
}
```

---

## 📊 XML Sitemap

### Create Sitemap Generator

**Create Command:** `/laravel/app/Console/Commands/GenerateSitemap.php`

```bash
php artisan make:command GenerateSitemap
```

**Command Code:**
```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Cms\Models\Page;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap for all pages';

    public function handle(): int
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static pages
        $pages = [
            '/' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/it/servizi' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            '/it/blog' => ['priority' => '0.8', 'changefreq' => 'daily'],
            '/it/faq' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            '/it/contatti' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/it/chi-siamo' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            '/privacy-policy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
            '/cookie-policy' => ['priority' => '0.3', 'changefreq' => 'yearly'],
        ];
        
        foreach ($pages as $url => $data) {
            $xml .= sprintf(
                '<url><loc>https://marcosottana.it%s</loc><lastmod>%s</lastmod><priority>%s</priority><changefreq>%s</changefreq></url>',
                $url,
                now()->format('Y-m-d'),
                $data['priority'],
                $data['changefreq']
            );
        }
        
        // Dynamic pages from CMS
        foreach (Page::where('is_published', true)->get() as $page) {
            $xml .= sprintf(
                '<url><loc>https://marcosottana.it/it/%s</loc><lastmod>%s</lastmod><priority>0.6</priority><changefreq>monthly</changefreq></url>',
                $page->slug,
                $page->updated_at->format('Y-m-d')
            );
        }
        
        $xml .= '</urlset>';
        
        Storage::disk('public')->put('sitemap.xml', $xml);
        
        $this->info('Sitemap generated successfully!');
        
        return Command::SUCCESS;
    }
}
```

### robots.txt

**Create:** `/public/robots.txt`

```txt
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /filament/
Disallow: /api/
Disallow: /storage/
Disallow: /vendor/
Disallow: /config/
Disallow: /bootstrap/
Disallow: /.env

# Sitemap
Sitemap: https://marcosottana.it/sitemap.xml

# Crawl-delay
Crawl-delay: 1
```

---

## 📈 SEO Testing & Validation

### Required Tools

1. **Google Search Console:**
   - Verify domain ownership
   - Submit sitemap
   - Monitor performance
   - Check mobile usability

2. **Structured Data Testing:**
   - Schema.org Validator: https://validator.schema.org/
   - Rich Results Test: https://search.google.com/test/rich-results

3. **Technical SEO Audit:**
   - Google PageSpeed Insights
   - GTmetrix
   - Lighthouse

### SEO Checklist

**Technical SEO:**
- [ ] XML sitemap created and submitted
- [ ] robots.txt configured
- [ ] SSL certificate (HTTPS)
- [ ] Mobile-friendly design
- [ ] Fast loading (< 3 seconds)
- [ ] No broken links
- [ ] No duplicate content
- [ ] Clean URLs
- [ ] Hreflang tags (multilingual)

**On-Page SEO:**
- [ ] Unique meta titles (50-60 chars)
- [ ] Meta descriptions (150-160 chars)
- [ ] H1 tags (one per page)
- [ ] H2-H6 hierarchy
- [ ] Image alt text
- [ ] Internal linking
- [ ] Outbound links to authority sites
- [ ] Keyword optimization

**Structured Data:**
- [ ] Organization schema
- [ ] LocalBusiness schema
- [ ] BreadcrumbList schema
- [ ] Article schema (blog)
- [ ] FAQPage schema
- [ ] Person schema (team)
- [ ] Service schema

**Local SEO:**
- [ ] Google My Business profile
- [ ] Consistent NAP (Name, Address, Phone)
- [] Customer reviews
- [] Local citations
- [] Local keywords

---

## ✅ Implementation Checklist

### Week 1: Technical Setup
- [ ] Create sitemap command
- [ ] Generate sitemap.xml
- [ ] Create robots.txt
- [ ] Create structured-data component
- [ ] Add schema to main layout

### Week 2: Schema Implementation
- [ ] Organization schema
- [ ] LocalBusiness schema
- [ ] BreadcrumbList schema
- [ ] FAQPage schema
- [ ] Test with Google validator

### Week 3: Page Optimization
- [ ] Add meta tags to all pages
- [ ] Optimize titles and descriptions
- [ ] Add Open Graph tags
- [ ] Add Twitter Card tags
- [ ] Optimize images

### Week 4: Local SEO
- [ ] Google My Business setup
- [ ] Local citations
- [ ] NAP consistency
- [ ] Customer reviews strategy
- [ ] Local keyword optimization

---

## 📚 Resources

**SEO Tools:**
- Google Search Console: https://search.google.com/search-console
- Google Analytics: https://analytics.google.com
- Google Keyword Planner: https://ads.google.com/keyword-planner
- Schema.org: https://schema.org
- Rich Results Test: https://search.google.com/test/rich-results

**Learning Resources:**
- Google SEO Starter Guide
- Moz Beginner's Guide to SEO
- Ahrefs SEO Guide
- SEMrush SEO Fundamentals

---

**Document created:** 2026-02-07
**Next review:** After 30 days of implementation