# MBSC Firm — UX audit

Audit date: 30 September 2026. Branch: `ux-audit`. No application code was changed for this audit.

## Summary

The site looks finished at a glance, but it does not yet work as a business website. The five findings that matter most:

1. **No enquiry can be sent.** Both forms on the home page have a disabled submit button and no handler. The contact page form shows "Message sent" after a 1.5 second timer without sending anything. There is no backend route for enquiries.
2. **The main call to action goes nowhere.** "Free Consultation" in the hero links to `/#`. On the home page 22 links are dead, including every footer link, "Get Started", "Contact Us", the blog links, LinkedIn and the privacy policy.
3. **The live site is an empty shell on a static host.** `www.mbscfirm.com` is served by Vercel as a static export. Every URL, including `/robots.txt`, `/sitemap.xml` and `/favicon.ico`, returns the same 633-byte HTML with the title "MBSC Firm" and no description. Navigation is disabled with CSS, so only the home page is reachable. The Laravel admin panel and database are not running there.
4. **Trust content is unverifiable and contradicts itself.** Client counts read 500+ in one place and 5000+ in another, the firm is "Dhaka-based" on a site whose address is Chattogram, and office hours differ between pages. Two of three team members use stock photographs. See [Unverifiable content](#unverifiable-content).
5. **Mobile is slow and hard to tap.** Mobile Lighthouse performance is 58–67, with LCP between 6.0 and 7.9 seconds. The home page downloads 3.4 MB, 2.8 MB of it stock images. 40 of 57 tappable elements are smaller than 44 px, and every form field is 14 px, which makes iOS zoom the page on focus.

The visual design is a separate, lower-priority problem: it is a generic dark-gradient SaaS template (46 gradients, 29 glass-blur panels, 63 shadows and 43 large-radius cards on the home page) that does not read as an established legal and tax practice.

## Phase 1 — Project map

| Area | Finding |
| --- | --- |
| Stack | Laravel 12, Inertia 2, Vue 3 with TypeScript, Tailwind CSS 4, Vite 7, reka-ui, vue-i18n (English and Bengali, admin strings only) |
| Public routes | `/`, `/services`, `/about`, `/contact` in [routes/public.php](routes/public.php), all served by [HomeController.php](app/Http/Controllers/Public/HomeController.php) |
| Admin | `/admin/*` behind auth and permissions: services, team members, testimonials, FAQs, roles, users, audit logs. Fortify login, registration and two-factor |
| Data | Services, team, testimonials and FAQs come from MySQL and are editable in the admin. Everything else on the public pages is hard-coded in the Vue files |
| Public components | One layout, [PublicLayout.vue](resources/js/layouts/PublicLayout.vue), and four page files of 270–1,143 lines each. No shared section, button, card or form components; markup and SVG icons are copied inline |
| Styling | Tailwind utilities written inline. [app.css](resources/css/app.css) holds the shadcn admin theme only. There are no public-site design tokens |
| Rendering | Inertia SSR is enabled in [config/inertia.php](config/inertia.php) and `resources/js/ssr.ts` exists, but no SSR server is running and no SSR bundle is built, so pages render in the browser |
| Deployment | Live site is a static export on Vercel from an older build. The build and deploy process for that export is not in this repository |
| Tests | Default Laravel starter tests for auth, dashboard and settings. None for public pages |

### Pages

| Page | File | Contents |
| --- | --- | --- |
| Home | [Welcome.vue](resources/js/pages/Welcome.vue) | 10 sections: hero with callback form, services, about, process, testimonials, team, blog, CTA band, FAQ, contact form. 15,952 px tall at 375 px |
| Services | [Services.vue](resources/js/pages/Services.vue) | Hero with four stat tiles, sidebar list of 8 services, detail panel switched in place, related services |
| About | [About.vue](resources/js/pages/About.vue) | Hero with four stat tiles, mission, vision and values tabs, six-step timeline, team, certifications, CTA |
| Contact | [Contact.vue](resources/js/pages/Contact.vue) | Hero with four contact tiles, six-field form, drawn placeholder map, social links |

### Screenshots

Full-page and above-the-fold captures at 375, 768 and 1440 px are in [doc/ux-audit/screenshots/](doc/ux-audit/screenshots/), named `<page>-<width>.jpg` and `<page>-<width>-fold.jpg`.

| Page | 375 | 768 | 1440 |
| --- | --- | --- | --- |
| Home | [view](doc/ux-audit/screenshots/home-375.jpg) | [view](doc/ux-audit/screenshots/home-768.jpg) | [view](doc/ux-audit/screenshots/home-1440.jpg) |
| Services | [view](doc/ux-audit/screenshots/services-375.jpg) | [view](doc/ux-audit/screenshots/services-768.jpg) | [view](doc/ux-audit/screenshots/services-1440.jpg) |
| About | [view](doc/ux-audit/screenshots/about-375.jpg) | [view](doc/ux-audit/screenshots/about-768.jpg) | [view](doc/ux-audit/screenshots/about-1440.jpg) |
| Contact | [view](doc/ux-audit/screenshots/contact-375.jpg) | [view](doc/ux-audit/screenshots/contact-768.jpg) | [view](doc/ux-audit/screenshots/contact-1440.jpg) |

### Lighthouse baseline

Local figures come from `php artisan serve` with a production Vite build. That server sends no compression or cache headers, so local performance is pessimistic by roughly the size of the uncompressed JavaScript and CSS. The live row is the fair comparison for performance.

| Page | Mode | Performance | Accessibility | Best practices | SEO | FCP | LCP | TBT | CLS | Weight |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Live home | mobile | 58 | 70 | 100 | 83 | 1.7 s | 6.1 s | 810 ms | 0 | 2.9 MB |
| Live home | desktop | 90 | 78 | 100 | 83 | 0.4 s | 1.4 s | 190 ms | 0.001 | 2.9 MB |
| Home | mobile | 63 | 78 | 100 | 83 | 3.4 s | 7.9 s | 190 ms | 0 | 3.4 MB |
| Home | desktop | 88 | 84 | 100 | 83 | 0.7 s | 1.7 s | 180 ms | 0 | 3.4 MB |
| Services | mobile | 67 | 81 | 100 | 92 | 3.4 s | 6.0 s | 200 ms | 0 | 1.2 MB |
| Services | desktop | 97 | 90 | 100 | 92 | 0.7 s | 1.2 s | 10 ms | 0.001 | 1.2 MB |
| About | mobile | 62 | 86 | 100 | 92 | 3.3 s | 6.9 s | 330 ms | 0 | 1.2 MB |
| About | desktop | 97 | 93 | 100 | 92 | 0.7 s | 1.3 s | 0 ms | 0.001 | 1.2 MB |
| Contact | mobile | 67 | 72 | 100 | 92 | 3.3 s | 6.6 s | 200 ms | 0.004 | 1.1 MB |
| Contact | desktop | 97 | 72 | 100 | 92 | 0.7 s | 1.2 s | 0 ms | 0.001 | 1.1 MB |

The SEO score overstates the real position: Lighthouse runs JavaScript, so it sees headings and links that a crawler or link-preview bot reading the raw HTML does not.

### Live site rendering

Confirmed. `curl https://www.mbscfirm.com/` returns 633 bytes: a `<title>MBSC Firm</title>`, two stylesheets, an empty `<div id="app">` and one script. The same response is returned for `/about`, `/services`, `/contact`, `/robots.txt`, `/sitemap.xml` and `/favicon.ico`.

Impact:

- Link previews on WhatsApp, Facebook and LinkedIn show a bare "MBSC Firm" with no description or image. These are the channels the firm links to.
- Search engines that do not run JavaScript index an empty page. Google will render it eventually, but with one title and no description for the whole site.
- `/robots.txt` is HTML, which Lighthouse reports as 14 syntax errors. There is no sitemap and no favicon.
- Only one page exists for search purposes, so no service can rank on its own.

## Phase 2 — Findings

Each issue has an impact rating (effect on visitors) and an effort rating. The [prioritised list](#prioritised-list) combines them.

### First impression

The hero ([home-1440-fold.jpg](doc/ux-audit/screenshots/home-1440-fold.jpg), [home-375-fold.jpg](doc/ux-audit/screenshots/home-375-fold.jpg)) says what the firm does in the supporting sentence: RJSC, income tax, VAT and audit support in Bangladesh. That sentence is the most useful text on the page. The headline above it, "Expert Legal & Tax Solutions for Your Business", is generic and the city is never stated.

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| F1 | Primary CTA "Free Consultation" links to `/#` and does nothing | [Welcome.vue:119](resources/js/pages/Welcome.vue#L119) | High | Low |
| F2 | Hero callback form has a permanently disabled button and no handler | [Welcome.vue:194-203](resources/js/pages/Welcome.vue#L194-L203) | High | Medium |
| F3 | Location is missing from the hero; the about section says "Dhaka-based" while the office is in Chattogram | [Welcome.vue:438](resources/js/pages/Welcome.vue#L438) | High | Low |
| F4 | Hero competes with itself: badge, headline, two buttons, stats bar, three photos, floating form and floating rating card | [Welcome.vue:78-223](resources/js/pages/Welcome.vue#L78-L223) | Medium | Medium |
| F5 | On mobile the first screen ends before any proof or contact detail other than the phone button; the stats, photos and form are below or hidden | [home-375-fold.jpg](doc/ux-audit/screenshots/home-375-fold.jpg) | Medium | Medium |
| F6 | Hero photos are generic stock (office high-five, handshake) unrelated to Bangladesh or the firm | [Welcome.vue:162-173](resources/js/pages/Welcome.vue#L162-L173) | Medium | Needs photography |

### Navigation and information architecture

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| N1 | All footer links use `href="#"`: quick links, six service links, privacy policy, terms | [PublicLayout.vue:166-181](resources/js/layouts/PublicLayout.vue#L166-L181), [:209-210](resources/js/layouts/PublicLayout.vue#L209-L210) | High | Low |
| N2 | 22 dead links on the home page, 15–16 on each other page | "Get Started" [Welcome.vue:488](resources/js/pages/Welcome.vue#L488), blog [:773](resources/js/pages/Welcome.vue#L773), [:800](resources/js/pages/Welcome.vue#L800), "Contact Us" [:856](resources/js/pages/Welcome.vue#L856) | High | Low |
| N3 | Services have no URLs of their own. The services page swaps content in place, so a service cannot be linked, shared or indexed. Home cards link to `/services#slug` | [Services.vue](resources/js/pages/Services.vue) | High | Medium |
| N4 | "Sign In" sits in the public header as prominently as the phone number. It is for staff only | [PublicLayout.vue:86-88](resources/js/layouts/PublicLayout.vue#L86-L88) | Medium | Low |
| N5 | The header has no enquiry button; the phone pill is hidden below 1024 px, so the mobile header offers no way to contact the firm | [PublicLayout.vue:74](resources/js/layouts/PublicLayout.vue#L74) | High | Low |
| N6 | The home page is a second copy of the whole site: about, team, FAQ and a full contact form repeat their own pages. It is 9,376 px tall on desktop and 15,952 px on mobile | [home-1440.jpg](doc/ux-audit/screenshots/home-1440.jpg) | Medium | Medium |
| N7 | Blog section lists three articles that do not exist | [Welcome.vue:65-69](resources/js/pages/Welcome.vue#L65-L69) | Medium | Low |
| N8 | Footer shows a red "M" tile instead of the logo used in the header | [PublicLayout.vue:137-139](resources/js/layouts/PublicLayout.vue#L137-L139) | Low | Low |
| N9 | Mobile menu button has no accessible name or expanded state, and the menu does not trap focus or close on Escape | [PublicLayout.vue:93-96](resources/js/layouts/PublicLayout.vue#L93-L96) | Medium | Low |

### Trust signals

See the full table under [Unverifiable content](#unverifiable-content). Issues beyond the individual claims:

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| T1 | The same statistics are repeated four times on the home page and conflict across pages (500+ against 5000+ clients) | see table | High | Low once figures are confirmed |
| T2 | Two of three team members have first-name-only entries and stock portraits, and all three share the founder's social links | [TeamMemberSeeder.php](database/seeders/TeamMemberSeeder.php) | High | Needs content |
| T3 | The founder's photograph is 314 × 314 px and is shown at up to 400 px wide, next to high-resolution stock portraits | `public/asset/rifat.jpeg` | Medium | Needs photography |
| T4 | No registration or licence numbers, no named affiliations with evidence, no client logos | — | Medium | Needs content |
| T5 | The featured testimonial quote does not match the text of the same person's testimonial card below it | [Welcome.vue:616-632](resources/js/pages/Welcome.vue#L616-L632) | Medium | Low |
| T6 | Contact email is a Gmail address while an `@mbscfirm.com` domain exists | [PublicLayout.vue:31](resources/js/layouts/PublicLayout.vue#L31) | Low | Client decision |

### Conversion paths

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| C1 | Contact page form fakes success with `setTimeout` and sends nothing | [Contact.vue:18-35](resources/js/pages/Contact.vue#L18-L35) | High | Medium |
| C2 | Home page contact form has a disabled submit button and no handler | [Welcome.vue:1096-1133](resources/js/pages/Welcome.vue#L1096-L1133) | High | Medium |
| C3 | No backend for enquiries: no route, controller, mail, storage, validation or spam protection | [routes/public.php](routes/public.php) | High | Medium |
| C4 | Contact form has six fields and four are required, including both subject and message | [Contact.vue:161-229](resources/js/pages/Contact.vue#L161-L229) | Medium | Low |
| C5 | Phone number in the footer and contact tiles is plain text, not a `tel:` link | [PublicLayout.vue:195](resources/js/layouts/PublicLayout.vue#L195) | Medium | Low |
| C6 | WhatsApp appears only in the home CTA band and contact page. The top-bar and footer WhatsApp icons link to `#` | [PublicLayout.vue:45](resources/js/layouts/PublicLayout.vue#L45), [:155](resources/js/layouts/PublicLayout.vue#L155) | High | Low |
| C7 | The map is a drawn placeholder with non-functional zoom buttons, and the home page uses a stock photo captioned "Dhaka Location" | [Contact.vue:290](resources/js/pages/Contact.vue#L290), [Welcome.vue:1090](resources/js/pages/Welcome.vue#L1090) | Medium | Low |
| C8 | Office hours conflict: Sunday to Thursday in the header and home page, Monday to Friday on the contact page | [PublicLayout.vue:36](resources/js/layouts/PublicLayout.vue#L36), [Contact.vue:118](resources/js/pages/Contact.vue#L118) | Medium | Low |
| C9 | Response promises conflict: "within 2 hours", "within 24 hours" and "1-2 business days" | [Welcome.vue:191](resources/js/pages/Welcome.vue#L191), [Contact.vue:78](resources/js/pages/Contact.vue#L78), services sidebar | Medium | Client decision |
| C10 | No privacy note at the form, and the privacy policy link is dead | — | Medium | Needs content |

### Mobile usability

| ID | Issue | Evidence | Impact | Effort |
| --- | --- | --- | --- | --- |
| M1 | 40 of 57 interactive elements on the home page are under 44 × 44 px: top-bar icons 16 × 16, "Learn more" 94 × 20, phone link 144 × 20, menu button 40 × 40 | measured at 375 px | High | Low |
| M2 | All inputs, selects and textareas are 14 px. iOS Safari zooms the page when a field under 16 px is focused | both forms | High | Low |
| M3 | Home page scrolls 9 px sideways at 320–375 px. A decorative blur circle in the services section is 384 px wide and its section does not clip overflow | [Welcome.vue:227-231](resources/js/pages/Welcome.vue#L227-L231) | Medium | Low |
| M4 | No persistent call or WhatsApp action on mobile; the header only shows the logo and the menu | [home-375-fold.jpg](doc/ux-audit/screenshots/home-375-fold.jpg) | High | Low |
| M5 | The home page is about 20 screens long on a phone | 15,952 px at 375 px | Medium | Medium |
| M6 | Contact hero tiles truncate the email address on a 375 px screen | [contact-375.jpg](doc/ux-audit/screenshots/contact-375.jpg) | Low | Low |
| M7 | Services page puts the eight-item list and a help card before the selected service, so the content starts on the third screen | [services-375.jpg](doc/ux-audit/screenshots/services-375.jpg) | Medium | Medium |

### Visual consistency

| ID | Issue | Evidence | Impact | Effort |
| --- | --- | --- | --- | --- |
| V1 | No design tokens for the public site. Colours, radii and shadows are chosen per element: rose, red, pink, violet, sky, emerald, zinc and slate all appear | page files | Medium | Medium |
| V2 | Template styling throughout: 46 gradient elements, 14 blur circles, 29 backdrop-blur panels, 63 shadows and 43 `rounded-2xl` boxes on the home page | measured | Medium | High |
| V3 | At least four primary button treatments: rose gradient, dark gradient, outlined glass, green WhatsApp | hero, callback card, CTA band | Medium | Low |
| V4 | Every section repeats the same formula: pill label, heading, paragraph, card grid. Nothing distinguishes services from process from team | [home-1440.jpg](doc/ux-audit/screenshots/home-1440.jpg) | Medium | High |
| V5 | Headings highlight one or two words in a gradient on every page | all four heroes | Low | Low |
| V6 | The logo says "RJSC \| Tax \| Legal Compliance"; the footer says "Legal & Tax Consultancy"; the headline says "Legal & Tax Solutions". Three descriptions of the firm | header, footer, hero | Medium | Client decision |
| V7 | Instrument Sans, the starter-kit default, is the only typeface, in three weights with no defined scale | [app.blade.php](resources/views/app.blade.php) | Low | Low |
| V8 | Team portraits are square on the home page and 3:4 on the about page, with different crops of the same people | team sections | Low | Low |

### Accessibility

Lighthouse accessibility is 72–86 on mobile. Issues against WCAG 2.2 AA:

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| A1 | Form labels are not associated with their fields. No `for`/`id` pairs, so screen readers announce unlabelled inputs. The hero callback form has placeholders only | both forms; Lighthouse `select-name` | High | Low |
| A2 | Seven icon-only links and up to three buttons have no accessible name: social icons, menu toggle, map zoom | Lighthouse `link-name`, `button-name` | High | Low |
| A3 | No custom focus style. Focus relies on the browser default 1 px outline, which is hard to see on the dark sections | tab test | High | Low |
| A4 | No skip link | [PublicLayout.vue](resources/js/layouts/PublicLayout.vue) | Medium | Low |
| A5 | Contrast at or below the 4.5:1 minimum: white on `rose-500` buttons, `rose-600` on `rose-100`, and `slate-400`/`slate-500` text on dark backgrounds. Six failures on the contact page | Lighthouse `color-contrast` | Medium | Low |
| A6 | Heading order skips levels: H2 to H4 on the home page, H1 to H3 on services, footer titles as H4 | measured outline | Medium | Low |
| A7 | No `prefers-reduced-motion` handling anywhere. The hero badge pings continuously and cards scale on hover | no rule in built CSS | Medium | Low |
| A8 | FAQ accordion buttons have no `aria-expanded` or `aria-controls`; about-page tabs have no tab roles | [Welcome.vue](resources/js/pages/Welcome.vue), [About.vue](resources/js/pages/About.vue) | Medium | Low |
| A9 | Logo image has empty alt text while being the only content of the home link | [PublicLayout.vue:56](resources/js/layouts/PublicLayout.vue#L56) | Medium | Low |
| A10 | Success message on the contact form is not announced to assistive technology | [Contact.vue:155](resources/js/pages/Contact.vue#L155) | Low | Low |
| A11 | Alt text describes stock imagery generically ("Team Member", "Office") and one says "Dhaka Location" | [Welcome.vue](resources/js/pages/Welcome.vue) | Low | Low |

### Performance

| ID | Issue | Evidence | Impact | Effort |
| --- | --- | --- | --- | --- |
| P1 | Home page loads 31 images totalling 2.8 MB from `images.unsplash.com`, four of them 1920 px backgrounds shown at 5–10% opacity or under a 95% dark overlay | 608 kB, 472 kB, 334 kB and 249 kB files | High | Low |
| P2 | No image has `loading="lazy"`, `width`/`height`, `srcset` or a modern format request | 31 of 31 on home | High | Low |
| P3 | `logo.png` is 288 kB at 978 × 250 and is displayed 64 px tall | `public/logo.png` | Medium | Low |
| P4 | The shared `app` bundle is 349 kB (119 kB gzipped) and 201 kB of it is unused on public pages. Admin-oriented dependencies such as i18n and theme handling load for every visitor | Lighthouse `unused-javascript` | Medium | Medium |
| P5 | CSS is one 146 kB file containing admin and public styles, and it blocks rendering along with the font stylesheet | Lighthouse `render-blocking` about 1.6 s | Medium | Medium |
| P6 | The LCP element is a CSS-positioned background image that the browser cannot discover until JavaScript has rendered the page | Lighthouse `lcp-discovery` | High | Medium, solved by server rendering plus preload |
| P7 | All imagery depends on a third-party host, with no control over availability or caching | `images.unsplash.com` | Medium | Low |
| P8 | Mobile LCP is 6.0–7.9 s against a 2.5 s target. CLS is good at 0–0.004 | table above | High | Combination of P1, P2 and P6 |
| P9 | The font is loaded from a third-party host without preload; Lighthouse estimates up to 180 ms of delay from `font-display` | [app.blade.php](resources/views/app.blade.php) | Low | Low |

### SEO

| ID | Issue | Where | Impact | Effort |
| --- | --- | --- | --- | --- |
| S1 | No server-rendered HTML. Locally the SSR setting is on but nothing serves it; live is an empty static shell | [config/inertia.php](config/inertia.php) | High | Medium, depends on hosting |
| S2 | `APP_NAME` is "Laravel", so titles read "About Us - MBSC Firm - Laravel" | `.env`, [app.ts](resources/js/app.ts) | High | Low |
| S3 | No meta description on any page | Lighthouse `meta-description` | High | Low |
| S4 | No Open Graph or Twitter tags and no share image | [app.blade.php](resources/views/app.blade.php) | High | Low |
| S5 | No canonical URLs | — | Medium | Low |
| S6 | No structured data. `LegalService` or `AccountingService` with address, phone, hours and area served would suit the firm | — | Medium | Low |
| S7 | No `sitemap.xml`. Local `robots.txt` is valid but has no sitemap line; live `robots.txt` is HTML | `public/robots.txt` | Medium | Low |
| S8 | Favicon and touch icon are the Laravel defaults; the live site has none | `public/favicon.svg` | Medium | Low |
| S9 | One page for eight services, so no service has its own title, description or URL | same as N3 | High | Medium |
| S10 | Six "Learn more" links with identical text | Lighthouse `link-text` | Low | Low |
| S11 | Name, address and phone are consistent in the footer but the city conflicts in body copy (F3), which weakens local search signals | — | Medium | Low |
| S12 | Bengali locale exists for the admin only. No Bengali public content or `hreflang` | [i18n/locales](resources/js/i18n/locales) | Low | High, needs content |

## Unverifiable content

Nothing here has been removed or changed. Each item needs a decision from the firm: confirm with evidence, correct, or remove.

### Statistics and claims

| Claim | Where | Concern |
| --- | --- | --- |
| "Trusted by 500+ Businesses", "500+ Happy Clients", "500+ Clients Served", "500+ Business Clients" | [Welcome.vue:14](resources/js/pages/Welcome.vue#L14), [:102](resources/js/pages/Welcome.vue#L102), [:143](resources/js/pages/Welcome.vue#L143), [:562](resources/js/pages/Welcome.vue#L562), [:879](resources/js/pages/Welcome.vue#L879) | Conflicts with 5000+ below |
| "5000+ Clients Served", "5000+ Happy Clients", "serving over 5000 businesses" | [Services.vue:98](resources/js/pages/Services.vue#L98), [About.vue:110](resources/js/pages/About.vue#L110), [About.vue:14](resources/js/pages/About.vue#L14) | Ten times the home page figure |
| "98% Success Rate" | home ×3, services, about | No definition of success. Outcome percentages are a risk for a legal or tax practice |
| "15+ Years Experience" and "Since 2010" | home ×4, about, services | Consistent with each other, but unverified |
| "50+ Expert Team", "50+ Expert Professionals" | [Welcome.vue:17](resources/js/pages/Welcome.vue#L17), about hero | The site shows three people |
| "4.9/5 Rating, 200+ Reviews" | [Welcome.vue:216-217](resources/js/pages/Welcome.vue#L216-L217) | No review source named or linked |
| "2,500+ Cases Resolved" | [Welcome.vue:276](resources/js/pages/Welcome.vue#L276) | Unsourced |
| "24/7 Support" | [Welcome.vue:918](resources/js/pages/Welcome.vue#L918) | Contradicts the stated office hours |
| "Free Consultation" | [Welcome.vue:121](resources/js/pages/Welcome.vue#L121), [Services.vue:228](resources/js/pages/Services.vue#L228), FAQ | Confirm the firm offers this |
| "Response within 2 hours" | [Welcome.vue:191](resources/js/pages/Welcome.vue#L191) | Conflicts with 24 hours and 1–2 business days |
| "Best Financial Advisory Company" | [Welcome.vue:613](resources/js/pages/Welcome.vue#L613) | Reads as an award; no awarding body |
| "Awarded Best Tax Consultancy Firm by the Bangladesh Business Awards" (2022) | [About.vue:13](resources/js/pages/About.vue#L13) | Specific award claim, unverified |
| Timeline: 2010 foundation, 2013 first 500 clients, 2016 expansion, 2019 digital platform, 2025 5000+ clients | [About.vue:9-14](resources/js/pages/About.vue#L9-L14) | Reads as template filler; 500 clients by 2013 conflicts with 500+ today |
| "Registered Tax Practitioners", "Bangladesh Bar Council Members", "ICAB Certified Accountants", "RJSC Authorized Representatives" | [About.vue:55-58](resources/js/pages/About.vue#L55-L58) | Professional-body claims with no names or registration numbers. The services copy says statutory audits are done through chartered accountancy partners, which sits uneasily with "ICAB Certified Accountants" |
| "Dhaka-based legal and consultancy firm" | [Welcome.vue:438](resources/js/pages/Welcome.vue#L438) | Address is Kotowali, Chattogram |
| Case labels "E-commerce VAT Setup", "Foreign Company Registration", "Startup Tax Planning" under "Real Results" | [Welcome.vue:566-586](resources/js/pages/Welcome.vue#L566-L586) | Presented as client work, illustrated with stock photos |

### Testimonials

| Item | Where | Concern |
| --- | --- | --- |
| Mohammad Rahman, CEO, TechStart BD | [TestimonialSeeder.php](database/seeders/TestimonialSeeder.php) | Generic name and company; stock avatar |
| Fatima Ahmed, Director, Green Exports Ltd | same | Same; mentions IRC/ERC processing, which is not a listed service |
| Karim Hassan, Managing Director, Dhaka Industries | same, and featured at [Welcome.vue:616-632](resources/js/pages/Welcome.vue#L616-L632) | Same; mentions BIDA registration, not a listed service. Featured quote differs from the card text |
| All avatars | [Welcome.vue:629](resources/js/pages/Welcome.vue#L629), [:657](resources/js/pages/Welcome.vue#L657) | Unsplash portraits chosen by position in the list, not by person |

Two of these names, Mohammad Rahman and Karim Hassan, also appear as team members with `@mbscfirm.com` addresses in the older build on the live server, which suggests they are template data.

### Team and photographs

| Item | Where | Concern |
| --- | --- | --- |
| Mr. S. M. Sirajul Monir (Rifat), Founder & Consultant | [TeamMemberSeeder.php](database/seeders/TeamMemberSeeder.php) | Appears genuine. Photo is 314 px square, low quality. The Twitter link should be checked |
| Ms. Fatima, Senior Consultant | same | First name only; Unsplash stock portrait; social links are the founder's |
| Mr. Hossain, Legal Advisor | same | First name only; Unsplash stock portrait; social links are the founder's |
| Every other photograph on the site, about 30 images | all pages | Unsplash stock: western offices, handshakes, city towers. None shows the firm, its office or Chattogram |
| Blog posts dated January 2026 | [Welcome.vue:65-69](resources/js/pages/Welcome.vue#L65-L69) | No articles exist |

## Prioritised list

### High

| # | Action | Issues | Effort |
| --- | --- | --- | --- |
| 1 | Make enquiries work: one short form, a Laravel endpoint with validation, mail or stored record, spam protection, real success and error states | C1, C2, C3, C4, F2, A1, A10 | Medium |
| 2 | Fix every dead link and wire the primary CTA to the enquiry form | F1, N1, N2, C5, C6 | Low |
| 3 | Decide hosting, then ship real HTML: run Inertia SSR if the site moves to a PHP host, or prerender the four pages if it stays on Vercel | S1, P6 | Medium |
| 4 | Per-page titles, descriptions, Open Graph tags, canonical URLs, favicon, fix `APP_NAME` | S2, S3, S4, S5, S8 | Low |
| 5 | Resolve conflicting facts: city, hours, client count, response time | F3, C8, C9, T1, S11 | Low after client answers |
| 6 | Mobile tap targets, 16 px inputs, sticky call and WhatsApp bar, header enquiry button | M1, M2, M4, N5 | Low |
| 7 | Images: host locally, WebP or AVIF, correct sizes, lazy loading, dimensions; drop the four near-invisible background photos; compress the logo | P1, P2, P3, P7, P8 | Low |
| 8 | Give each service its own page and URL | N3, S9, M7 | Medium |
| 9 | Accessible names, visible focus style, skip link | A2, A3, A4, N9 | Low |
| 10 | Replace or remove unverifiable trust content once the firm responds | T2, and the tables above | Needs content |

### Medium

| # | Action | Issues | Effort |
| --- | --- | --- | --- |
| 11 | Design tokens for colour, type, spacing, radius and shadow; one button set | V1, V3, V7 | Medium |
| 12 | Rework the hero: location in the headline area, one primary action, remove floating cards | F4, F5, V5 | Medium |
| 13 | Shorten the home page so it introduces and links out instead of duplicating other pages; remove the blog section until articles exist | N6, N7, M5 | Medium |
| 14 | Real map embed or a static map image linking to Google Maps | C7 | Low |
| 15 | Structured data, sitemap and a sitemap line in `robots.txt` | S6, S7 | Low |
| 16 | Contrast, heading order, reduced motion, accordion and tab semantics | A5, A6, A7, A8, A9 | Low |
| 17 | Split the public bundle from the admin bundle and trim public CSS | P4, P5 | Medium |
| 18 | Move "Sign In" out of the public header | N4 | Low |
| 19 | Replace the card-grid template look with a layout specific to the firm | V2, V4 | High |
| 20 | Horizontal overflow on small phones | M3 | Low |

### Low

| # | Action | Issues | Effort |
| --- | --- | --- | --- |
| 21 | Footer logo, consistent firm descriptor, portrait ratios | N8, V6, V8 | Low |
| 22 | Font loading and link text | P9, S10, A11 | Low |
| 23 | Email on the firm's own domain | T6 | Client decision |
| 24 | Bengali public content | S12 | High |

## Decisions and content needed before Phase 3

1. **Hosting.** Will the site stay a static export on Vercel, or move to a host that runs Laravel? This decides how server rendering is done and whether the admin panel and enquiry form can work in production at all.
2. **Where enquiries go.** Destination email address, and whether to store them in the admin.
3. **Facts.** Correct client count, founding year, team size, office hours, response time, and whether the first consultation is free.
4. **Credentials.** Which professional memberships are real, with names or registration numbers.
5. **Testimonials.** Genuine ones with permission, or remove the section. Placeholders would be marked `[TESTIMONIAL]`.
6. **Team.** Full names, roles, qualifications and real photographs for each person, or show the founder only.
7. **Photography.** Office exterior and interior, and the team at work. Without these, the recommendation is fewer images, not different stock.
8. **Legal pages.** Privacy policy and terms text.
9. **Links.** LinkedIn page URL, Google Maps pin, and whether the Gmail address stays.
10. **Firm descriptor.** One line to use everywhere: the logo says "RJSC | Tax | Legal Compliance".

## Method

- Source review of the four public pages, the layout, controllers, seeders, routes and build configuration.
- Production build served locally; Chrome driven by Playwright for screenshots and DOM measurements (tap-target sizes, overflow, heading outline, link targets, form fields, focus styles).
- Lighthouse 13.5.0 in mobile and desktop modes on all four local pages and the live home page.
- Live site checked with `curl` for raw HTML, headers and asset responses.

Not covered: real-device testing, screen-reader testing, the admin panel, and field performance data from real visitors.

## Phase 3 results

Added after implementation on the `ux-audit` branch. Measured on a production build with server-side rendering, served by `php artisan serve` without compression, the same way as the baseline.

### Lighthouse, before and after

| Page | Mode | Performance | Accessibility | Best practices | SEO | LCP | Weight |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Home | mobile | 63 → 71 | 78 → 100 | 100 → 100 | 83 → 100 | 7.9 s → 3.7 s | 3.4 MB → 0.48 MB |
| Home | desktop | 88 → 99 | 84 → 100 | 100 → 100 | 83 → 100 | 1.7 s → 0.8 s | 3.4 MB → 0.48 MB |
| Services | mobile | 67 → 89 | 81 → 100 | 100 → 100 | 92 → 100 | 6.0 s → 3.4 s | 1.2 MB → 0.44 MB |
| Services | desktop | 97 → 99 | 90 → 100 | 100 → 100 | 92 → 100 | 1.2 s → 0.8 s | 1.2 MB → 0.44 MB |
| Service page (new) | mobile | 71 | 100 | 100 | 100 | 3.5 s | 0.45 MB |
| Service page (new) | desktop | 100 | 100 | 100 | 100 | 0.7 s | 0.45 MB |
| About | mobile | 62 → 88 | 86 → 100 | 100 → 100 | 92 → 100 | 6.9 s → 3.4 s | 1.2 MB → 0.45 MB |
| About | desktop | 97 → 100 | 93 → 100 | 100 → 100 | 92 → 100 | 1.3 s → 0.7 s | 1.2 MB → 0.45 MB |
| Contact | mobile | 67 → 78 | 72 → 100 | 100 → 100 | 92 → 100 | 6.6 s → 3.6 s | 1.1 MB → 0.45 MB |
| Contact | desktop | 97 → 100 | 72 → 100 | 100 → 100 | 92 → 100 | 1.2 s → 0.7 s | 1.1 MB → 0.45 MB |

Mobile performance scores varied between runs by about 10 points because total blocking time moved between 50 ms and 650 ms; other processes were running on the test machine. Mobile LCP is still above the 2.5 s target in this setup. Most of the remaining weight is uncompressed JavaScript, so enabling gzip or Brotli on the production server is the next step; that has not been measured.

### Other measurements at 375 px

| Measure | Before | After |
| --- | --- | --- |
| Home page height | 15,952 px | 7,380 px |
| Dead links on the home page | 22 | 0 |
| Interactive elements under 44 px on the home page | 40 of 57 | 3 of 49: the off-screen skip link, the hidden honeypot field and one link inside a sentence |
| Form fields without an associated label | all | none |
| Horizontal overflow | 9 px | none |
| Images on the home page | 31, all remote stock | 2, both local |
| Raw HTML contains page content | no | yes |

After screenshots are in `doc/ux-audit/after/`.

## Second design pass

Hero pictures, a service card grid, softer cards and scroll reveals were added after Phase 3. Measured the same way as above. "Before" is the end of Phase 3.

| Page | Mode | Performance | LCP | Weight |
| --- | --- | --- | --- | --- |
| Home | mobile | 71 → 80 | 3.7 s → 4.3 s | 0.48 MB → 0.60 MB |
| Home | desktop | 99 → 99 | 0.8 s → 0.9 s | 0.48 MB → 0.60 MB |
| Services | mobile | 89 → 83 | 3.4 s → 3.9 s | 0.44 MB → 0.57 MB |
| Services | desktop | 99 → 99 | 0.8 s → 0.9 s | 0.44 MB → 0.58 MB |
| Service page | mobile | 71 → 85 | 3.5 s → 3.6 s | 0.45 MB → 0.49 MB |
| Service page | desktop | 100 → 99 | 0.7 s → 0.7 s | 0.45 MB → 0.48 MB |
| About | mobile | 88 → 82 | 3.4 s → 3.7 s | 0.45 MB → 0.50 MB |
| About | desktop | 100 → 99 | 0.7 s → 0.7 s | 0.45 MB → 0.48 MB |
| Contact | mobile | 78 → 83 | 3.6 s → 3.6 s | 0.45 MB → 0.47 MB |
| Contact | desktop | 100 → 99 | 0.7 s → 0.7 s | 0.45 MB → 0.45 MB |

Accessibility, best practices and SEO are 100 on every page in both modes.

Mobile performance scores moved by up to 14 points in either direction, which is within the run-to-run variation seen earlier, so the pictures did not lower the score in a measurable way. They did add weight, and mobile LCP is 0.1 to 0.6 s later on the pages where the hero picture is now the largest element. Phones are served an 800px hero (12 to 28 kB for the three seeded page heroes, up to 69 kB for a service picture) and 480px card thumbnails to limit this.

Screenshots for this pass are in `doc/ux-audit/pass2/`.
