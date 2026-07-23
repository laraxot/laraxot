# Modal Component

## Features
- Stackable modals
- Fullscreen variants
- Multiple animations (fade, slide-up, slide-down, zoom)
- Responsive sizing (sm, md, lg, xl)
- Accessible (ARIA attributes)
- Dismissible with close button

## Usage

```blade
<x-bootstrap-italia::modal 
    id="example-modal"
    title="Example Modal"
    size="lg"
    animation="slide-up"
    stackable
>
    <p>Modal content goes here</p>
</x-bootstrap-italia::modal>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| id | string | auto-generated | Unique modal identifier |
| title | string | null | Modal header text |
| size | sm/md/lg/xl | md | Modal size variant |
| fullscreen | bool | false | Fullscreen mode |
| stackable | bool | false | Allow modal stacking |
| animation | fade/slide-up/slide-down/zoom | fade | Entry/exit animation |
| dismissible | bool | true | Show close button |

## Related Components
- [Alert System](/laravel/Themes/Sixteen/docs/components/alert.md)
- [Dialog System](/laravel/Themes/Sixteen/docs/components/dialog.md)
- [Form Elements](/laravel/Themes/Sixteen/docs/components/form-elements.md)
- [Accessibility Guidelines](/laravel/Themes/Sixteen/docs/accessibility.md)
- [Animation System](/laravel/Themes/Sixteen/docs/animation-system.md)

## Implementation Notes
- Uses Bootstrap Italia CSS framework
- Follows WCAG 2.1 AA accessibility standards
- Tested across modern browsers

> Last updated: {{current_date}}
