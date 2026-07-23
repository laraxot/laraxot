# Address Data Parameter Standardization

## Overview
Standardized parameter naming across address-related data classes to ensure consistency and prevent errors.

## Parameter Naming Standards

### AddressData Parameters
```php
public function __construct(
    public readonly float $latitude,
    public readonly float $longitude,
    public readonly ?string $country = null,
    public readonly ?string $city = null,
    public readonly ?string $country_code = null,
    public readonly ?int $postal_code = null,  // ⚠️ Note: integer type
    public readonly ?string $locality = null,
    public readonly ?string $county = null,
    public readonly ?string $street = null,
    public readonly ?string $street_number = null,
    public readonly ?string $district = null,
    public readonly ?string $state = null,
)
```

### Parameter Mapping
When converting from external data sources to AddressData:

1. Postal Code:
   ```php
   // ✓ Correct
   postal_code: $data->postcode ? (int) $data->postcode : null,
   
   // ❌ Incorrect
   postcode: $data->postcode,  // Wrong parameter name
   postal_code: $data->postcode,  // Missing type conversion
   ```

2. Street Number:
   ```php
   // ✓ Correct
   street_number: $data->housenumber,
   
   // ❌ Incorrect
   housenumber: $data->housenumber,  // Wrong parameter name
   ```

## Type Conversions

### Required Conversions
1. Postal Code: string → int
   ```php
   // ✓ Correct with null handling
   postal_code: $data->postcode ? (int) $data->postcode : null,
   
   // ✓ Correct with default
   postal_code: (int) ($data->postcode ?? 0),
   
   // ❌ Incorrect
   postal_code: $data->postcode,  // Missing conversion
   postal_code: (int) $data->postcode,  // Missing null handling
   ```

2. Coordinates: array → float
   ```php
   // ✓ Correct
   latitude: (float) $coordinates['latitude'],
   longitude: (float) $coordinates['longitude'],
   
   // ❌ Incorrect
   latitude: $coordinates['latitude'],  // Missing conversion
   ```

## Data Source Mappings

### Photon API
```php
return new AddressData(
    latitude: $photonData->coordinates['latitude'],
    longitude: $photonData->coordinates['longitude'],
    country: $photonData->country,
    city: $photonData->city,
    postal_code: $photonData->postcode ? (int) $photonData->postcode : null,
    street: $photonData->street,
    street_number: $photonData->housenumber
);
```

### OpenStreetMap API
```php
return new AddressData(
    latitude: (float) $data['lat'],
    longitude: (float) $data['lon'],
    city: $address['city'] ?? $address['town'] ?? null,
    postal_code: isset($address['postcode']) ? (int) $address['postcode'] : null,
    street: $address['road'] ?? null,
    street_number: $address['house_number'] ?? null
);
```

## Best Practices

### Parameter Names
1. Always use snake_case for parameter names
2. Use consistent naming across all address-related classes
3. Follow AddressData parameter names exactly
4. Document any parameter name mappings

### Type Safety
1. Always convert string postal codes to integers
2. Handle null values appropriately
3. Convert coordinate values to floats
4. Provide appropriate defaults when needed

### Error Handling
1. Validate input data before conversion
2. Handle missing or invalid data gracefully
3. Log conversion errors for debugging
4. Return null for invalid addresses

## Common Issues

### Parameter Name Mismatches
- Using 'postcode' instead of 'postal_code'
- Using 'housenumber' instead of 'street_number'
- Using inconsistent casing (e.g., 'postalCode')

### Type Conversion Issues
- Not converting postal codes to integers
- Not handling null values
- Missing coordinate type conversions
- Incorrect default values

## Related Documentation
- AddressData Class Documentation
- Photon API Integration Guide
- OpenStreetMap Integration Guide
- Data Type Conversion Standards
