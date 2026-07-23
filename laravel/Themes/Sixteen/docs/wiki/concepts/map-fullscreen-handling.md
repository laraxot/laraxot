# Map Fullscreen Handling

## Issue: Scrollbar and Overlay on Fullscreen

### Symptoms
- Vertical scrollbar appears when map enters fullscreen
- Info box overlays map, making it appear disabled
- Map doesn't render correctly until manually resized

### Root Cause
- Missing `overflow: hidden` on parent containers during fullscreen
- Overlay element z-index conflicts with Leaflet map container
- Map `invalidateSize()` not called after layout changes

### Fix Strategy
1. **Prevent Body Scroll**
```javascript
_toggleFullscreen() {
  document.body.style.overflow = this.isFullscreen ? 'hidden' : '';
}
```

2. **Ensure Map Container Covers Viewport**
```css
.map-container.is-fullscreen {
  position: fixed !important;
  inset: 0 !important;
  z-index: 9999 !important;
  overflow: hidden !important;
}
```

3. **Force Map Resize After Layout Changes**
```javascript
_toggleFullscreen() {
  // ...existing code...
  if (this._map) {
    setTimeout(() => this._map?.invalidateSize(), 350);
  }
}
```

4. **Z-Index Management for Overlays**
```css
.map-picker-leaflet-pane {
  position: relative;
  z-index: 1;
}

.overlay-info {
  z-index: 10;
  pointer-events: auto;
}
```

## Prevention Guidelines
- Always call `invalidateSize()` after any dimension change
- Use explicit `z-index` layers for overlays (1-1000+)
- Test with both desktop and mobile viewport sizes

## QA Checklist
- [ ] Map fills entire viewport on fullscreen
- [ ] No scrollbars visible during interaction
- [ ] Overlay elements don't block map controls
- [ ] Map controls remain accessible