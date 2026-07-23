# Filament 5 Migration Plan

## Overview
Migration plan for upgrading TechPlanner project from Filament 4 to Filament 5.

## Dependencies
- Main obstacle: `lara-zeus/spatie-translatable` package in Lang module only supports Filament 4
- Solution: Migrate to Filament 5's built-in translation system

## Migration Steps

### Phase 1: Preparation
1. Create branch: `git checkout -b filament5-migration`
2. Update root composer.json repositories section to include local path package
3. Document current state of LangBase classes

### Phase 2: Create Filament 5 Compatible Translation System
1. Update Lang module to handle translations with Filament 5's built-in system
2. Modify LangBaseResource and related classes to use new translation approach
3. Update all models using HasTranslations trait

### Phase 3: Execute Upgrade
1. Run `composer require filament/upgrade:"^5.0" --dev`
2. Run `vendor/bin/filament-v5` script
3. Update composer dependencies to Filament 5

### Phase 4: Module Updates
1. Update all modules using LangBase classes (Cms, Notify, etc.)
2. Update panel providers to register new translation plugin
3. Test all functionality

### Phase 5: Validation
1. Run all tests
2. Verify translation functionality
3. Update documentation