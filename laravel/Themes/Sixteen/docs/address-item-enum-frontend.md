# AddressItemEnum - Frontend Integration Guide

## Overview

This guide explains how to effectively use the `AddressItemEnum` in frontend components, particularly within Filament forms and Vue/JavaScript components.

## Form Implementation

### Basic Address Form Component

```php
<?php

namespace Modules\Geo\Filament\Components;

use Filament\Forms;
use Filament\Forms\Form;
use Modules\Geo\Enums\AddressItemEnum;

class AddressForm extends Forms\Component
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Contact Section
                Forms\Section::make('Contact')
                    ->description('Contact information for this address')
                    ->schema([
                        Forms\TextInput::make('phone')
                            ->label(AddressItemEnum::PHONE->getLabel())
                            ->prefixIcon(AddressItemEnum::PHONE->getIcon())
                            ->tel()
                            ->placeholder('+39 123 4567890'),
                    ])
                    ->columns(1),
                
                // Identification Section
                Forms\Section::make('Identification')
                    ->description('Name and description')
                    ->schema([
                        Forms\TextInput::make('name')
                            ->label(AddressItemEnum::NAME->getLabel())
                            ->prefixIcon(AddressItemEnum::NAME->getIcon())
                            ->placeholder('Home, Office, etc.'),
                        
                        Forms\Textarea::make('description')
                            ->label(AddressItemEnum::DESCRIPTION->getLabel())
                            ->rows(2)
                            ->placeholder('Additional notes about this address'),
                    ])
                    ->columns(2),
                
                // Street Address Section
                Forms\Section::make('Street Address')
                    ->description('Street and building information')
                    ->schema([
                        Forms\TextInput::make('route')
                            ->label(AddressItemEnum::ROUTE->getLabel())
                            ->prefixIcon(AddressItemEnum::ROUTE->getIcon())
                            ->placeholder('Via Roma, Piazza Duomo, Corso Italia')
                            ->requiredWith('street_number'),
                        
                        Forms\TextInput::make('street_number')
                            ->label(AddressItemEnum::STREET_NUMBER->getLabel())
                            ->prefixIcon(AddressItemEnum::STREET_NUMBER->getIcon())
                            ->placeholder('123, 1/A, 45/B'),
                    ])
                    ->columns(2),
                
                // Administrative Section
                Forms\Section::make('Administrative Area')
                    ->description('Administrative divisions')
                    ->schema([
                        Forms\TextInput::make('locality')
                            ->label(AddressItemEnum::LOCALITY->getLabel())
                            ->prefixIcon(AddressItemEnum::LOCALITY->getIcon())
                            ->placeholder('Frazione, Località'),
                        
                        Forms\TextInput::make('administrative_area_level_3')
                            ->label(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_3->getLabel())
                            ->prefixIcon(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_3->getIcon())
                            ->placeholder('Milano, Roma, Napoli')
                            ->requiredWith('postal_code'),
                        
                        Forms\TextInput::make('administrative_area_level_2')
                            ->label(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_2->getLabel())
                            ->prefixIcon(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_2->getIcon())
                            ->placeholder('MI, RM, NA'),
                        
                        Forms\TextInput::make('administrative_area_level_1')
                            ->label(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_1->getLabel())
                            ->prefixIcon(AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_1->getIcon())
                            ->placeholder('Lombardia, Lazio, Campania'),
                    ])
                    ->columns(2),
                
                // Country Section
                Forms\Section::make('Country')
                    ->description('Country and postal code')
                    ->schema([
                        Forms\Select::make('country')
                            ->label(AddressItemEnum::COUNTRY->getLabel())
                            ->prefixIcon(AddressItemEnum::COUNTRY->getIcon())
                            ->options([
                                'IT' => 'Italia',
                                'DE' => 'Germany',
                                'FR' => 'France',
                                'ES' => 'Spain',
                                'GB' => 'United Kingdom',
                                'US' => 'United States',
                            ])
                            ->searchable()
                            ->live(),
                        
                        Forms\TextInput::make('postal_code')
                            ->label(AddressItemEnum::POSTAL_CODE->getLabel())
                            ->prefixIcon(AddressItemEnum::POSTAL_CODE->getIcon())
                            ->placeholder('00100, 20100, 80100')
                            ->mask('99999')
                            ->requiredWith('administrative_area_level_3'),
                    ])
                    ->columns(2),
                
                // Geocoding Section
                Forms\Section::make('Geocoding')
                    ->description('Location data')
                    ->schema([
                        Forms\Textarea::make('formatted_address')
                            ->label(AddressItemEnum::FORMATTED_ADDRESS->getLabel())
                            ->rows(2)
                            ->readOnly(),
                        
                        Forms\TextInput::make('place_id')
                            ->label(AddressItemEnum::PLACE_ID->getLabel())
                            ->prefixIcon(AddressItemEnum::PLACE_ID->getIcon())
                            ->readOnly(),
                        
                        Forms\TextInput::make('latitude')
                            ->label(AddressItemEnum::LATITUDE->getLabel())
                            ->prefixIcon(AddressItemEnum::LATITUDE->getIcon())
                            ->numeric()
                            ->step(0.0000000001)
                            ->readOnly(),
                        
                        Forms\TextInput::make('longitude')
                            ->label(AddressItemEnum::LONGITUDE->getLabel())
                            ->prefixIcon(AddressItemEnum::LONGITUDE->getIcon())
                            ->numeric()
                            ->step(0.0000000001)
                            ->readOnly(),
                    ])
                    ->columns(2),
            ]);
    }
}
```

### Dynamic Address Field Generator

```php
<?php

namespace Modules\Geo\Filament\Components;

use Filament\Forms;
use Illuminate\Support\Arr;
use Modules\Geo\Enums\AddressItemEnum;

class DynamicAddressFields
{
    public static function generate(): array
    {
        $fieldGroups = [
            'contact' => [
                AddressItemEnum::PHONE,
            ],
            'identification' => [
                AddressItemEnum::NAME,
                AddressItemEnum::DESCRIPTION,
            ],
            'street' => [
                AddressItemEnum::ROUTE,
                AddressItemEnum::STREET_NUMBER,
            ],
            'administrative' => [
                AddressItemEnum::LOCALITY,
                AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_3,
                AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_2,
                AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_1,
            ],
            'country' => [
                AddressItemEnum::COUNTRY,
                AddressItemEnum::POSTAL_CODE,
            ],
            'geocoding' => [
                AddressItemEnum::FORMATTED_ADDRESS,
                AddressItemEnum::PLACE_ID,
                AddressItemEnum::LATITUDE,
                AddressItemEnum::LONGITUDE,
            ],
        ];
        
        $sections = [];
        
        foreach ($fieldGroups as $groupName => $enums) {
            $fields = Arr::map($enums, function ($enum) {
                $fieldType = match($enum) {
                    AddressItemEnum::DESCRIPTION,
                    AddressItemEnum::FORMATTED_ADDRESS => Forms\Textarea::class,
                    AddressItemEnum::COUNTRY => Forms\Select::class,
                    AddressItemEnum::LATITUDE,
                    AddressItemEnum::LONGITUDE => Forms\TextInput::class,
                    default => Forms\TextInput::class,
                };
                
                return $fieldType::make($enum->value)
                    ->label($enum->getLabel())
                    ->prefixIcon($enum->getIcon())
                    ->helperText($enum->getDescription());
            });
            
            $sections[$groupName] = Forms\Section::make(ucfirst($groupName))
                ->schema($fields)
                ->columns(2);
        }
        
        return array_values($sections);
    }
}
```

## JavaScript Integration

### Address Autocomplete Component

```javascript
// resources/js/components/AddressAutocomplete.js
import AddressItemEnum from '../enums/AddressItemEnum';

class AddressAutocomplete {
    constructor(input, options = {}) {
        this.input = input;
        this.options = {
            country: 'IT',
            types: ['address'],
            ...options
        };
        
        this.init();
    }
    
    init() {
        // Initialize Google Places Autocomplete
        const autocomplete = new google.maps.places.Autocomplete(
            this.input,
            {
                types: this.options.types,
                componentRestrictions: { country: this.options.country }
            }
        );
        
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            this.fillAddress(place);
        });
    }
    
    fillAddress(place) {
        const addressComponents = this.extractAddressComponents(place);
        
        // Fill form fields using enum values
        Object.entries(AddressItemEnum).forEach(([key, enumValue]) => {
            const input = document.querySelector(`[name="${enumValue}"]`);
            if (input && addressComponents[enumValue]) {
                input.value = addressComponents[enumValue];
                
                // Trigger change event for Livewire/Vue reactivity
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    }
    
    extractAddressComponents(place) {
        const components = {};
        
        place.address_components.forEach(component => {
            const types = component.types;
            
            if (types.includes('street_number')) {
                components[AddressItemEnum.STREET_NUMBER] = component.long_name;
            }
            if (types.includes('route')) {
                components[AddressItemEnum.ROUTE] = component.long_name;
            }
            if (types.includes('locality')) {
                components[AddressItemEnum.LOCALITY] = component.long_name;
            }
            if (types.includes('administrative_area_level_3')) {
                components[AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_3] = component.long_name;
            }
            if (types.includes('administrative_area_level_2')) {
                components[AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_2] = component.short_name;
            }
            if (types.includes('administrative_area_level_1')) {
                components[AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_1] = component.long_name;
            }
            if (types.includes('country')) {
                components[AddressItemEnum.COUNTRY] = component.short_name;
            }
            if (types.includes('postal_code')) {
                components[AddressItemEnum.POSTAL_CODE] = component.long_name;
            }
        });
        
        // Add geocoding data
        if (place.geometry) {
            components[AddressItemEnum.LATITUDE] = place.geometry.location.lat();
            components[AddressItemEnum.LONGITUDE] = place.geometry.location.lng();
        }
        
        components[AddressItemEnum.FORMATTED_ADDRESS] = place.formatted_address;
        components[AddressItemEnum.PLACE_ID] = place.place_id;
        
        return components;
    }
}

// Export for use in other components
export default AddressAutocomplete;
```

### Vue.js Address Form Component

```vue
<!-- resources/js/components/AddressForm.vue -->
<template>
    <div class="address-form">
        <!-- Contact Section -->
        <div class="form-section">
            <h3>Contact</h3>
            <div class="form-group">
                <label :for="AddressItemEnum.PHONE">
                    {{ labels[AddressItemEnum.PHONE] }}
                </label>
                <input
                    :id="AddressItemEnum.PHONE"
                    v-model="form.phone"
                    type="tel"
                    :placeholder="placeholders[AddressItemEnum.PHONE]"
                />
            </div>
        </div>
        
        <!-- Street Address Section -->
        <div class="form-section">
            <h3>Street Address</h3>
            <div class="form-row">
                <div class="form-group">
                    <label :for="AddressItemEnum.ROUTE">
                        {{ labels[AddressItemEnum.ROUTE] }}
                    </label>
                    <input
                        :id="AddressItemEnum.ROUTE"
                        v-model="form.route"
                        type="text"
                        :placeholder="placeholders[AddressItemEnum.ROUTE]"
                        ref="routeInput"
                    />
                </div>
                <div class="form-group">
                    <label :for="AddressItemEnum.STREET_NUMBER">
                        {{ labels[AddressItemEnum.STREET_NUMBER] }}
                    </label>
                    <input
                        :id="AddressItemEnum.STREET_NUMBER"
                        v-model="form.street_number"
                        type="text"
                        :placeholder="placeholders[AddressItemEnum.STREET_NUMBER]"
                    />
                </div>
            </div>
        </div>
        
        <!-- Administrative Section -->
        <div class="form-section">
            <h3>Administrative Area</h3>
            <div class="form-grid">
                <div class="form-group" v-for="field in administrativeFields" :key="field">
                    <label :for="field">{{ labels[field] }}</label>
                    <input
                        :id="field"
                        v-model="form[field]"
                        type="text"
                        :placeholder="placeholders[field]"
                    />
                </div>
            </div>
        </div>
        
        <!-- Geocoding Display -->
        <div class="form-section" v-if="form.latitude && form.longitude">
            <h3>Location</h3>
            <div class="coordinates">
                <span>Lat: {{ form.latitude }}</span>
                <span>Lng: {{ form.longitude }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import AddressItemEnum from '../enums/AddressItemEnum';
import AddressAutocomplete from './AddressAutocomplete';

export default {
    name: 'AddressForm',
    props: {
        initialAddress: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            AddressItemEnum,
            form: { ...this.getDefaultAddress(), ...this.initialAddress },
            labels: {},
            placeholders: {},
            autocomplete: null
        };
    },
    computed: {
        administrativeFields() {
            return [
                AddressItemEnum.LOCALITY,
                AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_3,
                AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_2,
                AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_1
            ];
        }
    },
    mounted() {
        this.loadTranslations();
        this.initAutocomplete();
    },
    methods: {
        getDefaultAddress() {
            const defaults = {};
            Object.values(AddressItemEnum).forEach(value => {
                defaults[value] = '';
            });
            return defaults;
        },
        
        async loadTranslations() {
            // Load translations based on current locale
            const locale = document.documentElement.lang || 'en';
            const response = await fetch(`/geo/translations/${locale}`);
            const translations = await response.json();
            
            Object.values(AddressItemEnum).forEach(value => {
                this.labels[value] = translations[`${value}.label`] || value;
                this.placeholders[value] = translations[`${value}.placeholder`] || '';
            });
        },
        
        initAutocomplete() {
            if (this.$refs.routeInput) {
                this.autocomplete = new AddressAutocomplete(this.$refs.routeInput, {
                    country: this.form.country || 'IT'
                });
            }
        },
        
        emitUpdate() {
            this.$emit('update:address', this.form);
        }
    },
    watch: {
        form: {
            handler() {
                this.emitUpdate();
            },
            deep: true
        }
    }
};
</script>

<style scoped>
.address-form {
    max-width: 800px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    margin-bottom: 1rem;
    color: #374151;
    font-size: 1.125rem;
    font-weight: 600;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.form-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.coordinates {
    display: flex;
    gap: 1rem;
    padding: 0.75rem;
    background-color: #f3f4f6;
    border-radius: 0.375rem;
    font-family: monospace;
}

@media (max-width: 768px) {
    .form-row,
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
```

## Validation Integration

### Client-side Validation

```javascript
// resources/js/validation/addressValidation.js
import AddressItemEnum from '../enums/AddressItemEnum';

class AddressValidator {
    static rules = {
        [AddressItemEnum.POSTAL_CODE]: {
            IT: /^\d{5}$/,
            DE: /^\d{5}$/,
            FR: /^\d{5}$/,
            GB: /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i,
            US: /^\d{5}(-\d{4})?$/
        },
        [AddressItemEnum.PHONE]: {
            IT: /^\+39\s?\d{3}\s?\d{3,4}\s?\d{3,4}$/,
            DE: /^\+49\s?\d{3,4}\s?\d{7,8}$/,
            FR: /^\+33\s?\d{1,2}\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/,
            GB: /^\+44\s?\d{3,4}\s?\d{6}$/,
            US: /^\+1\s?\d{3}\s?\d{3}\s?\d{4}$/
        }
    };
    
    static validate(field, value, country = 'IT') {
        const fieldRules = this.rules[field];
        
        if (!fieldRules || !fieldRules[country]) {
            return true; // No validation rule for this country
        }
        
        return fieldRules[country].test(value);
    }
    
    static validateAddress(address, country = 'IT') {
        const errors = {};
        
        // Validate postal code
        if (address[AddressItemEnum.POSTAL_CODE]) {
            if (!this.validate(AddressItemEnum.POSTAL_CODE, address[AddressItemEnum.POSTAL_CODE], country)) {
                errors[AddressItemEnum.POSTAL_CODE] = `Invalid postal code format for ${country}`;
            }
        }
        
        // Validate phone
        if (address[AddressItemEnum.PHONE]) {
            if (!this.validate(AddressItemEnum.PHONE, address[AddressItemEnum.PHONE], country)) {
                errors[AddressItemEnum.PHONE] = `Invalid phone format for ${country}`;
            }
        }
        
        // Validate required fields
        const requiredFields = [
            AddressItemEnum.ROUTE,
            AddressItemEnum.ADMINISTRATIVE_AREA_LEVEL_3,
            AddressItemEnum.POSTAL_CODE
        ];
        
        requiredFields.forEach(field => {
            if (!address[field]) {
                errors[field] = `${field} is required`;
            }
        });
        
        return errors;
    }
}

export default AddressValidator;
```

## Styling Guidelines

### Tailwind CSS Classes

```scss
// resources/css/address-form.scss
.address-form {
    // Use colors from enum
    .field-#{AddressItemEnum.PHONE} {
        @apply border-l-4 border-l-primary;
    }
    
    .field-#{AddressItemEnum.ROUTE} {
        @apply border-l-4 border-l-success;
    }
    
    .field-#{AddressItemEnum.POSTAL_CODE} {
        @apply border-l-4 border-l-warning;
    }
    
    // Icon positioning
    .input-with-icon {
        @apply relative;
        
        .icon {
            @apply absolute left-3 top-1/2 transform -translate-y-1/2;
        }
        
        input {
            @apply pl-10;
        }
    }
}
```

## Livewire Integration

### Livewire Address Component

```php
<?php

namespace Modules\Geo\Livewire;

use Livewire\Component;
use Modules\Geo\Enums\AddressItemEnum;

class AddressForm extends Component
{
    public $address = [];
    public $country = 'IT';
    
    protected $rules = [];
    
    public function mount($address = [])
    {
        $this->address = $address ?: $this->getDefaultAddress();
        $this->setValidationRules();
    }
    
    public function render()
    {
        return view('geo::livewire.address-form');
    }
    
    public function updatedCountry($value)
    {
        $this->setValidationRules();
        $this->validate();
    }
    
    public function geocode()
    {
        // Geocoding logic here
        $this->dispatch('addressGeocoded', $this->address);
    }
    
    private function getDefaultAddress()
    {
        return array_fill_keys(array_map(fn($enum) => $enum->value, AddressItemEnum::cases()), '');
    }
    
    private function setValidationRules()
    {
        $this->rules = [
            AddressItemEnum::ROUTE->value => 'required|string|max:255',
            AddressItemEnum::ADMINISTRATIVE_AREA_LEVEL_3->value => 'required|string|max:255',
            AddressItemEnum::POSTAL_CODE->value => [
                'required',
                'regex:' . $this->getPostalCodeRegex()
            ],
            AddressItemEnum::PHONE->value => [
                'nullable',
                'regex:' . $this->getPhoneRegex()
            ],
        ];
    }
    
    private function getPostalCodeRegex()
    {
        return match($this->country) {
            'IT' => '/^\d{5}$/',
            'DE' => '/^\d{5}$/',
            'GB' => '/^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/i',
            default => '/^.+$/',
        };
    }
    
    private function getPhoneRegex()
    {
        return match($this->country) {
            'IT' => '/^\+39\s?\d{3}\s?\d{3,4}\s?\d{3,4}$/',
            'DE' => '/^\+49\s?\d{3,4}\s?\d{7,8}$/',
            'GB' => '/^\+44\s?\d{3,4}\s?\d{6}$/',
            default => '/^.+$/',
        };
    }
}
```

## Best Practices

1. **Always use enum values** instead of hardcoded strings in frontend code
2. **Load translations dynamically** based on user locale
3. **Implement country-specific validation** for postal codes and phones
4. **Use semantic HTML5** with proper labels for accessibility
5. **Provide visual feedback** for validation errors
6. **Optimize for mobile** with responsive design
7. **Cache enum translations** to avoid repeated API calls
8. **Use debouncing** for autocomplete inputs
9. **Implement proper error handling** for geocoding failures
10. **Test across browsers** for consistent behavior