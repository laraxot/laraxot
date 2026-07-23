# Theme TwentyOne Roadmap (2025 Q4)

## Vision & Scope
- Alternate theme with parity to Sixteen, used for A/B or tenant branding.

## Key Outcomes
- Shared components with UI module, overrides documented

## Milestones
- [ ] Ensure section/block compatibility with CMS
- [ ] Theme asset pipeline documented

## Acceptance Criteria
- Swap between themes without functional regressions

## Next Steps (Q4 2025)
1. Achieve component parity with `Themes/Sixteen` for all core UI elements
2. Validate compatibility with `Modules/Cms` sections/blocks (render tests per block)
3. Align Vite asset pipeline and env config (mirroring Sixteen)
4. Document overrides vs shared components with `Modules/UI`

## Documentation Maintenance
- Add cross-links to `Themes/Sixteen/docs/ROADMAP.md` and `Modules/UI/docs/components_guide.md`
- Include a matrix: Component name | Source (UI/Sixteen) | Override (TwentyOne)
- Add a short "Theme swap QA checklist" for regression tests
