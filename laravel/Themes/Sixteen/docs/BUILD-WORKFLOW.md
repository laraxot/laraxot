# Sixteen Theme Build Workflow

## Overview
This document describes the proper build workflow for the Sixteen theme, emphasizing the use of `npm run build` and `npm run copy` instead of manual file copying.

## Why Use Build System

### 1. Asset Compilation
- Vite processes TypeScript, JavaScript, and CSS
- Minimizes and optimizes assets
- Generates hashed filenames for cache busting
- Handles dependencies and imports

### 2. Development Workflow
- Hot Module Replacement (HMR) in development
- Proper source maps for debugging
- Automatic dependency resolution

### 3. Production Optimization
- Code splitting and lazy loading
- Tree-shaking unused code
- CSS minification and optimization
- Asset compression (gzip)

## Build Commands

### 1. Build Assets
```bash
npm run build
```
This command:
- Compiles all TypeScript/JavaScript files
- Processes CSS with Tailwind
- Generates optimized assets in `./public/`
- Creates manifest for asset management

### 2. Copy to Laravel
```bash
npm run copy
```
This command:
- Copies built assets to Laravel public directory
- Copies Bootstrap Italia SVG sprites
- Copies theme images and assets
- Maintains proper directory structure

## Project Structure

```
Sixteen/
├── src/
│   └── components/          # TypeScript source files
├── resources/
│   ├── css/               # Tailwind CSS files
│   └── js/
│       └── app.js         # Main JavaScript entry
├── public/                # Build output directory
└── node_modules/          # Dependencies
```

## Common Mistakes to Avoid

### 1. Manual File Copying
❌ WRONG: Directly copying files to Laravel public
```bash
cp -r ./src/ ../../public_html/themes/Sixteen/
```

✅ CORRECT: Use npm scripts
```bash
npm run build && npm run copy
```

### 2. Skipping Build Step
❌ WRONG: Only running copy command
```bash
npm run copy
```

✅ CORRECT: Always run build first
```bash
npm run build && npm run copy
```

### 3. Modifying Public Files Directly
❌ WRONG: Editing files in `./public/`
✅ CORRECT: Edit source files in `./src/` and rebuild

## Development Workflow

### 1. Making Changes
1. Edit files in `src/` or `resources/` directories
2. Run `npm run build` to compile changes
3. Run `npm run copy` to deploy to Laravel
4. Refresh browser to see changes

### 2. Adding New Components
1. Create component in `src/components/`
2. Import in `resources/js/app.js`
3. Build and copy assets
4. Use component in Blade templates

### 3. Debugging Build Errors
- Check console for Vite build errors
- Verify TypeScript syntax
- Ensure imports are correct
- Check for circular dependencies

## File Watching (Development)

For development, use:
```bash
npm run dev
```

This provides:
- Watch mode for automatic rebuilding
- Hot Module Replacement
- Development server with HMR

## Production Deployment

For production:
```bash
npm run build
npm run copy
```

This ensures:
- Optimized production builds
- Minified assets
- Cache-busting filenames
- Proper file permissions

## Integration with Laravel

The build system integrates with Laravel by:
- Outputting assets to `public/themes/Sixteen/`
- Maintaining asset manifest for versioning
- Supporting Mix/Vite asset helpers
- Working with Laravel's routing system

## Troubleshooting

### Build Errors
1. Check TypeScript syntax
2. Verify imports exist
3. Clear node_modules if needed
4. Run `npm install` to update dependencies

### Missing Assets
1. Always run `npm run copy` after build
2. Check file permissions
3. Verify destination directory exists
4. Confirm asset paths are correct

### Performance Issues
1. Use production builds for deployment
2. Enable caching headers
3. Use CDN for static assets
4. Monitor bundle size