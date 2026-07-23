# Activity Resource Documentation

## Overview
The `ActivityResource` class manages activity logging in the Filament admin panel. It provides a user interface for viewing and managing activity logs.

## Form Fields

The resource includes the following form fields:

1. **Log Name**
   - Required field
   - Max length: 255 characters
   - Purpose: Identifies the type or category of the log entry

2. **Description**
   - Required field
   - Max length: 255 characters
   - Purpose: Describes the activity that occurred

3. **Subject Type**
   - Required field
   - Max length: 255 characters
   - Purpose: Specifies the type of model being logged (e.g., User, Post)

4. **Subject ID**
   - Required field
   - Numeric value
   - Purpose: References the specific model instance being logged

5. **Causer Type** (Optional)
   - Max length: 255 characters
   - Purpose: Identifies the type of model that caused the activity

6. **Causer ID** (Optional)
   - Numeric value
   - Purpose: References the specific model instance that caused the activity

7. **Properties**
   - Key-value pairs
   - Full column span
   - Purpose: Stores additional metadata about the activity

8. **Batch UUID** (Optional)
   - Max length: 36 characters
   - Purpose: Groups related activities together in batch operations

## Recent Changes

- Resolved merge conflicts in `ActivityResource.php`
- Updated form schema to use the newer Filament 3.x form builder syntax
- Added helpful placeholders and helper text for all form fields
- Improved code organization and documentation
- Added proper type hints and return type declarations
