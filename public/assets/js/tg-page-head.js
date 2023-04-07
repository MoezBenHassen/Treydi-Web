const ENABLE_PAGE_REVEALER = !0,
  ENABLE_PAGE_PRELOADER = !0,
  ENABLE_PAGE_REVEALER_USED = "show" === localStorage.getItem("page-revealer");
{
  let e = "cubic-bezier(0.8, 0, 0.2, 1)",
    t = document.createElement("div");
  t.classList.add("page-revealer"),
    document.documentElement.append(t),
    window.addEventListener("pageshow", () => {
      (t.style.visibility = ""),
        (t.style.transform = ""),
        (t.style.transformOrigin = "");
    }),
    ENABLE_PAGE_REVEALER_USED &&
      (async () => {
        localStorage.removeItem("page-revealer"),
          (t.style.transition = ""),
          (t.style.visibility = "visible"),
          (t.style.transform = "scaleY(1)"),
          (t.style.transformOrigin = "center bottom"),
          await new Promise((e) =>
            document.addEventListener("DOMContentLoaded", e)
          ),
          await new Promise((e) => requestAnimationFrame(e)),
          (t.style.transition = "transform 1.1s " + e),
          (t.style.transform = "scaleY(0)"),
          (t.style.transformOrigin = "center top"),
          await new Promise((e) => setTimeout(e, 1210)),
          (t.style.visibility = ""),
          (t.style.transform = ""),
          (t.style.transformOrigin = "");
      })();
  let a = (e) => {
    let t = location.protocol === e.protocol && location.origin === e.origin;
    if (!t || "_blank" === e.target) return !1;
    let a = location.pathname === e.pathname && location.search === e.search;
    if (!a) return !0;
    let i = e.hash || e.href !== e.origin + e.pathname + e.search + e.hash;
    return !i;
  };
  document.addEventListener("click", async (i) => {
    let n = i.target,
      r = n.closest("a");
    r &&
      r instanceof HTMLAnchorElement &&
      !i.defaultPrevented &&
      a(r) &&
      (i.preventDefault(),
      (t.style.transition = "transform 1.1s " + e),
      (t.style.visibility = "visible"),
      (t.style.transform = "scaleY(1)"),
      (t.style.transformOrigin = "center bottom"),
      await new Promise((e) => setTimeout(e, 1100)),
      localStorage.setItem("page-revealer", "show"),
      (location.href = r.href));
  });
}
if (!ENABLE_PAGE_REVEALER_USED) {
  let i = document.createElement("div");
  i.classList.add("tg-preloader"),
    (i.innerHTML = `
        <div class="tg-loading">
            <div></div><div></div><div></div><div></div>
        </div>
    `),
    document.documentElement.classList.add("show-preloader"),
    document.documentElement.append(i);
  let n = Date.now();
  (async () => {
    await new Promise((e) => document.addEventListener("DOMContentLoaded", e)),
      document.documentElement.classList.remove("show-preloader"),
      await new Promise((e) => requestAnimationFrame(e)),
      await new Promise((e) =>
        setTimeout(e, Math.max(0, 500 - (Date.now() - n)))
      ),
      (i.style.transition = "opacity 1.1s cubic-bezier(0.8, 0, 0.2, 1)"),
      (i.style.opacity = "0"),
      await new Promise((e) => setTimeout(e, 1100)),
      i.remove();
  })();
}
