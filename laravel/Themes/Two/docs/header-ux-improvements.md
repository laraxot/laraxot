# Header UI/UX Improvement Plan

## Problem
User reports "white characters on white background" making text unreadable.
Current implementation uses `bg-white/10` (10% opacity white) when scrolled. If the Page content behind the sticky header is white, the white text becomes invisible.

## Solution
We must ensure high contrast in both "Top" and "Scrolled" states.

### option A: Blue Sticky Header (Preferred for White Text)
- **Top**: Transparent (overlaying Hero). Text: White.
- **Scrolled**: Solid Brand Blue (`bg-[#1E5A96]` or similar). Text: White.
- **Pros**: Keeps text color consistent, strong branding.

### Option B: White Sticky Header (common in modern webs)
- **Top**: Transparent. Text: White.
- **Scrolled**: Solid White (`bg-white`). Text: **Dark** (Brand Blue/Gray).
- **Pros**: Clean look.
- **Cons**: Requires swapping classes on ALL text elements (Nav links, Logo, Icons).

## Decision
**Option A (Blue Sticky)** is safer and quicker to implement to solve the "white on white" issue immediately without complex class swapping logic for every element. It also matches the Footer's blue identity.

## UX Rules (to be added to Theme Docs)
1.  **Contrast Ratio**: Header text must always have a WCAG AA contrast ratio against its background.
2.  **Sticky State**: Must provide a solid background (opacity > 95%) to prevent content bleed-through affecting readability.
3.  **Transitions**: Background and color changes must use `transition-all duration-300`.

## Implementation Steps
1.  Modify `header/v1.blade.php`:
    - Change `:class` for `header`.
    - `scrolled`: `bg-[#0d2d4d]/90 backdrop-blur-md shadow-lg` (Dark Blue, slight transparency).
    - `!scrolled`: `bg-transparent`.
2.  Verify Mobile Menu contrast (currently `bg-gray-900/95`, generally okay).
