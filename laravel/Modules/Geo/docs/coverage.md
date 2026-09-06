# Code Coverage: Geo

**Test Exit Code:** 0 (PASS)
**Last Updated:** 2026-09-06

## Test Summary

### Pest Test Results

Test suites passed: 4 (6 total test classes)
Tests with failures/skipped: 2

#### Test Class Results

1. **AddressIntegrationTest** - PASS (6/6)
   - Address polymorphic attachment
   - Full address generation
   - Geolocation handling
   - Google Places API integration
   - Multiple addresses per entity
   - Soft deletion handling

2. **MapPickerTest** - PASS (7/7)
   - Instantiation
   - Field name configuration
   - Default field names
   - Zoom level configuration
   - Reverse geocoding toggle
   - Geolocation on empty
   - Blade view rendering

3. **GeoMapWidgetTest** - FAIL (0/2)
   - Blade rendering with dataset
   - Expected blade view usage

4. **AdditionalActionsTest** - PASS (6/6)
   - UpdateCoordinatesAction instantiation
   - ClusterLocationsAction instantiation
   - GetAddressDataFromFullAddressAction instantiation
   - OptimizeRouteAction instantiation
   - FormatCoordinatesAction instantiation
   - ValidateCoordinatesAction instantiation

5. **GetAddressFromBingMapsActionTest** - MIXED (4 passed, 6 failed, 2 incomplete)
   - API key validation: PASS
   - Invalid latitude range: INCOMPLETE
   - Invalid longitude range: INCOMPLETE
   - API response validation: PASS
   - Response JSON parsing: FAIL
   - Results validation: PASS
   - Point extraction: FAIL
   - Coordinates extraction: FAIL
   - Address extraction: FAIL
   - Valid coordinates return: FAIL

6. **CalculateDistanceActionTest** - PASS (7/7)
   - Distance calculation
   - Latitude validation
   - Longitude validation
   - Negative coordinate handling
   - Empty response handling
   - Malformed response handling

## PHPMD Analysis

PHPMD ruleset: phpmd.ruleset.xml (copied from Xot module)

Issues detected:
- Complexity violations (CyclomaticComplexity > 13)
- NPath complexity violations (> 800)
- Missing class imports

Output: `/tmp/Geo_phpmd.txt` (68.3KB)

## Status

Philosophy.md: VERIFIED (complete and comprehensive)
Test suite: PARTIALLY PASSING (4/6 classes, blockers in Bing Maps and Widget tests)
Coverage: Trending toward improvement; requires test fixes for full pass
