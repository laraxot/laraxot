# Tab System Component

## Features
- Vertical/horizontal tabs
- Responsive behavior
- Keyboard navigation
- ARIA compliant
- State management

## Usage

```blade
<x-bootstrap-italia::tabs 
    type="horizontal"
    active="tab1"
>
    <x-bootstrap-italia::tab 
        id="tab1" 
        label="First Tab"
    >
        Tab 1 content
    </x-bootstrap-italia::tab>
    
    <x-bootstrap-italia::tab 
        id="tab2" 
        label="Second Tab"
    >
        Tab 2 content
    </x-bootstrap-italia::tab>
</x-bootstrap-italia::tabs>
```

## Props

### Tabs Container
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| type | horizontal/vertical | horizontal | Tab orientation |
| active | string | null | Initially active tab ID |

### Individual Tab
| Prop | Type | Default | Description |
|------|------|---------|-------------|
| id | string | required | Unique tab identifier |
| label | string | required | Tab label text |
| disabled | bool | false | Disable tab interaction |

## Related Components
- [Navigation System](/laravel/Themes/Sixteen/docs/components/navigation.md)
- [Accessibility Guidelines](/laravel/Themes/Sixteen/docs/accessibility.md)
- [JavaScript Behaviors](/laravel/Themes/Sixteen/docs/javascript-behaviors.md)
- [Responsive Design](/laravel/Themes/Sixteen/docs/responsive-design.md)
- [State Management](/laravel/Themes/Sixteen/docs/state-management.md)

## Implementation Notes
- Follows WAI-ARIA tabs pattern
- Mobile-responsive design
- Supports keyboard navigation (arrow keys, Home/End)
- Uses Bootstrap Italia styling

> Last updated: {{current_date}}
