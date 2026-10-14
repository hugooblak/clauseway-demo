# Test proof — Clauseway

Run on 2026-10-14 against a fresh WordPress install with the demo content. Local test server, no compression.

| Check | Result | Details |
|---|---|---|
| 1. Markup lint | ✅ Pass | clean |
| 2. Pages load | ✅ Pass | 2 pages |
| 3. Link crawl | ✅ Pass | 9 URLs checked |
| 4. Editor: no invalid blocks | ✅ Pass | 1 posts/pages, 4 patterns, all templates |
| 5. Editor font sizes = live site | ✅ Pass | 25 text elements compared |
| 6. Accessibility (axe, WCAG 2.2 AA) | ✅ Pass | 2 pages × 2 widths, 0 violations |
| 7. Keyboard | ✅ Pass | skip link, focus outline, FAQ, mobile menu |
| 8. Forms | ✅ Pass | 1 forms: every field labelled (CF7: errors shown) |
| 9. Layout 320–1440px | ✅ Pass | no overflow, header fits |
| 10. Lighthouse (mobile) | ✅ Pass | / 96/100/100/100 |
| 11. Screenshots | ✅ Pass | 3 saved to proof/screenshots |

## Lighthouse (mobile)

| Page | Speed | Accessibility | Best practices | SEO | LCP | CLS |
|---|---|---|---|---|---|---|
| / | 96 | 100 | 100 | 100 | 2.3 s | 0.001 |

Automated tests cannot catch every accessibility or design problem. A real project adds manual screen reader testing.
