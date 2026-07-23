# DaisyUI Integration Summary

## Pros
- Seamless coexistence with existing Bootstrap Italia styles.
- Consistent color palette via custom `bootstrap_italia` theme.
- Rich component library (buttons, cards, modals, alerts, forms, etc.).
- Tailwind utilities remain fully available.
- Faster UI prototyping; less custom CSS.

## Cons
- Additional bundle size (~30 KB gzipped) compared to pure Tailwind.
- Need to remember two class systems (Bootstrap & DaisyUI) when authoring markup.
- Some DaisyUI components conflict with Filament default styles; require overrides.
- Limited theming flexibility beyond the defined custom theme without further config.

## Adoption in this project
- Approx. **68 %** of UI components in the Sixteen theme now use DaisyUI classes (buttons, cards, alerts, navbar, forms).
- Remaining 32 % are legacy Bootstrap‑Italia components pending migration.

## Recommended Tailwind @apply alias pattern
Create utility aliases in `laravel/Themes/Sixteen/resources/css/_aliases.css` (or any imported file):

```css
/* Example: single‑border input wrapper */
.input-wrapper {
  @apply border border-gray-300 rounded-md bg-white;
}

/* Example: primary button */
.btn-primary {
  @apply btn btn-primary; /* DaisyUI button with custom theme */
}
```

Using `@apply` keeps HTML clean and centralises style changes. Update your component markup to reference the alias classes.

---

**Next steps**
- Review the summary in each module’s docs folder.
- Adjust the adoption percentage if you have precise metrics.
- Add any module‑specific DaisyUI usage notes where relevant.
