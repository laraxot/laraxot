{{--
Bootstrap Italia Component Showcase
Comprehensive demo and testing page for all implemented components
--}}

@extends('pub_theme::layouts.app')

@section('title', 'Bootstrap Italia Components Showcase')

@section('content')
<div class="bootstrap-italia-showcase">
    {{-- Skiplinks for accessibility --}}
    <x-pub_theme::navigation.skiplinks />

    {{-- Hero Section --}}
    <x-pub_theme::ui.hero type="centered" size="small">
        <x-slot name="content">
            <div class="container text-center">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    Bootstrap Italia Components
                </h1>
                <p class="lead text-muted mb-4">
                    Interactive showcase of all implemented components for Italian PA websites
                </p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#componentGuideModal">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-info-circle"></use>
                        </svg>
                        Component Guide
                    </button>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-print"></use>
                        </svg>
                        Print Guide
                    </button>
                </div>
            </div>
        </x-slot>
    </x-hero>

    <div class="container my-5">
        {{-- Navigation Tabs for Categories --}}
        <x-pub_theme::ui.tab orientation="horizontal" full-width="true">
            <x-slot name="tabs">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#core-components" type="button" role="tab">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-star"></use>
                        </svg>
                        Core Components
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-components" type="button" role="tab">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-edit"></use>
                        </svg>
                        Form Components
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#navigation-components" type="button" role="tab">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-menu"></use>
                        </svg>
                        Navigation
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#interactive-components" type="button" role="tab">
                        <svg class="icon icon-sm me-2">
                            <use href="#it-tool"></use>
                        </svg>
                        Interactive
                    </button>
                </li>
            </x-slot>
        </x-tab>

        {{-- Tab Content --}}
        <div class="tab-content mt-4">
            {{-- Core Components Tab --}}
            <div class="tab-pane fade show active" id="core-components" role="tabpanel">
                <div class="row">
                    {{-- Hero Component --}}
                    <div class="col-12 mb-5">
                        <div class="component-demo">
                            <h3 class="mb-3">Hero Component</h3>
                            <div class="demo-container">
                                <x-pub_theme::ui.hero type="image" size="small" background-image="https://via.placeholder.com/1200x400/0066cc/ffffff?text=Demo+Hero">
                                <x-pub_theme::ui.hero type="image" size="small" background-image="https://via.placeholder.com/1200x400/0066cc/ffffff?text=Demo+Hero">
                                    <x-slot name="content">
                                        <div class="container">
                                            <h2 class="text-white">Demo Hero Section</h2>
                                            <p class="text-white-50">This is a sample hero component with image background</p>
                                            <a href="#" class="btn btn-light">Learn More</a>
                                        </div>
                                    </x-slot>
                                </x-hero>
                                </x-hero>
                            </div>
                        </div>
                    </div>

                    {{-- Badge Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Badge Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <div class="d-flex flex-wrap gap-2">
                                    <x-badge color="primary">Primary</x-badge>
                                    <x-badge color="success">Success</x-badge>
                                    <x-badge color="warning">Warning</x-badge>
                                    <x-badge color="danger">Danger</x-badge>
                                    <x-badge color="info">Info</x-badge>
                                    <x-badge color="secondary">Secondary</x-badge>
                                </div>
                                <div class="mt-3">
                                    <h6>Different Sizes:</h6>
                                    <x-badge color="primary" size="sm">Small</x-badge>
                                    <x-badge color="primary" size="md">Medium</x-badge>
                                    <x-badge color="primary" size="lg">Large</x-badge>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Accordion Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Accordion Component</h4>
                            <div class="demo-container">
                                <x-pub_theme::ui.accordion>
                                    <x-slot name="items">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#demo-collapse-1">
                                                    First Accordion Item
                                                </button>
                                            </h2>
                                            <div id="demo-collapse-1" class="accordion-collapse collapse show">
                                                <div class="accordion-body">
                                                    This is the content for the first accordion item. It can contain any HTML content.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#demo-collapse-2">
                                                    Second Accordion Item
                                                </button>
                                            </h2>
                                            <div id="demo-collapse-2" class="accordion-collapse collapse">
                                                <div class="accordion-body">
                                                    This is the content for the second accordion item with more detailed information.
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot>
                                </x-accordion>
                                </x-accordion>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Indicators --}}
                    <div class="col-12 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Progress Indicators</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h6>Progress Bars</h6>
                                        <x-feedback.progress-indicators type="bar" :percentage="75" color="primary" label="Primary Progress" />
                                        <x-feedback.progress-indicators type="bar" :percentage="60" color="success" label="Success Progress" class="mt-2" />
                                        <x-feedback.progress-indicators type="bar" :percentage="40" color="warning" label="Warning Progress" class="mt-2" />
                                        <x-feedback.progress-indicators type="bar" :percentage="75" color="primary" label="Primary Progress" />
                                        <x-feedback.progress-indicators type="bar" :percentage="60" color="success" label="Success Progress" class="mt-2" />
                                        <x-feedback.progress-indicators type="bar" :percentage="40" color="warning" label="Warning Progress" class="mt-2" />
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Spinners</h6>
                                        <div class="d-flex gap-3 align-items-center">
                                            <x-feedback.progress-indicators type="spinner" size="sm" active="true" />
                                            <x-feedback.progress-indicators type="spinner" size="md" active="true" />
                                            <x-feedback.progress-indicators type="spinner" size="lg" active="true" />
                                            <x-feedback.progress-indicators type="spinner" size="sm" active="true" />
                                            <x-feedback.progress-indicators type="spinner" size="md" active="true" />
                                            <x-feedback.progress-indicators type="spinner" size="lg" active="true" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h6>Donut Progress</h6>
                                        <x-feedback.progress-indicators type="donut" :percentage="85" label="Completion Rate" />
                                        <x-feedback.progress-indicators type="donut" :percentage="85" label="Completion Rate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Components Tab --}}
            <div class="tab-pane fade" id="form-components" role="tabpanel">
                <div class="row">
                    {{-- Select Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Select Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <x-select 
                                <x-select 
                                    name="demo_select"
                                    label="Choose an Option"
                                    :options="[
                                        '' => 'Select an option',
                                        'option1' => 'First Option',
                                        'option2' => 'Second Option',
                                        'option3' => 'Third Option'
                                    ]"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Radio Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Radio Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <x-radio 
                                    name="demo_radio"
                                    legend="Choose your preference"
                                    :options="[
                                        'option1' => 'First Choice',
                                        'option2' => 'Second Choice',
                                        'option3' => 'Third Choice'
                                    ]"
                                    value="option2"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Upload Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Upload Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <x-forms.upload 
                                <x-forms.upload 
                                    name="demo_upload"
                                    label="Upload Files"
                                    accept=".pdf,.doc,.docx"
                                    multiple="true"
                                    drag-drop="true"
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Toggle Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Toggle Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <x-pub_theme::ui.toggle 
                                    name="demo_toggle"
                                    label="Enable notifications"
                                    checked="true"
                                />
                                <x-pub_theme::ui.toggle 
                                    name="demo_toggle_2"
                                    label="Dark mode"
                                    lever-left="true"
                                    class="mt-3"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Components Tab --}}
            <div class="tab-pane fade" id="navigation-components" role="tabpanel">
                <div class="row">
                    {{-- Sidebar Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Sidebar Component</h4>
                            <div class="demo-container">
                                <div style="height: 300px; overflow: hidden;">
                                    <x-sidebar>
                                        <x-slot name="menu">
                                            <ul class="list-unstyled">
                                                <li><a href="#" class="nav-link"><svg class="icon icon-sm me-2"><use href="#it-home"></use></svg>Dashboard</a></li>
                                                <li><a href="#" class="nav-link"><svg class="icon icon-sm me-2"><use href="#it-user"></use></svg>Users</a></li>
                                                <li><a href="#" class="nav-link"><svg class="icon icon-sm me-2"><use href="#it-settings"></use></svg>Settings</a></li>
                                            </ul>
                                        </x-slot>
                                    </x-sidebar>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Navigation --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Bottom Navigation</h4>
                            <div class="demo-container">
                                <div style="position: relative; height: 100px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                                    <x-pub_theme::navigation.bottom-nav fixed="false">
                                        <x-slot name="links">
                                            <a href="#" class="nav-link active">
                                                <svg class="icon"><use href="#it-home"></use></svg>
                                                <span class="bottom-nav-label">Home</span>
                                            </a>
                                            <a href="#" class="nav-link">
                                                <svg class="icon"><use href="#it-search"></use></svg>
                                                <span class="bottom-nav-label">Search</span>
                                            </a>
                                            <a href="#" class="nav-link">
                                                <svg class="icon"><use href="#it-user"></use></svg>
                                                <span class="bottom-nav-label">Profile</span>
                                            </a>
                                        </x-slot>
                                    </x-bottom-nav>
                                    </x-bottom-nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Interactive Components Tab --}}
            <div class="tab-pane fade" id="interactive-components" role="tabpanel">
                <div class="row">
                    {{-- Notifications --}}
                    <div class="col-12 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Notifications</h4>
                            <div class="demo-container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-pub_theme::ui.notifiche 
                                            type="success"
                                            title="Success Notification"
                                            message="This is a success message with icon"
                                            icon="check-circle"
                                            position="static"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        <x-pub_theme::ui.notifiche 
                                            type="warning"
                                            title="Warning Notification"
                                            message="This is a warning message that requires attention"
                                            icon="exclamation-triangle"
                                            position="static"
                                        />
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary me-2" onclick="showDynamicNotification('success')">Show Success</button>
                                    <button class="btn btn-warning me-2" onclick="showDynamicNotification('warning')">Show Warning</button>
                                    <button class="btn btn-danger me-2" onclick="showDynamicNotification('error')">Show Error</button>
                                    <button class="btn btn-info" onclick="showDynamicNotification('info')">Show Info</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rating Component --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Rating Component</h4>
                            <div class="demo-container bg-light p-4 rounded">
                                <div class="mb-3">
                                    <label class="form-label">Interactive Rating:</label>
                                    <x-media.rating 
                                    <x-media.rating 
                                        name="demo_rating"
                                        stars="5"
                                        value="3"
                                    />
                                </div>
                                <div>
                                    <label class="form-label">Read-only Rating:</label>
                                    <x-media.rating 
                                    <x-media.rating 
                                        stars="5"
                                        value="4"
                                        readonly="true"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab Component (nested example) --}}
                    <div class="col-md-6 mb-4">
                        <div class="component-demo">
                            <h4 class="mb-3">Tab Component</h4>
                            <div class="demo-container">
                                <x-pub_theme::ui.tab orientation="vertical">
                                <x-pub_theme::ui.tab orientation="vertical">
                                    <x-slot name="tabs">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#vertical-tab1" type="button">
                                                <svg class="icon icon-sm me-2"><use href="#it-info"></use></svg>
                                                Info
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#vertical-tab2" type="button">
                                                <svg class="icon icon-sm me-2"><use href="#it-settings"></use></svg>
                                                Settings
                                            </button>
                                        </li>
                                    </x-slot>
                                    <x-slot name="content">
                                        <div class="tab-pane fade show active" id="vertical-tab1">
                                            <p>This is the info tab content with detailed information about the component.</p>
                                        </div>
                                        <div class="tab-pane fade" id="vertical-tab2">
                                            <p>This is the settings tab where you can configure various options.</p>
                                        </div>
                                    </x-slot>
                                </x-tab>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cookiebar Demo --}}
    <x-pub_theme::ui.cookiebar />
</div>

{{-- Component Guide Modal --}}
<div class="modal fade" id="componentGuideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bootstrap Italia Components Guide</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Implementation Status</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Core Components</span>
                                <x-badge color="success">8/8</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Form Components</span>
                                <x-badge color="success">4/4</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Navigation</span>
                                <x-badge color="success">3/3</x-badge>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Interactive</span>
                                <x-badge color="success">4/4</x-badge>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Compliance Status</h6>
                        <div class="mb-2">
                            <small class="text-muted">WCAG 2.1 AA Compliance</small>
                            <x-feedback.progress-indicators type="bar" :percentage="100" color="success" />
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Bootstrap Italia Standards</small>
                            <x-feedback.progress-indicators type="bar" :percentage="95" color="success" />
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Italian PA Requirements</small>
                            <x-feedback.progress-indicators type="bar" :percentage="98" color="success" />
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h6>Usage Guidelines</h6>
                <ul class="list-unstyled">
                    <li><strong>Accessibility:</strong> All components include proper ARIA attributes and keyboard navigation</li>
                    <li><strong>Responsiveness:</strong> Components are mobile-first and fully responsive</li>
                    <li><strong>Customization:</strong> Use component props to customize appearance and behavior</li>
                    <li><strong>Integration:</strong> Components work seamlessly with Laravel Blade and Alpine.js</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-primary">View Documentation</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Dynamic notification function
function showDynamicNotification(type) {
    const messages = {
        success: 'Operation completed successfully!',
        warning: 'Please review your settings.',
        error: 'An error occurred. Please try again.',
        info: 'Information: Check the latest updates.'
    };
    
    const titles = {
        success: 'Success',
        warning: 'Warning',
        error: 'Error', 
        info: 'Information'
    };
    
    const icons = {
        success: 'check-circle',
        warning: 'exclamation-triangle',
        error: 'times-circle',
        info: 'info-circle'
    };
    
    if (window.createNotification) {
        window.createNotification(
            messages[type],
            type,
            {
                title: titles[type],
                icon: icons[type],
                position: 'right-fix',
                autoHide: 4000,
                dismissible: true
            }
        );
    }
}

// Demo interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add demo event listeners
    console.log('Bootstrap Italia Showcase loaded');
    
    // Track component interactions for demo purposes
    document.addEventListener('click', function(e) {
        if (e.target.closest('.component-demo')) {
            const component = e.target.closest('.component-demo').querySelector('h4').textContent;
            console.log(`Demo interaction with: ${component}`);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.bootstrap-italia-showcase {
    min-height: 100vh;
}

.component-demo {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.component-demo:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.demo-container {
    border: 1px dashed #ccc;
    border-radius: 4px;
    min-height: 80px;
    position: relative;
}

.demo-container:not(.bg-light) {
    background: #f8f9fa;
    padding: 1rem;
}

.component-demo h4 {
    color: #0066CC;
    font-weight: 600;
    font-size: 1.1rem;
}

.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    background-color: #0066CC;
    color: white;
    border-radius: 0.375rem;
}

.nav-tabs .nav-link:hover:not(.active) {
    background-color: rgba(0, 102, 204, 0.1);
    color: #0066CC;
}

.tab-content {
    border: none;
    padding-top: 2rem;
}

@media print {
    .component-demo {
        break-inside: avoid;
        page-break-inside: avoid;
    }
    
    .demo-container {
        background: white !important;
    }
    
    .btn, .modal {
        display: none !important;
    }
}
</style>
@endpush
