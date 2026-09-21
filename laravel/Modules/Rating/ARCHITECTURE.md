# Rating Module Architecture

## Overview
The Rating module provides star ratings, reviews, and feedback mechanisms.

## Components
- **Rating Model**: Core rating entity
- **Review System**: User-submitted reviews with moderation
- **Aggregation**: Rating calculations and statistics
- **Display Components**: Star display widgets

## Features
- 1-5 star ratings
- Text reviews with moderation queue
- Rating aggregation (average, count)
- User-specific ratings (prevent duplicate)

## Integration
- Rateable trait for other models
- Filament admin interface for moderation
- Statistics dashboard widget
