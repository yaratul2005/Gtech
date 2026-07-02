# AGENTS.md — RATUL Build Protocol (RBP)

> This is the single source of truth for how AI/IDE tools must scaffold and
> extend this project. AGENTS.md is an open, tool-agnostic standard read
> natively by Cursor, Windsurf, Codex, Kilo Code, and most other AI coding
> tools on session start. If your tool doesn't read AGENTS.md yet, point it
> here manually (see **Tool Coverage** at the bottom).
>
> **Read this file completely before creating, moving, or renaming any
> file or folder in this project.**

---

## 1. Philosophy

This project does not use a generic `src/` / `components/` / `lib/` layout.
It uses a fixed five-folder system, always named exactly as below,
regardless of language, framework, or platform:

```
Root   → connective tissue only (config, entry point, this file)
Front  → everything the user sees/interacts with
Back   → everything that thinks/processes
Tunnel → everything that moves data (internal + external APIs)
Vault  → everything that is stored (assets, content, media, backups)
Setup  → everything that installs/upgrades/governs the system
```

Never invent alternate top-level folders. If something doesn't obviously
belong in one of the five, it goes in the most specific existing
subfolder — never loose in root.

---

## 2. Root Directory

Root contains only:

| File | Purpose |
|---|---|
| `AGENTS.md` | This file. Always read first. |
| `.env` / `.env.example` | Secrets/config |
| dependency manifest (`package.json`, `requirements.txt`, etc.) | Stack-native, whatever the framework requires |
| entry point (`index.js`, `main.py`, `server.js`, etc.) | Boots the app, wires Front/Back/Tunnel together |
| `.gitignore` | Excludes `/Vault/uploads`, `/Setup/db/*.sql`, env files |
| `README.md` | Human-facing intro |
| `CHANGELOG.md` | Rollup of all `update.md` entries |

No business logic ever lives at root.

---

## 3. `/Front` — Presentation Layer

```
/Front
├── update.md
├── /static
│   ├── /css
│   │   ├── base.css            → resets, root variables, typography scale
│   │   ├── layout.css          → grid/flex scaffolding
│   │   ├── variables.css       → colors, spacing, breakpoints as CSS vars
│   │   ├── /components         → ONE file per component (button.css, card.css, nav.css...)
│   │   ├── /utilities          → helper classes
│   │   └── /animations         → transitions.css, keyframes.css, hover-fx.css
│   ├── /js
│   │   ├── core.js             → boot/init only
│   │   ├── /modules            → ONE file per feature (nav.js, modal.js, slider.js...)
│   │   ├── /animations         → scroll-fx.js, entrance.js, parallax.js
│   │   └── /utils              → dom-helpers.js, debounce.js, validators.js
│   ├── /img  /fonts  /icons
│
└── /dyn
    ├── /components              → interactive/stateful UI (framework-native)
    ├── /pages   (or /views)     → route-level screens
    ├── /state                   → store/context/state-management
    ├── /hooks                   → reusable stateful logic (if applicable)
    └── /animations              → JS-driven, data-dependent motion
```

**Rule:** never dump all styles into one `style.css` or all scripts into
one `main.js`. Fragment aggressively — one concern, one file. Bundle at
build time; keep source split at author time.

---

## 4. `/Back` — Application Layer

```
/Back
├── update.md
├── /core          → server bootstrap, app instance, global middleware wiring
├── /controllers   → request handlers, ONE file per resource/domain
├── /services      → business logic, framework-agnostic
├── /models        → data schema/ORM definitions
├── /middleware    → auth guards, validators, error handlers
└── /config        → runtime config
```

**Rule:** controllers stay thin (route → validate → call service →
respond). All real logic lives in `/services`.

---

## 5. `/Tunnel` — Data Movement Layer

```
/Tunnel
├── tunnel.map.md      → living registry of every endpoint, internal + external
├── /internal
│   └── /v1             (version folders as the API evolves)
├── /external
│   ├── /payment
│   ├── /auth-providers
│   └── /misc
├── /schemas            → request/response contracts
└── /webhooks           → inbound receivers, one file per source
```

**Rule:** every new endpoint (internal or external) gets an entry in
`tunnel.map.md` in the same change — method, path, purpose, auth
requirement.

---

## 6. `/Vault` — Storage & Assets Layer

```
/Vault
├── vault.rules.md    → what's public vs gated
├── /assets           → source media, brand files
├── /content           → structured content (CMS-like JSON/Markdown)
├── /uploads           → user-generated (gitignored)
└── /backups           → snapshots (gitignored)
```

**Rule:** data only, never executable code. Anything that *processes*
Vault content belongs in `/Back/services`.

---

## 7. `/Setup` — Install, Upgrade & Governance Layer

Admin-gated at runtime — the only folder allowed to modify the live
system.

```
/Setup
├── setup.log.md
├── /install    → first-run bootstrap (create tables, seed admin, etc.)
├── /db         → migrations, seeders, schema diff tools
├── /scripts    → upgrade/patch scripts, run on demand
├── /auth       → admin-gate logic protecting this folder
└── /ruleset    → coding conventions, lint config
```

**Rule:** any script here that touches the database or live files must be
idempotent (safe to re-run) and must log its action to `setup.log.md`.

---

## 8. The `update.md` Standard

`/Front/update.md` and `/Back/update.md` (extend to `/Tunnel`, `/Vault`,
`/Setup` if activity warrants it), newest entry on top:

```markdown
## [YYYY-MM-DD] vX.Y.Z
**Agent:** Claude Code / Cursor / manual
**Scope:** Front → /dyn/components/checkout.jsx

### Changed
- Short description of what changed and why

### Files touched
- path/one
- path/two

### Notes
- Anything the next AI session should know before touching this again
```

**Rule:** read the relevant `update.md` top-to-bottom before editing
anything in that folder. Append a new entry after finishing — never edit
past entries.

---

## 9. Directive Summary (checklist form)

```
1. Read this file before generating or moving anything.
2. Never create top-level folders outside Front / Back / Tunnel / Vault / Setup.
3. Front: split static vs dyn. Fragment CSS/JS into many single-concern files.
4. Back: controllers thin, services hold logic.
5. Tunnel: every endpoint logged in tunnel.map.md in the same change.
6. Vault: data only, never executable code.
7. Setup: admin-gated, idempotent scripts, log every run.
8. Read update.md before editing Front/Back; append an entry after.
9. Match existing naming conventions in a folder — don't reinvent them mid-project.
10. When unsure where something belongs, prefer the more specific subfolder over root.
```

---

## 10. Quick Reference Tree

```
/
├── AGENTS.md
├── README.md
├── CHANGELOG.md
├── .env
├── .gitignore
├── [entry point]
│
├── /Front   (update.md, /static, /dyn)
├── /Back    (update.md, core/ controllers/ services/ models/ middleware/ config/)
├── /Tunnel  (tunnel.map.md, internal/ external/ schemas/ webhooks/)
├── /Vault   (vault.rules.md, assets/ content/ uploads/ backups/)
└── /Setup   (setup.log.md, install/ db/ scripts/ auth/ ruleset/)
```

---

## 11. Tool Coverage

AGENTS.md is read natively by most AI coding tools. As of now, **Claude
Code specifically looks for `CLAUDE.md`**, not `AGENTS.md`. To keep a
single source of truth, add this exact one-line file at root — nothing
more — so no content is ever duplicated or drifts out of sync:

**`CLAUDE.md`**
```
See AGENTS.md — this project's full build protocol lives there.
```

If a future tool you use doesn't auto-detect AGENTS.md, use the same
pattern: a one-line pointer file in whatever filename that tool expects,
never a copy of the content.

---

*Stack-agnostic by design. Shaped for web/webapp projects, but the same
five-folder logic extends to mobile/desktop by treating `/Front` as the
client layer regardless of platform.*
