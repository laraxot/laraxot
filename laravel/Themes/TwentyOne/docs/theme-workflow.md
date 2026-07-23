# Theme Development & Build Workflow

## Core Philosophy
In the Laraxot ecosystem, themes are modular and isolated. Each theme manages its own assets, dependencies, and build pipeline to ensure maximum flexibility and performance.

## Asset Pipeline Architecture

1.  **Source Assets (`resources/`)**: Uncompiled CSS (Tailwind v4), JS, and images.
2.  **Local Build (`public/`)**: Vite compiles source assets into the theme's local `public` folder, generating a `manifest.json`.
3.  **Distribution (`../../../public_html/themes/{ThemeName}`)**: Compiled assets must be copied to the main application's public root to be accessible by the web server.

## Mandatory Build Procedure

Whenever theme assets are modified or the application reports a `ViteManifestNotFoundException`, follow these steps:

### 1. Environment Requirements
- **Node.js**: >= 20.x (Recommended for Vite 7+ and Tailwind v4)
- **npm**: >= 9.x

### 2. Execution Steps
Navigate to the theme directory:
```bash
cd laravel/Themes/TwentyOne
```

Run the pipeline:
```bash
# 1. Install dependencies
npm install

# 2. Build production assets
npm run build

# 3. Copy to public_html (Requires elevated permissions)
sudo npm run copy
```

## Troubleshooting: ViteManifestNotFoundException

### The "Why"
This error occurs because Laravel's `@vite` directive is configured to look for the manifest in `public_html/themes/TwentyOne/manifest.json`. If this file is missing or the directory is empty, the frontend will fail to render.

### Resolution
The resolution is not just running `npm run build`, but also ensuring `npm run copy` successfully populates the public distribution folder.

## Permissions & Security
The `npm run copy` command interacts with directories outside the `laravel/` root (`public_html`). 
- **Ownership**: Ensure `public_html/themes` is writable by the build user or use `sudo`.
- **Consistency**: Always run `npm run copy` after `npm run build` to keep the distribution in sync.

## Common Pitfalls
- **Node Version**: Running Vite 7 on Node 18 may produce warnings or errors. Always use the project-defined Node version.
- **Manifest Mismatch**: If you change the `statePath` or `public_html` location, update the `copy` script in `package.json`.
