# Bootstrap Italia Component Usage Guide
## Advanced Examples and Best Practices for Sixteen Theme

### Overview
This guide provides comprehensive usage examples, performance optimization tips, and best practices for implementing Bootstrap Italia components in the Sixteen theme for Italian public administration websites.

## Performance Optimization

### Lazy Loading Implementation
Components can be lazy-loaded using the `data-lazy-component` attribute for optimal performance:

```html
<!-- Lazy-loaded Carousel -->
<div data-lazy-component="carousel" class="carousel slide" id="example-carousel">
    <div class="carousel-inner">
        <!-- Content will be initialized when visible -->
    </div>
</div>

<!-- Lazy-loaded Timeline -->
<div data-lazy-component="timeline" class="timeline-wrapper">
    <!-- Timeline items -->
</div>
```

### Critical vs Non-Critical Components
- **Critical** (loaded immediately): Skiplinks, Cookiebar, Tooltips
- **Visible** (loaded on DOM ready): Progress indicators, Notifications, Rating
- **Lazy** (loaded when needed): Carousel, Timeline, Accordion, Tabs

## Component Usage Examples

### 1. Hero Component (Advanced)
```php
<x-bootstrap-italia.hero
    title="Advanced Prediction Analytics"
    subtitle="Real-time insights for data-driven decisions"
    variant="primary"
    :buttons="[
        ['text' => 'Get Started', 'url' => '/predict/start', 'style' => 'primary'],
        ['text' => 'Learn More', 'url' => '/predict/about', 'style' => 'outline-primary']
    ]"
    :stats="[
        ['value' => '95%', 'label' => 'Accuracy Rate'],
        ['value' => '10M+', 'label' => 'Predictions Made'],
        ['value' => '500K+', 'label' => 'Active Users']
    ]"
    background-image="/images/hero-bg.jpg"
>
    <!-- Optional slot content -->
    <div class="hero-features mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <svg class="icon icon-lg text-primary mb-3">
                        <use href="#it-chart-line-up"></use>
                    </svg>
                    <h4>Advanced Analytics</h4>
                    <p>Powerful machine learning algorithms</p>
                </div>
            </div>
            <!-- More feature cards -->
        </div>
    </div>
</x-bootstrap-italia.hero>
```

### 2. Progress Indicators (Performance Optimized)
```html
<!-- Progress Donut with lazy initialization -->
<div class="progress-donut" 
     data-percentage="75" 
     data-label="Prediction Accuracy"
     data-lazy-component="progress">
    <div class="progress-donut-inner">
        <span class="progress-donut-value">75%</span>
        <span class="progress-donut-label">Accuracy</span>
    </div>
</div>

<!-- Linear Progress with animation -->
<div class="progress-bar-wrapper" data-lazy-component="progress">
    <div class="progress-bar-label">
        <span>Model Training Progress</span>
        <span class="progress-percentage">85%</span>
    </div>
    <div class="progress" style="height: 8px;">
        <div class="progress-bar bg-success" 
             role="progressbar" 
             style="width: 85%" 
             aria-valuenow="85" 
             aria-valuemin="0" 
             aria-valuemax="100">
        </div>
    </div>
</div>
```

### 3. Interactive Cards with Actions
```php
<x-bootstrap-italia.card
    variant="shadow"
    hover="true"
    :actions="[
        ['icon' => 'it-external-link', 'text' => 'View Details', 'url' => '/prediction/123'],
        ['icon' => 'it-download', 'text' => 'Export', 'action' => 'export-prediction'],
        ['icon' => 'it-share', 'text' => 'Share', 'action' => 'share-prediction']
    ]"
>
    <div class="card-header border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Sales Prediction Model</h5>
            <x-bootstrap-italia.badge variant="success" size="sm">Active</x-bootstrap-italia.badge>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-6">
                <div class="stat-item">
                    <span class="stat-value text-primary">94.2%</span>
                    <span class="stat-label">Accuracy</span>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-item">
                    <span class="stat-value text-success">+12.5%</span>
                    <span class="stat-label">vs Last Month</span>
                </div>
            </div>
        </div>
        
        <div class="progress-wrapper">
            <x-bootstrap-italia.progress 
                value="94" 
                label="Model Performance"
                variant="success"
                size="sm"
            />
        </div>
    </div>
</x-bootstrap-italia.card>
```

### 4. Form Components with Validation
```html
<!-- Advanced Select with Search -->
<div class="bootstrap-select-wrapper">
    <label for="prediction-model">Select Prediction Model</label>
    <select class="form-control" 
            id="prediction-model" 
            data-live-search="true"
            data-lazy-component="select">
        <option value="">Choose a model...</option>
        <optgroup label="Regression Models">
            <option value="linear">Linear Regression</option>
            <option value="polynomial">Polynomial Regression</option>
            <option value="ridge">Ridge Regression</option>
        </optgroup>
        <optgroup label="Classification Models">
            <option value="svm">Support Vector Machine</option>
            <option value="random-forest">Random Forest</option>
            <option value="neural-network">Neural Network</option>
        </optgroup>
    </select>
    <div class="invalid-feedback">Please select a valid prediction model.</div>
</div>

<!-- File Upload with Progress -->
<div class="upload-wrapper">
    <x-bootstrap-italia.upload
        multiple="true"
        accept=".csv,.xlsx,.json"
        max-size="10MB"
        :validation="[
            'required' => true,
            'types' => ['csv', 'xlsx', 'json'],
            'max_files' => 5
        ]"
    >
        <div class="upload-content text-center py-4">
            <svg class="icon icon-xl text-primary mb-3">
                <use href="#it-upload"></use>
            </svg>
            <h6>Upload Training Data</h6>
            <p class="text-muted">Drag and drop files here or click to browse</p>
            <small class="text-muted">Supported formats: CSV, XLSX, JSON (max 10MB each)</small>
        </div>
    </x-bootstrap-italia.upload>
</div>
```

## Accessibility Best Practices

### ARIA Labels and Roles
```html
<!-- Accessible Progress Indicator -->
<div class="progress-donut" 
     role="progressbar"
     aria-valuenow="75" 
     aria-valuemin="0" 
     aria-valuemax="100"
     aria-label="Prediction accuracy: 75%">
    <!-- Content -->
</div>

<!-- Accessible Notification -->
<div class="notification alert alert-success" 
     role="alert" 
     aria-live="polite"
     aria-atomic="true">
    <strong>Success:</strong> Model training completed successfully.
</div>
```

### Keyboard Navigation
```javascript
// Custom keyboard navigation for complex components
document.addEventListener('keydown', function(e) {
    // Handle escape key for modals and dropdowns
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            bootstrap.Modal.getInstance(modal)?.hide();
        });
    }
    
    // Handle arrow keys for carousel navigation
    if (e.target.closest('.carousel')) {
        const carousel = e.target.closest('.carousel');
        const instance = bootstrap.Carousel.getInstance(carousel);
        
        if (e.key === 'ArrowLeft') {
            instance.prev();
        } else if (e.key === 'ArrowRight') {
            instance.next();
        }
    }
});
```

## Performance Monitoring

### Component Performance Metrics
```javascript
// Listen for performance metrics
window.addEventListener('bootstrapItaliaReady', function(e) {
    const metrics = e.detail;
    
    console.log('Performance Metrics:', {
        initTime: metrics.initTime,
        componentsInitialized: metrics.componentsInitialized,
        lazyLoadedComponents: metrics.lazyLoadedComponents
    });
    
    // Send metrics to analytics if needed
    if (typeof gtag !== 'undefined') {
        gtag('event', 'bootstrap_italia_performance', {
            init_time: Math.round(metrics.initTime),
            components_loaded: metrics.componentsInitialized
        });
    }
});
```

### Bundle Size Optimization
- **CSS**: Modular imports reduce bundle size by ~40%
- **JavaScript**: Lazy loading reduces initial load by ~60%
- **Images**: WebP format with fallbacks for IE support
- **Fonts**: Preload critical font weights only

## Security Considerations

### Content Security Policy
```html
<!-- Safe inline styles for dynamic components -->
<div class="progress-bar" 
     style="width: var(--progress-width, 0%)" 
     data-progress="75">
</div>
```

### Data Sanitization
```php
<!-- Always sanitize dynamic content -->
<x-bootstrap-italia.notification
    type="success"
    :message="e($userMessage)"
    :auto-hide="true"
/>
```

---

*This guide is part of the Sixteen theme Bootstrap Italia compliance project. For technical support, refer to the main documentation or create an issue in the project repository.*
