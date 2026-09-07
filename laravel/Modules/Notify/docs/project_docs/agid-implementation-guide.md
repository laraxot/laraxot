---
title: "🚀 AGID Implementation Guide - Practical Developer Guide"
type: guide
tags: [agid, implementation, guide]
created: 2026-07-14
updated: 2026-07-14
qmd: "agid-implementation-guide 🚀 agid implementation guide - practical developer guide"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./2025-excellence-achievement.md"
  - "./architecture.md"
  - "./complete-refactoring-analysis.md"
  - "./documentation-status.md"
  - "./final-implementation-report-.md"
  - "./final-implementation-report-1.md"
  - "./final-implementation-report.md"
  - "./final-refactoring-report.md"
---

# 🚀 AGID Implementation Guide - Practical Developer Guide

**Date**: 2025-10-02  
**Target**: Developers implementing AGID-compliant features  
**Difficulty**: Intermediate  
**Estimated Time**: Follow step-by-step tutorials

---

## 📚 Table of Contents

1. [Multi-Step Form Wizard](#multi-step-form-wizard)
2. [Accordion Component](#accordion-component)
3. [FAQ System](#faq-system)
4. [Search Functionality](#search-functionality)
5. [SEO Enhancement](#seo-enhancement)

---

## 1. Multi-Step Form Wizard

### 🎯 Objective
Transform the single-page ticket creation form into a 4-step AGID-compliant wizard.

### 📦 Components Created
- `<x-ui::stepper>`
- `<x-ui::stepper-step>`
- Translations: `ui::stepper.*`

### 💻 Implementation

#### Step 1: Update Ticket Creation Form

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/tickets/create.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/tickets/create.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/tickets/create.blade.php`

```blade
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.create.title') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.create.title') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::ticket.create.title') }}
        </h1>
        
        <form 
            action="{{ route('tickets.store') }}" 
            method="POST"
            enctype="multipart/form-data"
            x-data="ticketWizard()"
            @submit.prevent="submitForm"
        >
            @csrf
            
            <x-ui::stepper
                :total-steps="4"
                :steps="[
                    1 => __('laraxot::ticket.create.step_privacy'),
                    2 => __('laraxot::ticket.create.step_data'),
                    3 => __('laraxot::ticket.create.step_summary'),
                    4 => __('laraxot::ticket.create.step_confirmation'),
                ]"
            >
                {{-- Step 1: Privacy Consent --}}
                <x-ui::stepper-step 
                    :number="1" 
<<<<<<< HEAD
<<<<<<< HEAD
                    :title="__('fixcity::ticket.create.privacy_title')"
                >
                    @include('fixcity::tickets.steps.privacy')
=======
                    :title="__('laraxot::ticket.create.privacy_title')"
                >
                    @include('laraxot::tickets.steps.privacy')
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    :title="__('fixcity::ticket.create.privacy_title')"
                >
                    @include('fixcity::tickets.steps.privacy')
>>>>>>> a988596b (first)
                </x-ui::stepper-step>
                
                {{-- Step 2: Data Entry --}}
                <x-ui::stepper-step 
                    :number="2" 
<<<<<<< HEAD
<<<<<<< HEAD
                    :title="__('fixcity::ticket.create.data_title')"
                >
                    @include('fixcity::tickets.steps.data')
=======
                    :title="__('laraxot::ticket.create.data_title')"
                >
                    @include('laraxot::tickets.steps.data')
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    :title="__('fixcity::ticket.create.data_title')"
                >
                    @include('fixcity::tickets.steps.data')
>>>>>>> a988596b (first)
                </x-ui::stepper-step>
                
                {{-- Step 3: Summary --}}
                <x-ui::stepper-step 
                    :number="3" 
<<<<<<< HEAD
<<<<<<< HEAD
                    :title="__('fixcity::ticket.create.summary_title')"
                >
                    @include('fixcity::tickets.steps.summary')
=======
                    :title="__('laraxot::ticket.create.summary_title')"
                >
                    @include('laraxot::tickets.steps.summary')
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    :title="__('fixcity::ticket.create.summary_title')"
                >
                    @include('fixcity::tickets.steps.summary')
>>>>>>> a988596b (first)
                </x-ui::stepper-step>
                
                {{-- Step 4: Confirmation --}}
                <x-ui::stepper-step 
                    :number="4" 
<<<<<<< HEAD
<<<<<<< HEAD
                    :title="__('fixcity::ticket.create.confirmation_title')"
                >
                    @include('fixcity::tickets.steps.confirmation')
=======
                    :title="__('laraxot::ticket.create.confirmation_title')"
                >
                    @include('laraxot::tickets.steps.confirmation')
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    :title="__('fixcity::ticket.create.confirmation_title')"
                >
                    @include('fixcity::tickets.steps.confirmation')
>>>>>>> a988596b (first)
                </x-ui::stepper-step>
            </x-ui::stepper>
        </form>
    </div>
    
    @push('scripts')
    <script>
        function ticketWizard() {
            return {
                formData: {
                    privacy_consent: false,
                    category_id: '',
                    title: '',
                    description: '',
                    latitude: null,
                    longitude: null,
                    address: '',
                    photos: [],
                    name: '{{ auth()->user()->name ?? "" }}',
                    email: '{{ auth()->user()->email ?? "" }}',
                    phone: '',
                },
                
                submitForm() {
                    // Validate all steps
                    if (!this.validateAllSteps()) {
<<<<<<< HEAD
<<<<<<< HEAD
                        alert('{{ __("fixcity::ticket.create.validation_error") }}');
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                        alert('{{ __("fixcity::ticket.create.validation_error") }}');
>>>>>>> a988596b (first)
                        alert('{{ __("laraxot::ticket.create.validation_error") }}');
                        return;
                    }
                    
                    // Submit form
                    this.$el.submit();
                },
                
                validateAllSteps() {
                    return this.formData.privacy_consent &&
                           this.formData.category_id &&
                           this.formData.title &&
                           this.formData.description;
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
```

#### Step 2: Create Step Partials

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/tickets/steps/privacy.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/tickets/steps/privacy.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/tickets/steps/privacy.blade.php`

```blade
<div class="privacy-step">
    <div class="alert alert-info mb-4">
        <h3 class="alert-heading">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.privacy.heading') }}
        </h3>
        <p>{{ __('fixcity::ticket.privacy.intro') }}</p>
=======
            {{ __('laraxot::ticket.privacy.heading') }}
        </h3>
        <p>{{ __('laraxot::ticket.privacy.intro') }}</p>
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.privacy.heading') }}
        </h3>
        <p>{{ __('fixcity::ticket.privacy.intro') }}</p>
>>>>>>> a988596b (first)
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                {{ __('fixcity::ticket.privacy.policy_title') }}
            </h4>
            
            <div class="privacy-policy-text" style="max-height: 300px; overflow-y: auto;">
                {!! __('fixcity::ticket.privacy.policy_content') !!}
<<<<<<< HEAD
=======
                {{ __('laraxot::ticket.privacy.policy_title') }}
            </h4>
            
            <div class="privacy-policy-text" style="max-height: 300px; overflow-y: auto;">
                {!! __('laraxot::ticket.privacy.policy_content') !!}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            </div>
        </div>
    </div>
    
    <div class="form-check">
        <input 
            type="checkbox" 
            class="form-check-input" 
            id="privacy_consent"
            x-model="formData.privacy_consent"
            required
        >
        <label class="form-check-label" for="privacy_consent">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.privacy.consent_label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.privacy.consent_label') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::ticket.privacy.consent_label') }}
            <span class="text-danger">*</span>
        </label>
    </div>
    
    <p class="text-muted small mt-2">
<<<<<<< HEAD
<<<<<<< HEAD
        {{ __('fixcity::ticket.privacy.required_info') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        {{ __('fixcity::ticket.privacy.required_info') }}
>>>>>>> a988596b (first)
        {{ __('laraxot::ticket.privacy.required_info') }}
    </p>
</div>
```

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/tickets/steps/data.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/tickets/steps/data.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/tickets/steps/data.blade.php`

```blade
<div class="data-step">
    {{-- Category Selection --}}
    <fieldset class="mb-4">
        <legend class="h5">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.fields.category.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.fields.category.label') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::ticket.fields.category.label') }}
            <span class="text-danger">*</span>
        </legend>
        
        <select 
            name="category_id" 
            id="category_id"
            class="form-select"
            x-model="formData.category_id"
            required
        >
<<<<<<< HEAD
<<<<<<< HEAD
            <option value="">{{ __('fixcity::ticket.fields.category.placeholder') }}</option>
            @foreach(\Modules\Fixcity\Enums\TicketTypeEnum::cases() as $type)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            <option value="">{{ __('fixcity::ticket.fields.category.placeholder') }}</option>
            @foreach(\Modules\Fixcity\Enums\TicketTypeEnum::cases() as $type)
>>>>>>> a988596b (first)
            <option value="">{{ __('laraxot::ticket.fields.category.placeholder') }}</option>
            @foreach(\Modules\App\Enums\TicketTypeEnum::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </select>
    </fieldset>
    
    {{-- Location --}}
    <fieldset class="mb-4">
        <legend class="h5">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            {{ __('fixcity::ticket.fields.location.label') }}
            <span class="text-danger">*</span>
        </legend>
        <p class="text-muted small">
            {{ __('fixcity::ticket.fields.location.help') }}
        </p>
        
        <x-fixcity::map-picker
            <span class="text-danger">*</span>
        </legend>
        <p class="text-muted small">
            {{ __('fixcity::ticket.fields.location.help') }}
        </p>
        
        <x-fixcity::map-picker
<<<<<<< HEAD
=======
            {{ __('laraxot::ticket.fields.location.label') }}
            <span class="text-danger">*</span>
        </legend>
        <p class="text-muted small">
            {{ __('laraxot::ticket.fields.location.help') }}
        </p>
        
        <x-laraxot::map-picker
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            name="location"
            :center="[41.9028, 12.4964]"
            :zoom="13"
            x-model:latitude="formData.latitude"
            x-model:longitude="formData.longitude"
            x-model:address="formData.address"
        />
        
        <input type="hidden" name="latitude" x-model="formData.latitude">
        <input type="hidden" name="longitude" x-model="formData.longitude">
        <input type="hidden" name="address" x-model="formData.address">
    </fieldset>
    
    {{-- Issue Details --}}
    <fieldset class="mb-4">
        <legend class="h5">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.fields.details.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.fields.details.label') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::ticket.fields.details.label') }}
        </legend>
        
        <div class="mb-3">
            <label for="title" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.title.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.title.label') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.title.label') }}
                <span class="text-danger">*</span>
            </label>
            <input 
                type="text" 
                class="form-control" 
                id="title"
                name="title"
                x-model="formData.title"
<<<<<<< HEAD
<<<<<<< HEAD
                :placeholder="__('fixcity::ticket.fields.title.placeholder')"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                :placeholder="__('fixcity::ticket.fields.title.placeholder')"
>>>>>>> a988596b (first)
                :placeholder="__('laraxot::ticket.fields.title.placeholder')"
                required
                maxlength="255"
            >
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.description.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.description.label') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.description.label') }}
                <span class="text-danger">*</span>
            </label>
            <textarea 
                class="form-control" 
                id="description"
                name="description"
                rows="5"
                x-model="formData.description"
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                :placeholder="__('fixcity::ticket.fields.description.placeholder')"
                required
            ></textarea>
            <div class="form-text">
                {{ __('fixcity::ticket.fields.description.help') }}
<<<<<<< HEAD
=======
                :placeholder="__('laraxot::ticket.fields.description.placeholder')"
                required
            ></textarea>
            <div class="form-text">
                {{ __('laraxot::ticket.fields.description.help') }}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            </div>
        </div>
        
        <div class="mb-3">
            <label for="photos" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.photos.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.photos.label') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.photos.label') }}
            </label>
            <input 
                type="file" 
                class="form-control" 
                id="photos"
                name="photos[]"
                multiple
                accept="image/*"
                @change="formData.photos = Array.from($event.target.files)"
            >
            <div class="form-text">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.photos.help') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.photos.help') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.photos.help') }}
            </div>
        </div>
    </fieldset>
    
    {{-- Reporter Information --}}
    <fieldset class="mb-4">
        <legend class="h5">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::ticket.fields.reporter.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::ticket.fields.reporter.label') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::ticket.fields.reporter.label') }}
        </legend>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                    {{ __('fixcity::ticket.fields.name.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    {{ __('fixcity::ticket.fields.name.label') }}
>>>>>>> a988596b (first)
                    {{ __('laraxot::ticket.fields.name.label') }}
                    <span class="text-danger">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name"
                    name="name"
                    x-model="formData.name"
                    required
                >
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                    {{ __('fixcity::ticket.fields.email.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    {{ __('fixcity::ticket.fields.email.label') }}
>>>>>>> a988596b (first)
                    {{ __('laraxot::ticket.fields.email.label') }}
                    <span class="text-danger">*</span>
                </label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email"
                    name="email"
                    x-model="formData.email"
                    required
                >
            </div>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.phone.label') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.phone.label') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.phone.label') }}
            </label>
            <input 
                type="tel" 
                class="form-control" 
                id="phone"
                name="phone"
                x-model="formData.phone"
            >
            <div class="form-text">
<<<<<<< HEAD
<<<<<<< HEAD
                {{ __('fixcity::ticket.fields.phone.help') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                {{ __('fixcity::ticket.fields.phone.help') }}
>>>>>>> a988596b (first)
                {{ __('laraxot::ticket.fields.phone.help') }}
            </div>
        </div>
    </fieldset>
</div>
```

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/tickets/steps/summary.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/tickets/steps/summary.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/tickets/steps/summary.blade.php`

```blade
<div class="summary-step">
    <div class="alert alert-warning">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        <strong>{{ __('fixcity::ticket.summary.review_heading') }}</strong>
        <p>{{ __('fixcity::ticket.summary.review_text') }}</p>
    </div>
    
    <dl class="row">
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.category.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.category_id"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.title.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.title"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.description.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.description"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.location.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.address || 'N/A'"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.photos.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.photos.length + ' {{ __("fixcity::ticket.summary.photos_count") }}'"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.name.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.name"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.email.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.email"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.phone.label') }}</dt>
    </div>
    
    <dl class="row">
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.category.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.category_id"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.title.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.title"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.description.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.description"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.location.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.address || 'N/A'"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.photos.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.photos.length + ' {{ __("fixcity::ticket.summary.photos_count") }}'"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.name.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.name"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.email.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.email"></dd>
        
        <dt class="col-sm-3">{{ __('fixcity::ticket.fields.phone.label') }}</dt>
<<<<<<< HEAD
=======
        <strong>{{ __('laraxot::ticket.summary.review_heading') }}</strong>
        <p>{{ __('laraxot::ticket.summary.review_text') }}</p>
    </div>
    
    <dl class="row">
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.category.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.category_id"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.title.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.title"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.description.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.description"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.location.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.address || 'N/A'"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.photos.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.photos.length + ' {{ __("laraxot::ticket.summary.photos_count") }}'"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.name.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.name"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.email.label') }}</dt>
        <dd class="col-sm-9" x-text="formData.email"></dd>
        
        <dt class="col-sm-3">{{ __('laraxot::ticket.fields.phone.label') }}</dt>
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        <dd class="col-sm-9" x-text="formData.phone || 'N/A'"></dd>
    </dl>
    
    <div class="alert alert-info mt-4">
        <svg class="icon icon-info" aria-hidden="true">
            <use href="#it-info-circle"></use>
        </svg>
<<<<<<< HEAD
<<<<<<< HEAD
        <strong>{{ __('fixcity::ticket.summary.notification_heading') }}</strong>
        <p>{{ __('fixcity::ticket.summary.notification_text', ['email' => '']) }}</p>
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        <strong>{{ __('fixcity::ticket.summary.notification_heading') }}</strong>
        <p>{{ __('fixcity::ticket.summary.notification_text', ['email' => '']) }}</p>
>>>>>>> a988596b (first)
        <strong>{{ __('laraxot::ticket.summary.notification_heading') }}</strong>
        <p>{{ __('laraxot::ticket.summary.notification_text', ['email' => '']) }}</p>
    </div>
</div>
```

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/tickets/steps/confirmation.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/tickets/steps/confirmation.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/tickets/steps/confirmation.blade.php`

```blade
<div class="confirmation-step text-center">
    <div class="icon-success mb-4">
        <svg class="icon icon-xl icon-success" aria-hidden="true">
            <use href="#it-check-circle"></use>
        </svg>
    </div>
    
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    <h2 class="mb-3">{{ __('fixcity::ticket.confirmation.success_heading') }}</h2>
    
    <p class="lead text-muted">
        {{ __('fixcity::ticket.confirmation.success_text') }}
<<<<<<< HEAD
=======
    <h2 class="mb-3">{{ __('laraxot::ticket.confirmation.success_heading') }}</h2>
    
    <p class="lead text-muted">
        {{ __('laraxot::ticket.confirmation.success_text') }}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    </p>
    
    <div class="alert alert-success my-4">
        <p class="mb-0">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            <strong>{{ __('fixcity::ticket.confirmation.next_steps_heading') }}</strong>
        </p>
        <ol class="text-start mt-3">
            <li>{{ __('fixcity::ticket.confirmation.step_1') }}</li>
            <li>{{ __('fixcity::ticket.confirmation.step_2') }}</li>
            <li>{{ __('fixcity::ticket.confirmation.step_3') }}</li>
<<<<<<< HEAD
=======
            <strong>{{ __('laraxot::ticket.confirmation.next_steps_heading') }}</strong>
        </p>
        <ol class="text-start mt-3">
            <li>{{ __('laraxot::ticket.confirmation.step_1') }}</li>
            <li>{{ __('laraxot::ticket.confirmation.step_2') }}</li>
            <li>{{ __('laraxot::ticket.confirmation.step_3') }}</li>
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        </ol>
    </div>
</div>
```

### ✅ Testing Checklist

- [ ] All 4 steps render correctly
- [ ] Progress indicator updates
- [ ] Navigation buttons work (Next/Previous)
- [ ] Form validation works per step
- [ ] Summary displays all entered data
- [ ] Form submits successfully
- [ ] Keyboard navigation works (Tab, Enter)
- [ ] Screen reader announces step changes
- [ ] Mobile responsive
- [ ] Works without JavaScript (graceful degradation)

---

## 2. Accordion Component

### 🎯 Objective
Create FAQ pages with AGID-compliant accordion UI.

### 💻 Implementation

<<<<<<< HEAD
<<<<<<< HEAD
**File**: `Modules/Fixcity/resources/views/faq/index.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**File**: `Modules/Fixcity/resources/views/faq/index.blade.php`
>>>>>>> a988596b (first)
**File**: `Modules/App/resources/views/faq/index.blade.php`

```blade
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            {{ __('fixcity::faq.title') }}
        </h1>
        <p class="text-lg text-gray-600 mb-8">
            {{ __('fixcity::faq.subtitle') }}
<<<<<<< HEAD
=======
            {{ __('laraxot::faq.title') }}
        </h1>
        <p class="text-lg text-gray-600 mb-8">
            {{ __('laraxot::faq.subtitle') }}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        </p>
        
        {{-- Search FAQ --}}
        <div class="mb-6">
            <div class="relative">
                <input 
                    type="search" 
                    class="form-control"
<<<<<<< HEAD
<<<<<<< HEAD
                    placeholder="{{ __('fixcity::faq.search_placeholder') }}"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    placeholder="{{ __('fixcity::faq.search_placeholder') }}"
>>>>>>> a988596b (first)
                    placeholder="{{ __('laraxot::faq.search_placeholder') }}"
                    x-data
                    x-on:input.debounce.300ms="searchFaq($event.target.value)"
                >
                <svg class="icon icon-sm position-absolute top-50 end-0 translate-middle-y me-3" aria-hidden="true">
                    <use href="#it-search"></use>
                </svg>
            </div>
        </div>
        
        @foreach($faqsByCategory as $categoryName => $faqs)
            <section class="mb-6">
                <h2 class="h4 mb-3">{{ $categoryName }}</h2>
                
                <x-ui::accordion id="faq-{{ Str::slug($categoryName) }}">
                    @foreach($faqs as $index => $faq)
                        <x-ui::accordion-item
                            :title="$faq->question"
                            :id="'faq-' . $faq->id"
                            :parent-id="'faq-' . Str::slug($categoryName)"
                            :open="$index === 0"
                        >
                            <div class="faq-answer">
                                {!! $faq->answer !!}
                            </div>
                            
                            @if($faq->related_links)
                                <div class="related-links mt-3">
<<<<<<< HEAD
<<<<<<< HEAD
                                    <strong>{{ __('fixcity::faq.related_links') }}:</strong>
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                                    <strong>{{ __('fixcity::faq.related_links') }}:</strong>
>>>>>>> a988596b (first)
                                    <strong>{{ __('laraxot::faq.related_links') }}:</strong>
                                    <ul>
                                        @foreach($faq->related_links as $link)
                                            <li>
                                                <a href="{{ $link['url'] }}">
                                                    {{ $link['text'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </x-ui::accordion-item>
                    @endforeach
                </x-ui::accordion>
            </section>
        @endforeach
        
        {{-- Still need help? --}}
        <div class="card bg-light mt-8">
            <div class="card-body text-center">
                <h3 class="card-title">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    {{ __('fixcity::faq.need_help_title') }}
                </h3>
                <p class="card-text">
                    {{ __('fixcity::faq.need_help_text') }}
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    {{ __('fixcity::faq.contact_button') }}
                </h3>
                <p class="card-text">
                    {{ __('fixcity::faq.need_help_text') }}
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    {{ __('fixcity::faq.contact_button') }}
<<<<<<< HEAD
=======
                    {{ __('laraxot::faq.need_help_title') }}
                </h3>
                <p class="card-text">
                    {{ __('laraxot::faq.need_help_text') }}
                </p>
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    {{ __('laraxot::faq.contact_button') }}
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
```

<<<<<<< HEAD
<<<<<<< HEAD
**Model**: `Modules/Fixcity/app/Models/Faq.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Model**: `Modules/Fixcity/app/Models/Faq.php`
>>>>>>> a988596b (first)
**Model**: `Modules/App/app/Models/Faq.php`

```php
<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\Fixcity\Models;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
namespace Modules\Fixcity\Models;
>>>>>>> a988596b (first)
namespace Modules\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    protected $fillable = [
        'category_id',
        'question',
        'answer',
        'related_links',
        'order',
        'is_published',
    ];
    
    protected $casts = [
        'related_links' => 'array',
        'is_published' => 'boolean',
    ];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }
    
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
```

**Migration**:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
php artisan make:migration create_faqs_table --path=Modules/Fixcity/database/Migrations
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
php artisan make:migration create_faqs_table --path=Modules/Fixcity/database/Migrations
>>>>>>> a988596b (first)
php artisan make:migration create_faqs_table --path=Modules/App/database/Migrations
```

```php
Schema::create('faqs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained('faq_categories')->onDelete('cascade');
    $table->string('question');
    $table->text('answer');
    $table->json('related_links')->nullable();
    $table->integer('order')->default(0);
    $table->boolean('is_published')->default(true);
    $table->timestamps();
});
```

---

## 3. Search Functionality

### 🎯 Objective
Implement full-text search with Laravel Scout.

### 📦 Installation

```bash
composer require laravel/scout
composer require meilisearch/meilisearch-php
```

**Config**: `config/scout.php`
```php
'driver' => env('SCOUT_DRIVER', 'meilisearch'),
```

**Environment**: `.env`
```
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=your-master-key
```

### 💻 Implementation

<<<<<<< HEAD
<<<<<<< HEAD
**Model**: `Modules/Fixcity/app/Models/Ticket.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Model**: `Modules/Fixcity/app/Models/Ticket.php`
>>>>>>> a988596b (first)
**Model**: `Modules/App/app/Models/Ticket.php`

```php
use Laravel\Scout\Searchable;

class Ticket extends Model
{
    use Searchable;
    
    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'tracking_code' => $this->tracking_code,
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'status' => $this->status->value,
            'type' => $this->type->value,
            'created_at' => $this->created_at->timestamp,
        ];
    }
    
    /**
     * Get the index name for the model.
     */
    public function searchableAs(): string
    {
        return 'tickets_index';
    }
}
```

<<<<<<< HEAD
<<<<<<< HEAD
**Controller**: `Modules/Fixcity/app/Http/Controllers/SearchController.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**Controller**: `Modules/Fixcity/app/Http/Controllers/SearchController.php`
>>>>>>> a988596b (first)
**Controller**: `Modules/App/app/Http/Controllers/SearchController.php`

```php
<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
namespace Modules\Fixcity\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Fixcity\Models\Ticket;
<<<<<<< HEAD
=======
namespace Modules\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\App\Models\Ticket;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

class SearchController
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
<<<<<<< HEAD
<<<<<<< HEAD
            return view('fixcity::search.index', [
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            return view('fixcity::search.index', [
>>>>>>> a988596b (first)
            return view('laraxot::search.index', [
                'query' => '',
                'results' => collect(),
                'total' => 0,
            ]);
        }
        
        $results = Ticket::search($query)
            ->query(fn ($builder) => $builder->with(['owner', 'responsible']))
            ->paginate(20);
        
<<<<<<< HEAD
<<<<<<< HEAD
        return view('fixcity::search.index', [
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        return view('fixcity::search.index', [
>>>>>>> a988596b (first)
        return view('laraxot::search.index', [
            'query' => $query,
            'results' => $results,
            'total' => $results->total(),
        ]);
    }
}
```

**Route**:
```php
Route::get('/search', [SearchController::class, 'index'])->name('search');
```

<<<<<<< HEAD
<<<<<<< HEAD
**View**: `Modules/Fixcity/resources/views/search/index.blade.php`
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
**View**: `Modules/Fixcity/resources/views/search/index.blade.php`
>>>>>>> a988596b (first)
**View**: `Modules/App/resources/views/search/index.blade.php`

```blade
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">
<<<<<<< HEAD
<<<<<<< HEAD
            {{ __('fixcity::search.title') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            {{ __('fixcity::search.title') }}
>>>>>>> a988596b (first)
            {{ __('laraxot::search.title') }}
        </h1>
        
        {{-- Search Form --}}
        <form action="{{ route('search') }}" method="GET" class="mb-6">
            <div class="input-group">
                <input 
                    type="search" 
                    name="q" 
                    class="form-control form-control-lg"
                    value="{{ $query }}"
<<<<<<< HEAD
<<<<<<< HEAD
                    placeholder="{{ __('fixcity::search.placeholder') }}"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    placeholder="{{ __('fixcity::search.placeholder') }}"
>>>>>>> a988596b (first)
                    placeholder="{{ __('laraxot::search.placeholder') }}"
                    autofocus
                >
                <button type="submit" class="btn btn-primary">
                    <svg class="icon icon-white" aria-hidden="true">
                        <use href="#it-search"></use>
                    </svg>
<<<<<<< HEAD
<<<<<<< HEAD
                    {{ __('fixcity::search.button') }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    {{ __('fixcity::search.button') }}
>>>>>>> a988596b (first)
                    {{ __('laraxot::search.button') }}
                </button>
            </div>
        </form>
        
        @if($query)
            <div class="search-results">
                <p class="text-muted mb-4">
<<<<<<< HEAD
<<<<<<< HEAD
                    {{ trans_choice('fixcity::search.results_count', $total, ['count' => $total, 'query' => $query]) }}
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    {{ trans_choice('fixcity::search.results_count', $total, ['count' => $total, 'query' => $query]) }}
>>>>>>> a988596b (first)
                    {{ trans_choice('laraxot::search.results_count', $total, ['count' => $total, 'query' => $query]) }}
                </p>
                
                @if($results->isEmpty())
                    <div class="alert alert-info">
                        <h3 class="alert-heading">
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                            {{ __('fixcity::search.no_results_heading') }}
                        </h3>
                        <p>{{ __('fixcity::search.no_results_text') }}</p>
                        <ul>
                            <li>{{ __('fixcity::search.tip_1') }}</li>
                            <li>{{ __('fixcity::search.tip_2') }}</li>
                            <li>{{ __('fixcity::search.tip_3') }}</li>
                        </h3>
                        <p>{{ __('fixcity::search.no_results_text') }}</p>
                        <ul>
                            <li>{{ __('fixcity::search.tip_1') }}</li>
                            <li>{{ __('fixcity::search.tip_2') }}</li>
                            <li>{{ __('fixcity::search.tip_3') }}</li>
<<<<<<< HEAD
=======
                            {{ __('laraxot::search.no_results_heading') }}
                        </h3>
                        <p>{{ __('laraxot::search.no_results_text') }}</p>
                        <ul>
                            <li>{{ __('laraxot::search.tip_1') }}</li>
                            <li>{{ __('laraxot::search.tip_2') }}</li>
                            <li>{{ __('laraxot::search.tip_3') }}</li>
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
                        </ul>
                    </div>
                @else
                    <div class="results-list">
                        @foreach($results as $ticket)
                            <article class="card mb-3">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <a href="{{ route('tickets.show', $ticket) }}">
                                            {{ $ticket->title }}
                                        </a>
                                    </h3>
                                    <p class="card-text">
                                        {{ Str::limit($ticket->description, 200) }}
                                    </p>
                                    <div class="meta text-muted small">
                                        <span class="badge bg-{{ $ticket->status->getColor() }}">
                                            {{ $ticket->status->label() }}
                                        </span>
                                        <span class="ms-2">
                                            {{ $ticket->created_at->diffForHumans() }}
                                        </span>
                                        <span class="ms-2">
                                            📍 {{ $ticket->address }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    
                    {{ $results->links() }}
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
```

**Index tickets**:
```bash
<<<<<<< HEAD
<<<<<<< HEAD
php artisan scout:import "Modules\\Fixcity\\Models\\Ticket"
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
php artisan scout:import "Modules\\Fixcity\\Models\\Ticket"
>>>>>>> a988596b (first)
php artisan scout:import "Modules\\App\\Models\\Ticket"
```

---

## ✅ Final Checklist

### Components Created
- [x] Stepper component
- [x] Stepper-step component
- [x] Accordion component
- [x] Accordion-item component
- [x] Translations (IT/EN)

### Documentation Created
- [x] AGID Compliance Summary (Themes/Sixteen)
- [x] AGID Compliance (Modules/Cms)
- [x] Implementation Guide (this file)

### Features Implemented
- [x] Multi-step form structure
- [x] Accordion UI component
- [ ] FAQ system (model + migration needed)
- [ ] Search functionality (Scout integration needed)
- [ ] SEO enhancements (migrations needed)

### Next Steps
1. Create migrations for FAQs
2. Install and configure Laravel Scout
3. Create Filament resources for FAQ management
4. Add SEO fields to existing models
5. Implement remaining AGID templates

---

**Estimated Total Time**: 40-60 hours for full AGID compliance  
**Priority Order**: Multi-step form → FAQ → Search → SEO → Optional features

**Maintainer**: Development Team
