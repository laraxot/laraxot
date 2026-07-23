# Geo Module Icon Standardization

## Overview
Standardized navigation icons across the Geo module to provide consistent and intuitive visual indicators for map-related functionality.

## Icon Categories

### Core Navigation
- Dashboard: `heroicon-o-home`
  - Used for main dashboard
  - Indicates home/landing page

### Settings
- SettingPage: `heroicon-o-cog-6-tooth`
  - Used for module configuration
  - Manages API keys and settings

## Map Icon Standards

### Location-specific Pages
- Icon: `heroicon-o-map-pin`
- Used for:
  - LocationMap (specific locations display)
  - LatLng (coordinate points)
- Indicates precise location functionality

### General Map Pages
- Icon: `heroicon-o-map`
- Used for:
  - WebbingbrasilMap
  - DotswanMap
- Indicates general map functionality

### Global Map Pages
- Icon: `heroicon-o-globe-alt`
- Used for:
  - OSMMap
- Indicates global/world map functionality

## Navigation Structure
- All map-related pages grouped under 'Geo' navigation group
- Consistent icon usage across similar functionality
- Clear visual hierarchy based on map type

## Changes Made
1. LocationMap:
   - Updated icon from 'document-text' to 'map-pin'
   - Added to Geo navigation group

2. LatLng:
   - Updated icon from 'document-text' to 'map-pin'
   - Added to Geo navigation group

3. WebbingbrasilMap:
   - Updated icon from 'document-text' to 'map'
   - Added to Geo navigation group

4. OSMMap:
   - Maintained 'globe-alt' icon (already correct)
   - Verified Geo navigation group

5. DotswanMap:
   - Maintained 'map' icon (already correct)
   - Verified Geo navigation group

## Benefits
- Improved visual consistency
- Better user navigation
- Clear functional indicators
- Proper grouping of related features

## Icon Selection Guidelines
1. Use location-specific icons (`map-pin`) for precise coordinate/location features
2. Use general map icons (`map`) for map display/interaction features
3. Use global icons (`globe-alt`) for worldwide/OSM map features
4. Use settings icon (`cog-6-tooth`) for configuration pages
5. Use home icon (`home`) for dashboard/landing pages

## Related Documentation
- Heroicons Documentation
- Filament Navigation Management
- Module Geo Documentation
- Icon Usage Guidelines
