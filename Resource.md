# PHP Shared Hosting Stack Profile
### An AGENTS.md extension for high-impact design on constrained servers

> This file assumes `AGENTS.md` (RATUL Build Protocol) already governs the
> Front/Back/Tunnel/Vault/Setup structure. This document is the **stack
> profile** for PHP + shared hosting specifically — it tells the AI *how*
> to fill each folder given real hosting constraints, and how to get
> agency/studio-grade motion design without a server that can afford it.

---

## 1. Reality Check — What Shared Hosting Actually Gives You

| Resource | Typical shared hosting reality | Design implication |
|---|---|---|
| CPU/process time | Shared, throttled, execution timeout (often 30–60s) | No heavy server-side rendering loops, no image processing on every request |
| Persistent processes | None — no Node daemon, no WebSocket server, no queue worker | Real-time features simulate via polling/SSE, not sockets |
| Memory | Often 128–512MB per PHP process | Avoid loading huge datasets into memory; paginate everything |
| Cron | Available via cPanel, usually minimum 5–15 min interval | Background jobs (backups, cache warm, digest emails) go here, not in-request |
| Composer/SSH | Increasingly available, but assume it might not be | Prefer zero-dependency PHP where possible; vendor anything critical |
| Redis/Memcached | Rarely available | File-based caching in `/Vault` or `/Setup` |
| Node/build tools | Not on the server | **Build locally, upload compiled output.** Server never runs `npm`. |
| Bandwidth/CPU on client | Effectively unlimited (their device, their battery) | **This is where the budget goes.** Push animation, particles, and motion to the browser — GPU-composited, not server-rendered. |

**Core principle:** the server's only job is to answer data questions fast
and get out of the way. Every ounce of "wow" — particles, transitions,
motion — is a client-side, GPU-accelerated concern with zero server cost.

---

## 2. Stack Decisions

- **PHP:** vanilla PHP 8.x, PDO for MySQL — no framework by default (Laravel/Symfony assume resources shared hosts don't guarantee: CLI access, long-lived processes, sometimes Composer). If the specific host confirms Composer + adequate PHP-FPM, a micro-framework (Slim) is acceptable — but the folder structure below stays identical either way.
- **Routing:** single front controller (`index.php`) + `.htaccess` rewrite rules. No `mod_rewrite` reliance beyond what's universally supported on shared hosts (it is).
- **Frontend build:** compiled locally (Vite/esbuild/plain concatenation), only the **output** (`.css`/`.js`) is uploaded. The server never builds anything.
- **Database:** MySQL/MariaDB via PDO, prepared statements only, no ORM overhead unless the project is large enough to justify it.
- **Caching:** flat-file cache (serialized PHP or JSON) in a protected `/Vault/cache` directory. No Redis assumption.
- **Real-time:** Server-Sent Events (SSE) or short-poll, never raw sockets — shared hosting can't hold persistent socket connections.

---

## 3. Folder Mapping (PHP-specific)

```
/Front
├── update.md
├── /static
│   ├── /css        → compiled, minified output only — source lives in a local /src not deployed
│   ├── /js          → compiled output; particle/motion engines live here as standalone modules
│   └── /img /fonts /icons   → WebP/AVIF primary, with fallback where needed
└── /dyn
    ├── /components   → PHP partials/includes (header.php, card.php, modal.php)
    ├── /pages         → route-mapped PHP templates
    └── /animations    → JS motion modules, loaded lazily per page

/Back
├── update.md
├── /core          → bootstrap.php, db.php (PDO singleton), autoload/includes
├── /controllers   → one file per resource, thin
├── /services      → business logic
├── /models         → PDO-based data access
├── /middleware     → auth.php, csrf.php, rateLimit.php (file-based)
└── /config          → db.config.php, mail.config.php

/Tunnel
├── tunnel.map.md
├── /internal      → api/*.php endpoints, JSON in/out, hit via fetch()
├── /external       → cURL wrapper classes per provider
├── /schemas         → input validation definitions (arrays/JSON schema-lite)
└── /webhooks         → inbound receivers (payment confirps, etc.)

/Vault
├── vault.rules.md
├── /assets  /content  /uploads (0755, never 0777)  /backups
└── /cache            → flat-file cache, gitignored

/Setup
├── setup.log.md
├── /install    → install.php wizard (checks PHP version, tests DB connection, writes config, gitignored after run or self-locking via a flag file)
├── /db          → .sql migration files, run in order via a small PHP runner
├── /scripts      → cron-triggered maintenance (backup.php, cache-warm.php)
├── /auth          → simple session-based admin gate
└── /ruleset
```

**Cron-driven `/Setup` rule:** anything that would be "background jobs" on
a normal server (cache warming, digest emails, backups, cleanup) becomes a
PHP script in `/Setup/scripts` triggered by cPanel cron — never a
long-running process.

---

## 4. Design System — Deep Color, High Contrast

A dark, premium palette built for glow/glass effects to actually pop:

```css
:root {
  /* Base */
  --color-void:      #05060a;   /* near-black background */
  --color-ink:       #0b0d12;   /* elevated surface black */
  --color-surface:   #12141c;   /* card/panel surface */

  /* Blue range */
  --color-blue-deep: #0a2540;   /* structural, low-key blue */
  --color-blue-core: #1e3a8a;   /* primary brand blue */
  --color-blue-glow: #3b82f6;   /* interactive/accent blue */
  --color-cyan-pulse:#00d4ff;   /* highlight/particle glow */

  /* White range */
  --color-white:     #ffffff;   /* headline text */
  --color-fog:       #cbd5e1;   /* body text on dark */
  --color-mist:      #64748b;   /* muted/secondary text */

  /* Glass */
  --glass-fill:  rgba(255,255,255,0.04);
  --glass-border:rgba(255,255,255,0.08);
  --glow-blue:   0 0 40px rgba(59,130,246,0.35);
  --glow-cyan:   0 0 24px rgba(0,212,255,0.4);
}
```

**Guidelines:**
- Backgrounds stay near-black (`--color-void`/`--color-ink`), never flat pure black across huge areas — add subtle radial gradients toward `--color-blue-deep` for depth.
- White is used sparingly and deliberately — headlines, key CTAs, active states — so it reads as premium contrast, not default text color.
- Glow (`box-shadow`/`filter: drop-shadow`) on interactive elements only, using `--glow-blue`/`--glow-cyan` — this is what makes dark UIs feel alive without extra DOM weight.
- Glass panels (`--glass-fill` + `backdrop-filter: blur(12px)` + `--glass-border`) for cards/nav — cheap on GPU, expensive-looking visually.

---

## 5. Motion, Transitions & Particles — Zero Server Cost, GPU Only

Every recommendation below runs entirely in the browser. Server involvement: zero.

### 5.1 Rules before anything else
- Animate only `transform` and `opacity`. Never animate `width`, `top`, `left`, `box-shadow` position, etc. — those trigger layout/paint, not composite.
- Always respect:
  ```css
  @media (prefers-reduced-motion: reduce) {
    * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
  }
  ```
- `will-change` only on elements actively animating, removed after — not applied blanket.
- Pause any `requestAnimationFrame` loop when `document.visibilityState !== 'visible'` and when the canvas/section is off-screen (`IntersectionObserver`).

### 5.2 Scroll-triggered reveals
`IntersectionObserver` + a single reusable class — no scroll-linked JS math:
```js
// /Front/static/js/modules/reveal.js
const io = new IntersectionObserver((entries) => {
  entries.forEach(e => e.isIntersecting && e.target.classList.add('is-visible'));
}, { threshold: 0.15 });
document.querySelectorAll('[data-reveal]').forEach(el => io.observe(el));
```
```css
[data-reveal] { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
[data-reveal].is-visible { opacity: 1; transform: translateY(0); }
```

### 5.3 Particle / constellation background
A single self-hosted canvas module (no CDN dependency, no heavy library):
- 2D canvas, `requestAnimationFrame`, particle count capped and scaled by `window.innerWidth` (fewer particles on mobile).
- Optional connective lines between nearby particles for a "network/constellation" look — reads as very premium against the black/blue palette, cheap to compute at capped particle counts (80–150 desktop, 30–50 mobile).
- Mouse-reactive drift is optional and disabled on touch devices.
- Pauses via the visibility/intersection rules in 5.1.

This lives at `/Front/static/js/modules/particles.js`, loaded only on
pages/sections that use it (never globally) to keep first-paint fast.

### 5.4 Page/section transitions
- Prefer the native **View Transitions API** (`document.startViewTransition`) for same-document state changes — zero library weight, hardware accelerated, graceful no-op fallback in unsupported browsers.
- For multi-page PHP navigation (real page loads), a lightweight fade-out/fade-in on `<body>` via the `pagehide`/`DOMContentLoaded` events gives an SPA-like feel without an SPA.

### 5.5 Micro-interactions
- Buttons/cards: `transform: translateY(-2px) scale(1.01)` + glow shadow on hover, 150–200ms ease — cheap, high perceived polish.
- Loading/skeleton states: CSS gradient sweep animation (`background-position` on a `linear-gradient`, GPU-friendly) rather than JS-driven spinners where possible.

---

## 6. Performance Checklist (protects the "outstanding" from feeling slow)

- [ ] Images: WebP/AVIF, responsive `srcset`, `loading="lazy"` on everything below the fold
- [ ] CSS/JS: concatenated + minified locally before upload, never built on the server
- [ ] `.htaccess`: gzip/br compression + far-future cache headers on `/Front/static/*`
- [ ] Fonts: subset, `font-display: swap`, self-hosted (no external font CDN round-trip)
- [ ] Particle/animation modules: loaded via dynamic `import()` only on pages that use them
- [ ] Critical CSS inlined for above-the-fold content; rest deferred
- [ ] Database: indexes on every filtered/sorted column; no `SELECT *` in hot paths
- [ ] Cache: flat-file cache in `/Vault/cache` for expensive queries, warmed via `/Setup/scripts/cache-warm.php` on cron

---

## 7. AI Directive Additions (append to AGENTS.md checklist for this stack)

```
11. Server-side PHP stays data/render only — no animation, particle, or motion logic ever generated server-side.
12. All motion code animates transform/opacity only, and respects prefers-reduced-motion.
13. Particle/canvas modules are self-hosted, capped by device width, and pause when off-screen or tab is hidden.
14. No build tools assumed on the server — CSS/JS are delivered as pre-compiled static output.
15. No sockets, no persistent processes, no Redis assumed — background work goes through /Setup/scripts on cron.
16. Palette defaults to the --color-void / --color-blue-* / --color-white tokens in section 4 unless the project defines its own.
```

---

*This profile assumes nothing about the specific host beyond the table in
Section 1. If a target host confirms more (SSH, Composer, higher memory
limits, Redis), those constraints simply relax — the folder structure and
motion approach above don't need to change either way.*
