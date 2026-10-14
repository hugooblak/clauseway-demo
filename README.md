# Clauseway: contract-review AI demo (WordPress)

A WordPress twin of the Clauseway concept site. **Clauseway is a fictional company.**
The name, logos, contracts and figures are sample content, and the form sends nothing.

**Live demo:** https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hugooblak/clauseway-demo/main/blueprint.json

It runs in WordPress Playground: a whole WordPress site in the browser tab, nothing installed.
Login is `admin` / `password`.

## What it shows

- **Hero scan**: a contract page is read by a beam, clauses get flagged as it passes and comment
  cards attach on drawn lines. Loops every ten seconds.
- **Product tabs** (Review, Redline, Compare, Approve): each plays its own scene and the tabs cycle
  by themselves while on screen. A click picks one.
- **How it works**: pinned section; a document is dropped in, scanned and turned into a summary as you scroll.
- **Playbooks**: rules draw lines to the clauses they matched.
- **Security**: a dark section where a document folds into a sealed lock as you scroll.
- **FAQ** on WordPress's own Details block, and a **demo form** that sends nothing and says so.
- All contract text, comments, steps and rules are edited in each block's sidebar.
- Reduced motion shows the finished frames. Without JavaScript every block shows its finished state.

## Deliberately not here

A real working form, more pages, prices, a blog. A real job connects the form block to a form
plugin or the sales calendar.

## How it is built

- Block theme, `theme.json`, native blocks and patterns. No page builder, no Custom HTML blocks.
- Plugin **Clauseway Blocks** holds the six parts normal blocks cannot do: hero scan, product tabs,
  how-it-works, playbook, security, form. One stylesheet and one script, one animation loop.
- Self-hosted fonts (Inter Tight, Inter). No third-party scripts.
- Checks: see `proof/`.
