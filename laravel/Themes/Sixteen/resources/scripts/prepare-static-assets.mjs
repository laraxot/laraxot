import { mkdir, readFile, writeFile } from 'node:fs/promises';
import { dirname, resolve } from 'node:path';

const target = resolve(
    import.meta.dirname,
    '../static/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg',
);
const source =
    'https://cdn.jsdelivr.net/npm/bootstrap-italia@2.8.8/dist/svg/sprites.svg';

try {
    const existing = await readFile(target, 'utf8');
    if (existing.includes('<svg') && existing.length > 1000) {
        process.exit(0);
    }
} catch {
    // The first build obtains the pinned Design Comuni icon sprite below.
}

const response = await fetch(source);
if (!response.ok) {
    throw new Error(`Unable to download Design Comuni sprite (${response.status})`);
}

const sprite = await response.text();
if (!sprite.includes('<svg') || sprite.length <= 1000) {
    throw new Error('Downloaded sprite is not a valid SVG asset');
}

await mkdir(dirname(target), { recursive: true });
await writeFile(target, sprite, 'utf8');
