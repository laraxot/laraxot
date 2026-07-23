# Lessons Learned - Footer Development


## Lesson 1: Footer Background - Use Inline Styles

### Problem
Tailwind gradient classes `bg-gradient-to-br from-[#color] via-[#color] to-[#color]` may not compile correctly, resulting in transparent backgrounds.

### Solution
Use inline CSS styles for footer backgrounds:

```blade
<!-- Wrong - May not compile -->
<footer class="bg-gradient-to-br from-[#1e40af] via-[#2563eb] to-[#1d4ed8]">

<!-- Correct - Always works -->
<footer style="background: linear-gradient(135deg, #1E5A96 0%, #164575 50%, #0d2d4d 100%);">
```

### Verification
Use MCP Puppeteer to verify background is visible:
```javascript
const footer = document.querySelector('footer');
const style = window.getComputedStyle(footer);
console.log(style.backgroundImage); // Should show "linear-gradient(...)"
```

## Lesson 2: Always Verify Visually with MCP

### Problem
Assuming code works without visual verification leads to broken UI that users notice.

### Solution
After every UI change:
1. Navigate to site with `mcp9_puppeteer_navigate`
2. Scroll to section
3. Take screenshot with `mcp9_puppeteer_screenshot`
4. Analyze results

### Example Workflow
```javascript
// 1. Navigate
await mcp9_puppeteer_navigate({"url": "http://127.0.0.1:8000/it"});

// 2. Scroll to footer
await mcp9_puppeteer_evaluate({"script": "window.scrollTo(0, document.body.scrollHeight);"});

// 3. Screenshot
await mcp9_puppeteer_screenshot({"name": "footer_check", "width": 1280});

// 4. Verify
await mcp9_puppeteer_evaluate({"script": "
  const footer = document.querySelector('footer');
  return {
    backgroundImage: window.getComputedStyle(footer).backgroundImage,
    height: footer.offsetHeight
  };
"});
```

## Lesson 3: Section Component Data Flow

### Understanding Section.php
The Section component passes data to footer via `$blocks`:

```php
// Section.php renders view with $blocks
$view_params = [
    'blocks' => $this->blocks, // DataCollection<BlockData>
];
```

### Extracting Footer Data
In `footer/v1.blade.php`:
```php
foreach ($blocks as $block) {
    if ($block->type === 'footer' && $block->slug === 'main-footer') {
        $footerBlock = $block->data;
        break;
    }
}
```

### Data Structure
```php
$footerBlock = [
    'brand' => ['name' => '...', 'subtitle' => '...', 'description' => '...'],
    'social' => ['linkedin' => '...', 'facebook' => '...'],
    'normative' => ['title' => '...', 'items' => [...]],
    'services' => ['title' => '...', 'items' => [...]],
    'contact' => ['title' => '...', 'items' => [...]],
    'legal' => ['copyright' => '...', 'links' => [...]]
];
```

## Lesson 4: Laravel Cache Errors

### Serialization Error
```
TypeError: Cannot access offset of type Laravel\SerializableClosure\Serializers\Native
```

### Solution
Clear all Laravel caches:
```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

### When to Clear
- After modifying service providers
- After changing middleware
- After Folio routing changes
- When seeing serialization errors

## Checklist for Footer Development

- [ ] Use inline styles for background
- [ ] Extract data from $blocks correctly
- [ ] Verify with MCP screenshot after changes
- [ ] Check all 4 columns render correctly
- [ ] Verify text is readable (contrast)
- [ ] Test social icons links
- [ ] Confirm contact info displays
- [ ] Check copyright bar at bottom

## Related Documentation
- [AGENTS.md](../../laravel/AGENTS.md)
- [.windsurf/rules/footer-background-inline-styles.mdc](../../.windsurf/rules/footer-background-inline-styles.mdc)
- [.windsurf/rules/visual-verification-mcp.mdc](../../.windsurf/rules/visual-verification-mcp.mdc)
- [Themes/Two/docs/footer-analysis.md](../../laravel/Themes/Two/docs/footer-analysis.md)
