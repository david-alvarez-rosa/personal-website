(() => {
  let activeImg = null, angle = 0, lastT = 0, startT = 0, raf = 0, busy = false;

  function reset(img) {
    cancelAnimationFrame(raf);
    activeImg = null;
    img.style.transform = "rotate(" + (angle % 360) + "deg)";
    void img.offsetWidth;
    img.style.transition = "";
    img.style.transform = "";
  }

  function explode() {
    busy = true;
    const pieces = [...document.querySelectorAll("main > *, footer")];
    const cx = innerWidth / 2, cy = innerHeight / 2;
    const rects = pieces.map((el) => el.getBoundingClientRect());
    document.body.style.overflow = "hidden";
    const items = pieces.map((el, idx) => {
      const r = rects[idx];
      let dx = r.left + r.width / 2 - cx, dy = r.top + r.height / 2 - cy;
      let d = Math.hypot(dx, dy);
      if (d < 1) { const a = Math.random() * Math.PI * 2; dx = Math.cos(a); dy = Math.sin(a); d = 1; }
      const sp = 700 + Math.random() * 900;
      el.style.position = "fixed";
      el.style.left = r.left + "px";
      el.style.top = r.top + "px";
      el.style.width = r.width + "px";
      el.style.height = r.height + "px";
      el.style.margin = "0";
      el.style.zIndex = "9000";
      el.style.willChange = "transform";
      el.style.cursor = "grab";
      el.style.touchAction = "none";
      return { el, left: r.left, top: r.top, w: r.width, h: r.height, x: 0, y: 0, a: 0, vx: dx / d * sp, vy: dy / d * sp - 250 - Math.random() * 250, va: (Math.random() - 0.5) * 900, dragging: false };
    });
    let last = 0;
    function step(t) {
      if (!last) last = t;
      const dt = Math.min((t - last) / 1000, 0.05);
      last = t;
      for (const it of items) {
        if (it.dragging) continue;
        it.vy += 900 * dt;
        it.x += it.vx * dt;
        it.y += it.vy * dt;
        it.a += it.va * dt;
        const rad = it.a * Math.PI / 180, sin = Math.abs(Math.sin(rad)), cos = Math.abs(Math.cos(rad));
        const hw = (cos * it.w + sin * it.h) / 2, hh = (sin * it.w + cos * it.h) / 2;
        let cxp = it.left + it.x + it.w / 2, cyp = it.top + it.y + it.h / 2;
        if (cxp - hw < 0) { cxp = hw; it.vx = Math.abs(it.vx) * 0.6; it.va *= 0.7; }
        else if (cxp + hw > innerWidth) { cxp = innerWidth - hw; it.vx = -Math.abs(it.vx) * 0.6; it.va *= 0.7; }
        if (2 * hh <= innerHeight && cyp - hh < 0) { cyp = hh; it.vy = Math.abs(it.vy) * 0.6; }
        else if (cyp + hh > innerHeight) { cyp = innerHeight - hh; it.vy = -Math.abs(it.vy) * 0.55; it.vx *= 0.8; it.va *= 0.6; }
        it.x = cxp - it.w / 2 - it.left;
        it.y = cyp - it.h / 2 - it.top;
        it.el.style.transform = "translate(" + it.x + "px," + it.y + "px) rotate(" + it.a + "deg)";
      }
      raf = requestAnimationFrame(step);
    }
    raf = requestAnimationFrame(step);

    let drag = null, grabX = 0, grabY = 0, px = 0, py = 0, pt = 0;
    const find = (target) => items.find((it) => it.el.contains(target)) || null;
    const clamp = (v, lo, hi) => Math.max(lo, Math.min(hi, v));

    document.addEventListener("pointerdown", (e) => {
      const it = find(e.target);
      if (!it) return;
      e.preventDefault();
      drag = it;
      it.dragging = true;
      it.vx = it.vy = it.va = 0;
      it.el.style.zIndex = "9500";
      it.el.style.cursor = "grabbing";
      grabX = e.clientX - (it.left + it.x);
      grabY = e.clientY - (it.top + it.y);
      px = e.clientX; py = e.clientY; pt = e.timeStamp;
    });

    document.addEventListener("pointermove", (e) => {
      if (!drag) return;
      const it = drag;
      const rad = it.a * Math.PI / 180, sin = Math.abs(Math.sin(rad)), cos = Math.abs(Math.cos(rad));
      const hw = (cos * it.w + sin * it.h) / 2, hh = (sin * it.w + cos * it.h) / 2;
      let cxp = it.left + (e.clientX - grabX - it.left) + it.w / 2;
      let cyp = it.top + (e.clientY - grabY - it.top) + it.h / 2;
      cxp = clamp(cxp, hw, Math.max(hw, innerWidth - hw));
      cyp = Math.min(cyp, innerHeight - hh);
      if (innerHeight - hh >= hh) cyp = Math.max(cyp, hh);
      it.x = cxp - it.w / 2 - it.left;
      it.y = cyp - it.h / 2 - it.top;
      const dt = (e.timeStamp - pt) / 1000;
      if (dt > 0) {
        it.vx = (e.clientX - px) / dt;
        it.vy = (e.clientY - py) / dt;
        px = e.clientX; py = e.clientY; pt = e.timeStamp;
      }
      it.el.style.transform = "translate(" + it.x + "px," + it.y + "px) rotate(" + it.a + "deg)";
    });

    document.addEventListener("pointerup", () => {
      if (!drag) return;
      drag.el.style.zIndex = "9000";
      drag.el.style.cursor = "grab";
      drag.dragging = false;
      drag = null;
    });
  }

  function frame(t) {
    if (!activeImg) return;
    if (!lastT) { lastT = startT = t; }
    const dt = (t - lastT) / 1000;
    const elapsed = (t - startT) / 1000;
    const vel = 120 + elapsed * elapsed * 25;
    if (vel >= 3000) { reset(activeImg); explode(); return; }
    angle += vel * dt;
    activeImg.style.transform = "rotate(" + angle + "deg)";
    lastT = t;
    raf = requestAnimationFrame(frame);
  }

  document.addEventListener("mouseover", (e) => {
    if (busy) return;
    const img = e.target.closest(".side img");
    if (!img || img === activeImg) return;
    activeImg = img;
    angle = 0; lastT = 0;
    img.style.transition = "none";
    raf = requestAnimationFrame(frame);
  });

  document.addEventListener("mouseout", (e) => {
    if (busy) return;
    const img = e.target.closest(".side img");
    if (!img || img !== activeImg) return;
    reset(img);
  });
})();
