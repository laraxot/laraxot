# MCP and Infrastructure Integration - Theme Two

## Infrastructure Update (2026-02-06)
Theme Two has been updated to use:
- **Filament v5.1**: Core components (tables, schemas, forms, etc.) are now required individually for better performance and modularity.
- **Tailwind CSS v4**: Integrated via `@tailwindcss/vite` plugin.
- **Vite 6**: Optimized build process.

### Asset Loading
Assets are loaded using the corrected modular `@vite` directive:
```blade
@vite('resources/css/app.css', 'themes/Two')
@vite('resources/js/app.js', 'themes/Two')
```

## Recommended MCP Servers
To improve the UI/UX and beauty of Theme Two, the following MCP servers are recommended:

### Design & UI
- **daisyUI Blueprint**: Provides high-quality UI components and rapid prototyping capabilities.
  - Config: `{ "command": "npx", "args": ["-y", "daisyui@latest", "mcp"] }`
- **flowbite-mcp**: Excellent for Figma integration and consistent Tailwind components.
  - Config: `{ "command": "npx", "args": ["-y", "flowbite-mcp"] }`
- **Magic UI MCP**: Recommended for adding high-end animations and "visual flair" to theme components.

### Development Utilities
- **Tailwind Gemini MCP**: Specifically optimized for Gemini-based agents to provide accurate Tailwind v4 utility suggestions.
- **shadcn MCP**: Useful for adding specialized components into the theme's resource directory.

## Workflow
1. **Research**: Use `daisyUI` or `Flowbite` MCPs to explore component patterns.
2. **Implement**: Apply components using Tailwind v4 utilities.
3. **Verify**: Run `npm run build && npm run copy` within `laravel/Themes/Two` to see changes.

## Related Documentation
- [README.md](README.md)
- [Complete Theme Analysis](analisi-completa-tema.md)
