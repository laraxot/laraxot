# Time Tracking Development Guide

This directory contains development guides for the time tracking and attendance management system.

## Overview

The time tracking system provides:
- Real-time clock-in/clock-out functionality
- Break time management
- Attendance monitoring
- Work hour calculations
- Presence tracking widgets

## Implementation Status

| Component | Status | Notes |
|-----------|--------|-------|
| WorkHour Model | ✅ Complete | Core time tracking model |
| TimeTrackingWidget | ✅ Complete | Real-time dashboard widget |
| TodayPresenceWidget | ✅ Complete | Presence monitoring widget |
| Attendance Reports | 🚧 In Progress | Reporting functionality |
| Mobile Clock-in | 📋 Planned | Mobile PWA integration |

## Development Files

- **timbrature/** - Time tracking implementation details
- **attendance.md** - Attendance management guide
- **implementation.md** - Technical implementation

## Key Components

- `WorkHour` - Time tracking records
- `TimeTrackingWidget` - Dashboard time tracking
- `TodayPresenceWidget` - Real-time presence display
- `AttendanceReport` - Reporting system

## Widget Architecture

The time tracking widgets follow XotBaseWidget patterns:
- Extend `XotBaseWidget` for Laraxot compliance
- Implement required `getFormSchema()` method
- Use proper translation files
- Real-time polling for live updates

## Related Documentation

- [Features: Work Hour Implementation](../../features/workhour_implementation.md)
- [Architecture: Technical Architecture](../../architecture/technical_architecture.md)
- [Implementation: Workflows](../../implementation/workflows_and_best_practices.md)
