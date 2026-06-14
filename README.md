# Manangatang Energy — WordPress theme & core plugin

A WordPress conversion of the static Manangatang Energy site.

## Pieces
- **Theme:** `wp-content/themes/manangatang-energy` — the visual port (Tailwind CDN build, Lucide icons, all page/CPT templates).
- **Plugin:** `wp-content/plugins/manangatang-energy-core` — content types, settings and the setup importer. Keep this active; content lives here so it survives a theme switch.

## First-time setup
1. **Activate** the *Manangatang Energy Core* plugin and the *Manangatang Energy* theme.
2. *(Recommended)* Install & activate **Contact Form 7** before the next step so the forms are created automatically.
3. Go to **Manangatang → Setup** in the admin and click **Run setup now**. This:
   - creates all pages (Home, About, The Site, Community, Documents, FAQs, Contact, Privacy Policy) with the correct templates,
   - sets the static front page and builds the **Primary** nav menu,
   - seeds the category terms and sample News / Documents / FAQs,
   - creates the three Contact Form 7 forms and links their IDs (if CF7 is active).

It is safe to re-run — existing items are skipped, not duplicated.

## Editing content
- **News & Updates / Documents / FAQs** — edit under their own admin menus. Documents take a PDF file + a meta line; News take a subtitle + read-time; FAQs use the title as the question and the body as the answer. Order with the *Order* (page-attributes) field.
- **Contact details, hero stats, footer, Contact Form 7 IDs** — **Manangatang → Settings** *or* the Customizer (they share the same option, so either stays in sync).
- **Navigation** — fully dynamic. **Appearance → Menus**:
  - *Primary* menu → *Primary Navigation* location (header).
  - *Footer* menu → *Footer Navigation* location (footer "Project" quick-links column). If no footer menu is assigned, the column falls back to default links.
- **Footer → Header / Footer / Preloader / Cookie** are top-level Customizer sections. The footer **Contact items** is a repeater — add as many icon + text + link rows as you want; leave it empty to fall back to the email / phone / address from Contact details. Icons use [Lucide](https://lucide.dev) names (e.g. `mail`, `phone`, `map-pin`, `globe`, `clock`).

## Appearance → Customize
Live-preview controls:
- **Preloader** (top-level section): turn the loading screen on/off, set the max display time (ms), and choose a custom logo.
- **Cookie Consent** (top-level section): turn the notice on/off and edit the heading, message and button labels.
- **Manangatang Energy** panel — page-by-page customisation:
  - *Contact details, Hero statistics, Footer, Forms* — same values as the settings page.
  - *Homepage* — edit the hero tagline/intro and show/hide each of the 5 homepage sections.
  - *About / The Site / Community / Contact* — edit each page's hero eyebrow, heading and subtitle, and show/hide that page's major sections (add/remove content per page).

## Notes
- **Forms** use Contact Form 7. If it isn't active, form areas show a friendly placeholder instead of breaking. CF7 output is restyled by the theme to match the rounded inputs.
- **Tailwind** is loaded as the v4 browser (CDN) build to keep a zero-build, pixel-faithful match. For production you can later swap to a compiled stylesheet.
- **Permalinks:** if News/Documents URLs 404 after moving the site, go to *Settings → Permalinks* and click Save to flush rewrite rules.
