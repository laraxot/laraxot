# Geo Module Philosophy

## Zen of Geo

Geography is not metadata—it's a first-class citizen in TechPlanner. Every location, address, and coordinate is treated as a domain entity worthy of dedicated business logic, not relegated to a text field or generic JSON blob.

The Geo module provides authoritative, queryable, and reusable geographic infrastructure: unified address hierarchy (region → province → locality → postal code), coordinate precision management, distance calculations, and location-based clustering. All other modules consume this capability through clean interfaces, never duplicate it.

---

## Architecture

### Geographic Hierarchy

The module implements a four-tier geographic hierarchy aligned to Italian administrative divisions:

1. **Region** (`Regione`): Top level, sourced from `comuni.json` via Sushi
2. **Province** (`Provincia`): Mid level, linked to regions
3. **Locality** (`Comune`): City/town level, includes postal codes and metadata
4. **Address**: User-facing address record, polymorphic (can belong to Employee, Client, Organization, etc.)
5. **Coordinates**: Latitude/longitude pair, stored on Address; immutable during normal operations

### Data Flow

```
Comuni.json (static seed data)
    ↓
Sushi Models (Region, Province, Locality, Comune)
    ↓
Filament Forms (cascading selectors: Region → Province → Locality → PostalCode)
    ↓
Address Model (captures: route, street_number, coordinates, type)
    ↓
HasAddresses Trait (polymorphic relation: model →morphMany→ addresses)
    ↓
Client/Employee/Organization Models (consume via trait)
```

### 14 Address Form Fields

The Address form implements Schema.org PostalAddress with 14 primary fields:

- `name`: User-friendly label (e.g., "Home", "Office Milano")
- `country`: ISO country code (default: "IT")
- `administrative_area_level_1`: Region code (from Sushi)
- `administrative_area_level_2`: Province code (from Sushi)
- `administrative_area_level_3`: Unused in current design; reserved for future subregion support
- `locality`: Comune code (from Sushi)
- `postal_code`: CAP (from Sushi, cascaded on locality selection)
- `route`: Street name or address line 1
- `street_number`: Civic number (nullable, some addresses lack this)
- `latitude`: WGS84, ±90 range, validated
- `longitude`: WGS84, ±180 range, validated
- `formatted_address`: Human-readable concatenation (e.g., "Via Roma 42, 20100 Milano, MI, Lombardia, IT")
- `type`: Enum (HOME, WORK, BILLING, SHIPPING, LEGAL, OTHER)
- `is_primary`: Boolean, only one per model_type+model_id pair

### One Address Column Per Query

Addresses are stored in a single `addresses` table with `model_type` and `model_id` for polymorphic linking. Queries eager-load via `.with('addresses')` or `.with('primaryAddress')`. No denormalized address columns in other tables—query through the relation.

---

## Design Patterns

### Immutable Coordinates

Once geocoded (lat/lng populated), coordinates change only via explicit bulk actions (e.g., `UpdateCoordinatesBulkAction`). Normal address edits (changing street name) do not auto-recalculate—this prevents silent data drift and expensive external API calls.

### Clustering for Performance

`ClusterLocationsAction` groups nearby locations (default: ≤1 km) and returns cluster centers. Used in TechPlanner appointments listing: instead of 500 markers on a map, show 20 clusters. Cluster centers recalculate as the mean of assigned point coordinates.

### HasAddresses Trait

Models that store addresses use the `HasAddresses` trait, which provides:

- `.addresses()` → MorphMany relation
- `.primaryAddress()` → MorphOne filtered to `is_primary = true`
- `.homeAddress()`, `.workAddress()`, etc. → Type-specific MorphOne scopes
- `.addAddress(data)` → Create new address, optionally set as primary
- `.setPrimaryAddress(address)` → Atomically promote one, demote others
- `.getAddressesByType(enum)` → Fetch all addresses of a type

### HandlesCoordinates Trait

Utility trait for coordinate math:

- `.areValidCoordinates(lat, lng)` → Validates WGS84 bounds (latitude ±90, longitude ±180)
- `.calculateDistance(lat1, lon1, lat2, lon2)` → Haversine formula, returns km
- `.formatCoordinates(lat, lng, decimals)` → Pretty-print with custom precision

Used by Actions that perform distance filtering or clustering.

### Schema.org PostalAddress Conversion

Address model has `.toSchemaOrg(): array` method, returning a Schema.org-compliant PostalAddress object. Only populated fields are included—null values are omitted. Used for structured data in HTML head or API responses.

---

## Integration Points

### TechPlanner Module

**Use case**: Appointment scheduling at specific addresses.

- Clients/Locations linked via `HasAddresses`
- Appointment can filter nearby locations: `Address::nearby($lat, $lng, $radiusKm)`
- Bulk action: `UpdateCoordinatesBulkAction` to geocode client addresses from CSV import
- Mapbox/Leaflet widget displays appointment clusters

**Data flow**: Client → `addresses()` → Address → coordinates → TechPlanner appointment form

### Employee Module

**Use case**: Work location and home address for payroll, travel time calculations.

- Employee model uses `HasAddresses` trait
- `.workAddress()` and `.homeAddress()` MorphOne relations
- Home address used in: HR onboarding, commute distance calculation
- Work address used in: Site assignments, location-based dispatch (Notify module)

**Constraint**: Only one primary address per type; business logic enforces this.

### Notify Module

**Use case**: Location-based notification dispatch (e.g., "send reminder 1 hour before appointment").

- Notification eligibility filtered by address proximity
- Uses `Address::nearby()` scope to find recipients within radius
- Distance pre-calculated via batch `CalculateDistanceAction` to avoid real-time overhead

**Performance**: Addresses are clustered by region; queries filter region first, then distance—avoids full-table scans.

---

## Best Practices

### Clustering Caching

`ClusterLocationsAction` results are cached in Redis by hash of location set + max distance. Invalidate on:
- New address added via `HasAddresses::addAddress()`
- Coordinates updated via `UpdateCoordinatesFromAddressAction`
- Address deleted

Do not cache indefinitely—locations are live data.

### Never Compute Distance on Client

Distance calculations (Haversine, bearing, etc.) happen server-side in Actions. Client receives pre-computed values or cluster centers. Reason: avoid duplicating geographic knowledge and ensure consistency.

### Coordinate Precision Rules

- **Display**: 6 decimals (±0.11 meters precision)
- **Storage**: Float64 (±90/±180 range)
- **Calculation**: Use Double precision for Haversine; round only for display
- **User input**: Accept address text or map pin, resolve via geocoding Action

### Avoid Denormalization in Other Tables

Do not copy `latitude`, `longitude` into Employee, Client, or Order tables. Always query through `.address()` relation. Single source of truth in Address table prevents inconsistency.

---

## Anti-Patterns to Avoid

### Direct Geocoding in Filament Action Handlers

**Wrong**:
```php
public function bulkUpdateCoordinates(Collection $records): void
{
    foreach ($records as $record) {
        $address = $record->primaryAddress;
        $coords = app(GetCoordinatesAction::class)->execute($address->formatted_address);
        $address->update(['latitude' => $coords['lat'], 'longitude' => $coords['lng']]);
    }
}
```

**Right**: Use `UpdateCoordinatesBulkAction` from Geo module. Separation of concerns: UI action delegates to queueable business action.

### Client-Side Distance Filtering

Never compute "find all employees within 5 km" on the frontend. Always use `Address::nearby()` scope server-side. Reason: coordinate precision and network latency make client-side math unreliable.

### Storing Duplicate Address Text

Do not copy `formatted_address` into order, invoice, or appointment records. Link via Address model and fetch relation-side. Address updates (if geocoding service corrects a typo) then flow to all records.

---

## Roadmap

### Phase 1: External Geocoding Services (In Progress)

- **Google Maps API**: Reverse geocoding, elevation data, place autocomplete
- **Mapbox API**: Alternative provider, lower cost for batch jobs
- **Here Maps**: Fleet tracking integration for Notify module

Implementation: Provider interface pattern (Strategy), dependency injection, API key rotation.

### Phase 2: PostGIS Integration (Future)

- Move geographic queries to PostgreSQL PostGIS extension
- Native `ST_DWithin`, `ST_Centroid` for clustering
- Performance: O(log n) with spatial indexes vs. O(n) Haversine loops
- Gradual migration: keep business Actions unchanged, swap data layer

### Phase 3: Timezone Detection (Future)

- Auto-detect timezone from Address coordinates (via timezone API or offline database)
- Employee model: timezone field populated from `workAddress()`
- Notify module: schedule notifications in employee's timezone, not UTC

### Phase 4: Boundary Queries (Backlog)

- Polygon support: service areas, delivery zones
- `Address::withinPolygon(polygon)` scope
- Used by: Notify dispatch filters, TechPlanner zone management

---

## Security Considerations

### Location Privacy

Address history is sensitive: it reveals patterns (work location, home, frequent stops). Constraints:

- Address records inherit soft-delete from BaseModel—never hard-delete, only logical deletion
- Audit trail: `created_by`, `updated_by`, `deleted_by` track who modified location data
- RLS policies (future): only Employee can see their own addresses; Manager can see team addresses if authorized
- API responses: mask coordinates to ±2 decimal precision (±1.1 km) for non-owner requests

### Query Scope Isolation

Queries on Address must always scope to the owning model type. No global Address lists exposed in API; every Address is accessed via:
- `.with('primaryAddress')` (on parent model)
- `Address::whereModelType('Modules\\\\Employee\\\\Models\\\\Employee')` (internal only)

### External API Keys

Geocoding provider keys stored in `.env`:
- `GEO_GOOGLE_MAPS_API_KEY`
- `GEO_MAPBOX_ACCESS_TOKEN`
- Never commit keys; rotate quarterly
- Rate limit: Geo module batches requests, implements exponential backoff for quota exhaustion

### Input Validation

Address form uses cascading Selectors (Filament): user cannot enter free-text region/province/locality. All inputs validated against Sushi models (comuni.json). Coordinates validated by range (±90, ±180) and precision (not NaN, not Infinity).

---

## Testing Strategy

### Unit Tests

- `Geo/tests/Unit/Traits/TraitsTest.php`: Validates HasAddresses API
- `Geo/tests/Unit/AddressModelTest.php`: Scopes, accessors, schema output
- `Geo/tests/Unit/GeocodingBusinessLogicTest.php`: Distance, clustering, coordinate validation

### Feature Tests

- Address CRUD via AddressResource Filament
- Cascading selector behavior (Province populates when Region selected)
- Bulk coordinate update with job queueing
- Nearby query with realistic data

### Factories

- `AddressFactory`: Create test addresses with realistic data
- `ComuneFactory`: Seed test comuni (if needed)
- `RegionFactory`, `ProvinceFactory`: Generated from comuni.json

---

## Compliance

- **PHPStan Level 10**: All Models, Traits, Actions pass strict type checking
- **Schema.org Compliance**: Address.toSchemaOrg() aligns with schema.org/PostalAddress
- **Italy-Specific**: ISTAT codes, CAP format, province sigla all correct
- **Accessibility**: Map widgets use ARIA labels; coordinate inputs accept keyboard input

---

## Versioning

- **Current**: Geo v2.0 (Schema.org integration, Sushi-based hierarchy)
- **Breaking Change History**: v1.0 → v2.0 moved from custom Region/Province models to Sushi; HasAddresses API unchanged
- **Deprecations**: None active; old coordinate storage (text fields) removed in v2.0

---

## Quick Reference

| Need | Action/Model | Example |
|------|--------------|---------|
| Add address to model | `HasAddresses::addAddress()` | `$employee->addAddress(['route' => 'Via Roma', ...])` |
| Find nearby | `Address::nearby()` | `Address::nearby(45.46, 9.18, 10)->get()` |
| Format for display | `$address->full_address` | "Via Roma 42, 20100 Milano, MI, Lombardia, IT" |
| Schema.org JSON | `$address->toSchemaOrg()` | `json_encode($address->toSchemaOrg())` |
| Bulk geocode | `UpdateCoordinatesBulkAction` | Via Filament bulk action on Address resource |
| Distance (km) | `HandlesCoordinates::calculateDistance()` | `$this->calculateDistance(45.46, 9.18, 44.49, 11.34)` |
| Cluster locations | `ClusterLocationsAction` | `app(ClusterLocationsAction::class)->execute($locations, 1.0)` |

---

**Version**: 2.0  
**Status**: Production  
**Last Updated**: 2026-09  
**Maintainers**: Geo module team  
**Related**: TechPlanner (appointments), Employee (work location), Notify (dispatch)
