/* Clauseway Blocks: one animation loop for the whole page (hero scan, product tabs, scroll sections, playbook). */
(() => {
'use strict';
const $ = (s, r = document) => (r ? r.querySelector(s) : null), $$ = (s, r = document) => (r ? [...r.querySelectorAll(s)] : []);
const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
const ease = t => t < .5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
const mobile = () => innerWidth <= 860;

/* header border */
const hdr = $('header');
if (hdr) addEventListener('scroll', () => hdr.classList.toggle('stuck', scrollY > 8), { passive: true });

/* ---------- hero ---------- */
const doc = $('#doc'), beam = $('#beam'), chip = $('#chip'), cardsEl = $('#cards'), wires = $('#wires'), stage = $('#stage');
const cls = $$('.cl', doc), cards = $$('.card', cardsEl);
const NS = 'http://www.w3.org/2000/svg';
let paths = [];
function layoutHero() {
  if (!doc) return;
  wires.innerHTML = ''; paths = [];
  if (mobile()) { cards.forEach(c => c.style.top = ''); return; }
  const sr = stage.getBoundingClientRect(), dr = doc.getBoundingClientRect(), cr = cardsEl.getBoundingClientRect();
  let prev = -Infinity;
  const flagged = cls.filter(c => c.dataset.card !== undefined);
  flagged.forEach((cl, k) => {
    const r = cl.getBoundingClientRect(), card = cards[k];
    if (!card) return;
    const mid = r.top + r.height / 2 - cr.top, h = card.offsetHeight;
    let top = Math.max(mid - h / 2, prev + 16); card.style.top = top + 'px'; prev = top + h;
    const x1 = dr.right - sr.left, y1 = r.top + r.height / 2 - sr.top, x2 = cr.left - sr.left, y2 = cr.top - sr.top + top + 30;
    const xm = x1 + (x2 - x1) / 2;
    const p = document.createElementNS(NS, 'path');
    p.setAttribute('d', `M${x1} ${y1} H${xm} V${y2} H${x2}`); p.setAttribute('pathLength', '1'); wires.appendChild(p); paths[k] = p;
  });
  stage.style.minHeight = Math.max(doc.offsetHeight, prev) + 'px';
}
const heroState = { flagged: [false, false, false], read: -1, beamOn: false, cycleHold: false };
let heroVisible = true;
function heroFrame(t) {
  if (!doc) return;
  const T = 10, s = t % T;
  const H = doc.offsetHeight, y0 = 64, y1 = H - 14;
  const sp = clamp((s - .8) / 5);
  const y = y0 + ease(sp) * (y1 - y0);
  const on = s >= .8 && s < 6.2;
  beam.style.transform = `translateY(${y}px)`;
  if (on !== heroState.beamOn) { beam.style.opacity = on ? 1 : 0; heroState.beamOn = on; }
  let read = 0;
  cls.forEach((cl, i) => { if (s >= .8 && y > cl.offsetTop + cl.offsetHeight * .5 || (s >= 6.2 && s < 9.3)) read = i + 1; });
  const resetting = s >= 9.3 || s < .8;
  if (resetting) read = 0;
  if (read !== heroState.read) {
    heroState.read = read;
    const flaggedTotal = cls.filter(c => c.dataset.f !== 'none').length;
    chip.textContent = read === 0 ? 'Ready' : (read >= cls.length ? `${flaggedTotal} flagged` : `Reading ${read} of ${cls.length}`);
    let k = 0;
    cls.forEach((cl, i) => {
      const f = cl.dataset.f, show = i < read && f !== 'none';
      cl.classList.toggle('f-high', show && f === 'high'); cl.classList.toggle('f-med', show && f === 'med');
      if (cl.dataset.card !== undefined) { if (cards[k]) cards[k].classList.toggle('on', show); if (paths[k]) paths[k].classList.toggle('on', show); k++; }
    });
  }
}
const heroFinal = () => { heroFrame(7.5); };

/* ---------- tabs ---------- */
const tabs = $$('.tab'), scenes = $$('.scene'), rscenes = $$('.rscene'), crows = $$('.crow');
let cur = 0, picked = false, tabVisible = false, tabTimer = 0;
function typeSetup() { $$('.typed').forEach(el => { const w = el.dataset.text.split(' '); el.innerHTML = w.map((x, i) => `<span class="w" style="--i:${i}">${x}</span>`).join(' '); }); }
function playTab(i) {
  if (!tabs.length) return;
  cur = i;
  tabs.forEach((t, k) => { t.setAttribute('aria-selected', k === i); t.tabIndex = k === i ? 0 : -1; });
  [scenes, rscenes].forEach(list => list.forEach((s, k) => { s.classList.remove('on'); }));
  void document.body.offsetWidth;
  scenes[i].classList.add('on'); rscenes[i].classList.add('on');
  $$('.edit').forEach(e => e.classList.remove('acc'));
  if (i === 1) [2.2, 3.4, 4.6].forEach((d, k) => setTimeout(() => cur === 1 && $$('.edit')[k]?.classList.add('acc'), d * 1000));
  const cnt = $('#cnt'), target = +(($('.ring') || {}).dataset || {}).n || 0; if (cnt) cnt.textContent = i === 0 ? '0' : cnt.textContent;
  if (i === 0 && cnt) { const t0 = performance.now(); const tick = n => { const p = clamp((n - t0 - 300) / 1100); cnt.textContent = Math.round(p * target); if (p < 1 && cur === 0) requestAnimationFrame(tick); }; requestAnimationFrame(tick); }
  crows.forEach(c => c.classList.remove('act'));
  const act = crows[({ 0: 1, 1: 1, 2: 0, 3: 3 })[i]]; if (act) act.classList.add('act');
}
tabs.forEach((t, i) => {
  t.addEventListener('click', () => { picked = true; playTab(i); });
  t.addEventListener('keydown', e => {
    const d = e.key === 'ArrowRight' ? 1 : e.key === 'ArrowLeft' ? -1 : 0;
    if (!d) return;
    e.preventDefault(); picked = true; const n = (i + d + tabs.length) % tabs.length; playTab(n); tabs[n].focus();
  });
});
function tabLoop() {
  clearInterval(tabTimer);
  if (!tabs.length) return;
  tabTimer = setInterval(() => { if (tabVisible && !picked && !reduce) playTab((cur + 1) % 4); }, 6500);
}

/* ---------- how it works (scroll) ---------- */
const how = $('#how'), hdoc = $('#hdoc'), hrep = $('#hrep'), hbeam = $('#hbeam'), up = $('#up'), hcnt = $('#hcnt');
const hls = $$('.hl', hdoc), steps = $$('.step');
let lastStep = -1, lastHl = -1;
function prog(el) { const r = el.getBoundingClientRect(); return clamp(-r.top / (r.height - innerHeight)); }
function howFrame() {
  if (!how) return;
  const p = prog(how);
  const shift = innerWidth <= 860 ? 0 : 0;
  const q0 = ease(clamp(p / .26));
  hdoc.style.transform = `translateX(${(1 - q0) * 70}%)`;
  hdoc.style.opacity = clamp(q0 * 3);
  up.style.transform = `scaleX(${clamp((p - .1) / .15)})`;
  const q1 = clamp((p - .33) / .3);
  const hh = hdoc.clientHeight, a = 90, b = hh - 50;
  hbeam.style.opacity = (p > .33 && p < .66) ? 1 : 0;
  hbeam.style.transform = `translateY(${a + q1 * (b - a)}px)`;
  let read = 0;
  hls.forEach((h, i) => { if (p > .33 && (a + q1 * (b - a)) > h.offsetTop + h.offsetHeight * .5 || p >= .66) read = i + 1; });
  if (p < .33) read = 0;
  if (read !== lastHl) { lastHl = read; hls.forEach((h, i) => h.classList.toggle('f', i < read && !!h.querySelector('small'))); hcnt.textContent = `${how.dataset.read || 'Clauses read'}: ${read} of ${hls.length}`; }
  const q2 = ease(clamp((p - .68) / .26));
  const dx = innerWidth <= 860 ? -24 : -52;
  const mob = innerWidth <= 860;
  hdoc.style.transform = `translateX(${(1 - q0) * 70 + (mob ? 0 : q2 * dx)}%) scale(${1 - q2 * .06})`;
  hdoc.style.opacity = mob ? clamp(q0 * 3) * (1 - clamp(q2 * 1.6)) : clamp(q0 * 3);
  hrep.style.opacity = clamp(q2 * 2.2);
  hrep.style.transform = `translateX(${(1 - q2) * (mob ? 0 : 40)}px) translateY(${mob ? (1 - q2) * 24 : 0}px)`;
  const st = p < .31 ? 0 : p < .66 ? 1 : 2;
  if (st !== lastStep) { lastStep = st; steps.forEach((s, i) => s.classList.toggle('on', i === st)); }
}

/* ---------- playbooks ---------- */
const rules = $$('.rule'), pcl = $$('.pcl'), pw = $('#pw'), pgrid = $('#pgrid');
let pbVisible = false, pbPaths = [];
function layoutPb() {
  if (!pw) return;
  pw.innerHTML = ''; pbPaths = [];
  if (mobile()) return;
  const g = pgrid.getBoundingClientRect();
  rules.forEach((r, i) => {
    const a = r.getBoundingClientRect(), b = pcl[+r.dataset.c].getBoundingClientRect();
    const x1 = a.right - g.left, y1 = a.top + a.height / 2 - g.top, x2 = b.left - g.left, y2 = b.top + b.height / 2 - g.top, xm = (x1 + x2) / 2;
    const p = document.createElementNS(NS, 'path'); p.setAttribute('d', `M${x1} ${y1} C${xm} ${y1} ${xm} ${y2} ${x2} ${y2}`); p.setAttribute('pathLength', '1');
    p.style.stroke = r.dataset.off === '1' ? '#E5391F' : '#111'; pw.appendChild(p); pbPaths[i] = p;
  });
}
let pbStep = -1;
function pbFrame(t) {
  if (!pw) return;
  const s = t % 15, step = Math.floor(s / 3.2); // 0..3 rules, 4 = hold
  const phase = s - step * 3.2;
  const k = Math.min(step, 4);
  if (k === pbStep && !(k < 4 && phase > 1.1 && !rules[k].classList.contains('done'))) return;
  if (k !== pbStep && k === 0) { rules.forEach(r => r.classList.remove('act', 'done', 'off')); pcl.forEach(c => c.classList.remove('act', 'done', 'off')); pbPaths.forEach(p => p && p.classList.remove('on')); }
  pbStep = k;
  rules.forEach((r, i) => { r.classList.toggle('act', i === k && k < 4); });
  pcl.forEach(c => c.classList.remove('act'));
  if (k < 4) {
    const r = rules[k], c = pcl[+r.dataset.c];
    c.classList.add('act');
    pbPaths[k] && pbPaths[k].classList.add('on');
    setTimeout(() => { if (pbStep === k) { r.classList.add('done'); c.classList.add('done'); if (r.dataset.off === '1') { r.classList.add('off'); c.classList.add('off'); } else c.classList.remove('off'); } }, 700);
  }
  if (k >= 4) { rules.forEach(r => r.classList.remove('act')); }
  if (k > 0) rules.slice(0, k).forEach((r, i) => { r.classList.add('done'); if (r.dataset.off === '1') r.classList.add('off'); const c = pcl[+r.dataset.c]; c.classList.add('done'); if (r.dataset.off === '1') c.classList.add('off'); pbPaths[i] && pbPaths[i].classList.add('on'); });
}
function pbStatic() { if (!pw) return; rules.forEach((r, i) => { r.classList.add('done'); const c = pcl[+r.dataset.c]; c.classList.add('done'); if (r.dataset.off === '1') { r.classList.add('off'); c.classList.add('off'); } pbPaths[i] && pbPaths[i].classList.add('on'); }); }

/* ---------- security (scroll) ---------- */
const sec = $('#security'), vdoc = $('#vdoc'), vcard = $('#vcard'), vlock = $('#vlock'), vsh = $('#vsh'), vseal = $('#vseal');
function secFrame() {
  if (!sec) return;
  const p = prog(sec);
  const a = ease(clamp(p / .5));
  vdoc.setAttribute('transform', `translate(200 195) scale(${1 - a * .62}) translate(-200 -195)`);
  vdoc.setAttribute('opacity', 1 - clamp((p - .42) / .14));
  vcard.setAttribute('opacity', .35 + .65 * clamp((p - .3) / .3));
  const l = clamp((p - .45) / .12);
  vlock.setAttribute('opacity', l);
  const sh = ease(clamp((p - .55) / .25));
  vsh.setAttribute('transform', `translate(0 ${(1 - sh) * -22})`);
  vseal.setAttribute('opacity', clamp((p - .82) / .1));
}

/* ---------- single loop ---------- */
const deltas = []; let last = performance.now(), lastY = -1, t0 = last;
const vis = new Map();
const io = new IntersectionObserver(es => es.forEach(e => vis.set(e.target, e.isIntersecting)), { rootMargin: '80px' });
[doc, $('#frame'), $('#pgrid')].filter(Boolean).forEach(el => io.observe(el));
function loop(now) {
  deltas.push(now - last); if (deltas.length > 240) deltas.shift(); last = now;
  const t = (now - t0) / 1000;
  if (!reduce) {
    if (vis.get(doc)) heroFrame(t);
    if (vis.get($('#pgrid'))) pbFrame(t - (window.__pb0 ?? (window.__pb0 = t)));
  }
  tabVisible = !!vis.get($('#frame'));
  if (scrollY !== lastY) { lastY = scrollY; howFrame(); secFrame(); }
  requestAnimationFrame(loop);
}
window.MotionEngine = { jank() { const d = [...deltas].sort((a, b) => a - b); return { p95: d[Math.floor(d.length * .95)] | 0, max: d[d.length - 1] | 0 }; } };

/* form */
const form = $('#form');
if (form) form.addEventListener('submit', e => { e.preventDefault(); const th = $('#thanks'); th.hidden = false; th.style.display = 'block'; e.target.style.display = 'none'; });

/* init */
function init() {
  document.documentElement.classList.add('js');
  typeSetup(); layoutHero(); layoutPb();
  if (reduce) { heroFinal(); pbStatic(); }
  playTab(0); tabLoop(); howFrame(); secFrame();
  requestAnimationFrame(loop);
  requestAnimationFrame(() => requestAnimationFrame(() => { window.__ready = true; }));
}
let rt; addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(() => { layoutHero(); layoutPb(); heroState.read = -1; pbStep = -1; howFrame(); }, 150); });
(document.fonts && document.fonts.ready ? document.fonts.ready : Promise.resolve()).then(init);
// If a second copy of this script ever runs, the block ids would clash: run once.
})();
