(() => {
  const dock = document.querySelector(".nav-dock");
  const panel = document.querySelector(".nav-expand-panel");
  const header = document.querySelector(".site-header");
  const btn = document.getElementById("nav-more");
  if (!dock || !btn) return;

  const isOpen = () => dock.classList.contains("is-expanded");
  let collapseTimer = 0;

  const finishCollapse = () => {
    dock.classList.remove("is-collapsing");
    window.clearTimeout(collapseTimer);
  };

  const closeDrawer = () => {
    if (!isOpen() && !dock.classList.contains("is-collapsing")) return;
    dock.classList.add("is-collapsing");
    dock.classList.remove("is-expanded");
    header?.classList.remove("site-header--nav-open");
    btn.setAttribute("aria-expanded", "false");
    window.clearTimeout(collapseTimer);
    collapseTimer = window.setTimeout(finishCollapse, 520);
  };

  const openDrawer = () => {
    finishCollapse();
    dock.classList.add("is-expanded");
    header?.classList.add("site-header--nav-open");
    btn.setAttribute("aria-expanded", "true");
  };

  if (panel) {
    panel.addEventListener("transitionend", (e) => {
      if (e.target !== panel || e.propertyName !== "grid-template-rows") return;
      if (dock.classList.contains("is-collapsing")) finishCollapse();
    });
  }

  document.addEventListener("click", (e) => {
    const target = e.target;
    if (!(target instanceof Element)) return;

    const moreBtn = target.closest("#nav-more");
    if (moreBtn) {
      e.preventDefault();
      if (!isOpen()) openDrawer();
      else closeDrawer();
      return;
    }

    if (target.closest(".nav-secondary")) return;
    if (isOpen()) closeDrawer();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && isOpen()) closeDrawer();
  });

  const SCROLL_DELTA = 4;
  let lastScrollY = window.scrollY;

  const maxScrollY = () => {
    const doc = document.documentElement;
    return Math.max(0, doc.scrollHeight - window.innerHeight);
  };

  window.addEventListener(
    "scroll",
    () => {
      const y = window.scrollY;
      const delta = y - lastScrollY;

      if (!isOpen()) {
        lastScrollY = y;
        return;
      }

      if (Math.abs(delta) < SCROLL_DELTA) return;

      const atTop = y <= 0;
      const atBottom = y >= maxScrollY() - 1;

      if (atTop && delta < 0) {
        lastScrollY = y;
        return;
      }
      if (atBottom && delta > 0) {
        lastScrollY = y;
        return;
      }

      closeDrawer();
      lastScrollY = y;
    },
    { passive: true }
  );
})();

(() => {
  const input = document.getElementById("catalog-search");
  const grid = document.getElementById("catalog-grid");
  if (!input || !grid) return;

  const cards = () => Array.from(grid.querySelectorAll("[data-search]"));

  const apply = () => {
    const q = input.value.trim().toLowerCase();
    cards().forEach((el) => {
      const hay = (el.getAttribute("data-search") || "").toLowerCase();
      const show = !q || hay.includes(q);
      el.classList.toggle("ws-hidden", !show);
    });
  };

  input.addEventListener("input", apply);
  input.addEventListener("search", apply);
})();

(() => {
  const wrap = document.querySelector(".stats-loop-wrap");
  const track = document.querySelector(".stats-loop-track");
  if (!wrap || !track) return;

  const originals = Array.from(track.children);
  if (!originals.length) return;

  const reduce =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const fill = () => {
    const wasReady = wrap.classList.contains("ws-ticker-ready");

    while (track.children.length > originals.length) {
      track.removeChild(track.lastElementChild);
    }

    const wrapW = wrap.getBoundingClientRect().width;
    let trackW = track.getBoundingClientRect().width;
    const targetW = Math.max(wrapW * 2 + 200, wrapW + 600);
    let guard = 0;
    while (trackW < targetW && guard < 60) {
      originals.forEach((el) => track.appendChild(el.cloneNode(true)));
      trackW = track.getBoundingClientRect().width;
      guard += 1;
    }

    if (reduce) return;

    if (!wasReady) {
      wrap.classList.add("ws-ticker-ready");
      return;
    }

    track.style.animation = "none";
    void track.offsetWidth;
    track.style.animation = "";
  };

  const runFill = () => window.requestAnimationFrame(fill);

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", runFill, { once: true });
  } else {
    runFill();
  }

  window.addEventListener("load", runFill, { once: true });

  let resizeTimer = 0;
  window.addEventListener("resize", () => {
    window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(() => {
      window.requestAnimationFrame(fill);
    }, 160);
  });
})();

(() => {
  const accordion = document.getElementById("faq-accordion");
  if (!accordion) return;
  const details = Array.from(accordion.querySelectorAll("details"));

  const reduce =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const getParts = (el) => {
    const body = el.querySelector(".ws-acc-body");
    const inner = el.querySelector(".ws-acc-body-inner");
    return { body, inner };
  };

  const closeWithAnimation = (el) => {
    const { body, inner } = getParts(el);
    if (!body || !inner) return;
    body.style.maxHeight = `${inner.scrollHeight}px`;
    body.style.opacity = "1";
    void body.offsetHeight;
    body.style.maxHeight = "0px";
    body.style.opacity = "0";
    const onEnd = (ev) => {
      if (ev.propertyName !== "max-height") return;
      el.removeAttribute("open");
      body.removeEventListener("transitionend", onEnd);
    };
    body.addEventListener("transitionend", onEnd);
  };

  const openWithAnimation = (el) => {
    const { body, inner } = getParts(el);
    if (!body || !inner) return;
    el.setAttribute("open", "");
    body.style.maxHeight = "0px";
    body.style.opacity = "0";
    requestAnimationFrame(() => {
      body.style.maxHeight = `${inner.scrollHeight}px`;
      body.style.opacity = "1";
    });
  };

  details.forEach((el) => {
    const summary = el.querySelector("summary");
    const { body, inner } = getParts(el);
    if (!summary || !body || !inner) return;

    if (reduce) {
      body.style.maxHeight = "";
      body.style.opacity = "";
      return;
    }

    body.style.maxHeight = el.open ? `${inner.scrollHeight}px` : "0px";
    body.style.opacity = el.open ? "1" : "0";

    summary.addEventListener("click", (e) => {
      e.preventDefault();
      if (el.open) {
        closeWithAnimation(el);
        return;
      }
      details.forEach((other) => {
        if (other !== el && other.open) closeWithAnimation(other);
      });
      openWithAnimation(el);
    });
  });

  window.addEventListener("resize", () => {
    if (reduce) return;
    details.forEach((el) => {
      if (!el.open) return;
      const { body, inner } = getParts(el);
      if (!body || !inner) return;
      body.style.maxHeight = `${inner.scrollHeight}px`;
    });
  });
})();

(() => {
  document.addEventListener("dragstart", (e) => {
    const t = e.target;
    if (t && (t.tagName === "IMG" || (t.closest && t.closest("img")))) {
      e.preventDefault();
    }
  }, { capture: true });
})();

(() => {
  const reduce =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (reduce) return;

  const root = document.querySelector(".site-main");
  if (!root) return;

  const els = Array.from(root.querySelectorAll(".hero-quad-card, .ws-game-card"));
  if (!els.length) return;

  els.forEach((el, i) => {
    el.classList.add("ws-anim-card");
    el.style.animationDelay = `${Math.min(i * 70, 700)}ms`;
  });

  if (!("IntersectionObserver" in window)) {
    els.forEach((el) => el.classList.add("ws-inview"));
    return;
  }

  const obs = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return;
        e.target.classList.add("ws-inview");
        observer.unobserve(e.target);
      });
    },
    { root: null, threshold: 0.15, rootMargin: "80px 0px" }
  );

  els.forEach((el) => obs.observe(el));
})();

(() => {
  const launcher = document.getElementById("account-launcher");
  const modal = document.getElementById("account-modal");
  if (!launcher || !modal) return;

  const closeEls = Array.from(modal.querySelectorAll("[data-account-close]"));
  const panels = {
    login: document.getElementById("account-panel-login"),
    register: document.getElementById("account-panel-register"),
  };
  const switchers = Array.from(modal.querySelectorAll(".account-switcher"));
  const tabs = Array.from(modal.querySelectorAll(".account-modal-tab"));

  const setPanel = (name) => {
    if (panels.login) panels.login.classList.remove("is-active");
    if (panels.register) panels.register.classList.remove("is-active");
    tabs.forEach((tab) => {
      const target = tab.getAttribute("data-account-target") || "login";
      const active = target === name;
      tab.classList.toggle("is-active", active);
      tab.setAttribute("aria-selected", active ? "true" : "false");
    });
    if (name === "register" && panels.register) {
      panels.register.classList.add("is-active");
      const first = panels.register.querySelector("input");
      if (first instanceof HTMLElement) first.focus();
    }
    if (name === "login" && panels.login) {
      panels.login.classList.add("is-active");
      const first = panels.login.querySelector("input");
      if (first instanceof HTMLElement) first.focus();
    }
  };

  const reduce =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const open = () => {
    modal.removeAttribute("hidden");
    modal.classList.remove("is-closing");
    modal.classList.add("is-open");
    launcher.setAttribute("aria-expanded", "true");
  };

  const close = () => {
    if (reduce) {
      modal.classList.remove("is-open", "is-closing");
      modal.setAttribute("hidden", "");
      launcher.setAttribute("aria-expanded", "false");
      return;
    }
    modal.classList.remove("is-open");
    modal.classList.add("is-closing");
    launcher.setAttribute("aria-expanded", "false");
    window.setTimeout(() => {
      modal.classList.remove("is-closing");
      modal.setAttribute("hidden", "");
    }, 200);
  };

  launcher.addEventListener("click", open);
  closeEls.forEach((el) => el.addEventListener("click", close));
  modal.addEventListener("click", (e) => {
    if (e.target === modal) close();
  });
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && !modal.hasAttribute("hidden")) close();
  });

  switchers.forEach((el) => {
    el.addEventListener("click", () => {
      const target = el.getAttribute("data-account-target") || "login";
      setPanel(target);
    });
  });

  const initialPanel = modal.getAttribute("data-initial-panel") || "login";
  setPanel(initialPanel);

  if (modal.querySelector(".form-alert")) {
    open();
  }
})();

(() => {
  // Top scroll progress bar
  const bar = document.getElementById("scroll-progress");
  if (bar) {
    const update = () => {
      const doc = document.documentElement;
      const top = window.scrollY || doc.scrollTop || 0;
      const max = Math.max(1, doc.scrollHeight - window.innerHeight);
      const pct = Math.max(0, Math.min(100, (top / max) * 100));
      bar.style.width = `${pct.toFixed(2)}%`;
    };
    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
    update();
  }
})();

(() => {
  // Custom cursor: dot + ring, hide native cursor, fade on leave
  const dot = document.querySelector(".custom-cursor-dot");
  const ring = document.querySelector(".custom-cursor-ring");
  if (!(dot instanceof HTMLElement) || !(ring instanceof HTMLElement)) return;
  const canUse =
    window.matchMedia &&
    window.matchMedia("(pointer:fine)").matches &&
    !window.matchMedia("(hover:none)").matches;
  if (!canUse) return;

  document.body.classList.add("cursor-enhanced");

  const getZoom = () => {
    const raw = getComputedStyle(document.documentElement).zoom;
    if (!raw || raw === "normal") return 1;
    const n = Number.parseFloat(raw);
    return Number.isFinite(n) && n > 0 ? n : 1;
  };

  const toLayout = (clientPx) => clientPx / getZoom();

  let x = window.innerWidth / 2;
  let y = window.innerHeight / 2;
  let rx = x;
  let ry = y;
  const follow = 0.18;

  const place = (el, clientX, clientY) => {
    el.style.left = `${toLayout(clientX)}px`;
    el.style.top = `${toLayout(clientY)}px`;
  };

  const tick = () => {
    rx += (x - rx) * follow;
    ry += (y - ry) * follow;
    place(dot, x, y);
    place(ring, rx, ry);
    requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);

  const ISLAND_SELECTOR = "iframe, .console-frame, .map-frame";
  let pointerInPage = false;
  let nearEdge = false;
  let overIsland = false;

  const syncVisible = () => {
    const show = pointerInPage && !nearEdge && !overIsland;
    document.body.classList.toggle("cursor-visible", show);
  };

  const inIsland = (node) =>
    node instanceof Element && !!node.closest(ISLAND_SELECTOR);

  const isLeavingPage = (e) => {
    const rel = e.relatedTarget;
    return rel === null || !(rel instanceof Node) || !document.documentElement.contains(rel);
  };

  const isEnteringPage = (e) => {
    const rel = e.relatedTarget;
    return rel === null || !(rel instanceof Node) || !document.documentElement.contains(rel);
  };

  document.addEventListener(
    "mouseover",
    (e) => {
      if (isEnteringPage(e)) {
        pointerInPage = true;
        syncVisible();
      }
      if (!(e.target instanceof Element) || !inIsland(e.target)) return;
      const from = e.relatedTarget;
      if (inIsland(from)) return;
      overIsland = true;
      syncVisible();
    },
    true
  );

  document.addEventListener(
    "mouseout",
    (e) => {
      if (isLeavingPage(e)) {
        pointerInPage = false;
        syncVisible();
      }
      if (!(e.target instanceof Element) || !inIsland(e.target)) return;
      const to = e.relatedTarget;
      if (inIsland(to)) return;
      overIsland = false;
      syncVisible();
    },
    true
  );

  document.addEventListener(
    "pointermove",
    (e) => {
      if (e.pointerType && e.pointerType !== "mouse") return;
      x = e.clientX;
      y = e.clientY;
      pointerInPage = true;
      const edge = 3;
      nearEdge =
        x <= edge ||
        y <= edge ||
        x >= window.innerWidth - edge ||
        y >= window.innerHeight - edge;
      syncVisible();
    },
    { passive: true }
  );

  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      pointerInPage = false;
      syncVisible();
    }
  });

  window.addEventListener("blur", () => {
    pointerInPage = false;
    syncVisible();
  });

  window.addEventListener("mousedown", () => document.body.classList.add("cursor-down"));
  window.addEventListener("mouseup", () => document.body.classList.remove("cursor-down"));

  const hoverSelector = "a, button, [role='button'], input, select, textarea, label, summary";
  document.addEventListener("mouseover", (e) => {
    if (e.target instanceof Element && e.target.closest(hoverSelector)) {
      document.body.classList.add("cursor-hover");
    }
  }, { passive: true });
  document.addEventListener("mouseout", (e) => {
    if (e.target instanceof Element && e.target.closest(hoverSelector)) {
      document.body.classList.remove("cursor-hover");
    }
  }, { passive: true });
})();

(() => {
  const sliders = Array.from(document.querySelectorAll("[data-cheat-slider]"));
  if (!sliders.length) return;

  sliders.forEach((slider) => {
    const slides = Array.from(slider.querySelectorAll(".cheat-slide"));
    const buttons = Array.from(slider.querySelectorAll("[data-slide-dir]"));
    if (!slides.length || !buttons.length) return;
    let idx = Math.max(0, slides.findIndex((s) => s.classList.contains("is-active")));

    const render = () => {
      slides.forEach((s, i) => s.classList.toggle("is-active", i === idx));
    };

    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const dir = Number(btn.getAttribute("data-slide-dir") || "0");
        idx = (idx + dir + slides.length) % slides.length;
        render();
      });
    });
  });
})();

(() => {
  const blocks = Array.from(document.querySelectorAll("[data-custom-select]"));
  if (!blocks.length) return;

  const closeAll = () => {
    blocks.forEach((block) => {
      const trigger = block.querySelector("[data-custom-trigger]");
      const menu = block.querySelector("[data-custom-menu]");
      if (menu) menu.setAttribute("hidden", "");
      if (trigger) trigger.setAttribute("aria-expanded", "false");
    });
  };

  blocks.forEach((block) => {
    const trigger = block.querySelector("[data-custom-trigger]");
    const menu = block.querySelector("[data-custom-menu]");
    const input = block.querySelector("[data-custom-input]");
    const label = block.querySelector("[data-custom-label]");
    const options = Array.from(block.querySelectorAll("[data-custom-option]"));
    if (!trigger || !menu || !input || !label || !options.length) return;

    trigger.setAttribute("aria-haspopup", "listbox");
    trigger.setAttribute("aria-expanded", "false");

    const ensureDefault = () => {
      const selected = options.find((o) => o.classList.contains("is-selected")) || options[0];
      const value = selected.getAttribute("data-value") || "";
      const text = selected.getAttribute("data-label") || "";
      input.value = value;
      label.textContent = text;
      options.forEach((o) => o.classList.toggle("is-selected", o === selected));
    };
    ensureDefault();

    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      const hidden = menu.hasAttribute("hidden");
      closeAll();
      if (hidden) {
        menu.removeAttribute("hidden");
        trigger.setAttribute("aria-expanded", "true");
      }
    });

    options.forEach((opt) => {
      opt.addEventListener("click", () => {
        const value = opt.getAttribute("data-value") || "";
        const text = opt.getAttribute("data-label") || "";
        input.value = value;
        label.textContent = text;
        options.forEach((o) => o.classList.remove("is-selected"));
        opt.classList.add("is-selected");
        menu.setAttribute("hidden", "");
        trigger.setAttribute("aria-expanded", "false");
      });
    });

    trigger.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        closeAll();
      }
    });
  });

  document.addEventListener("click", (e) => {
    const t = e.target;
    if (t instanceof Element && t.closest("[data-custom-select]")) return;
    closeAll();
  });
})();

(() => {
  const input = document.getElementById("avatar-input");
  const img = document.getElementById("avatar-preview-img");
  const hint = document.getElementById("avatar-preview-hint");
  const preview = document.getElementById("avatar-preview");
  if (!(input instanceof HTMLInputElement) || !(img instanceof HTMLImageElement)) return;

  const maxBytes = 3 * 1024 * 1024;
  let lastUrl = "";

  const msg = {
    default: hint instanceof HTMLElement ? hint.dataset.msgDefault || "" : "",
    updated: hint instanceof HTMLElement ? hint.dataset.msgUpdated || "" : "",
    tooLarge: hint instanceof HTMLElement ? hint.dataset.msgTooLarge || "" : "",
    notImage: hint instanceof HTMLElement ? hint.dataset.msgNotImage || "" : "",
  };

  const setHint = (key) => {
    if (!(hint instanceof HTMLElement)) return;
    hint.textContent = msg[key] || msg.default;
  };

  const clearPreview = () => {
    if (lastUrl) {
      URL.revokeObjectURL(lastUrl);
      lastUrl = "";
    }
    if (preview instanceof HTMLElement) {
      preview.classList.remove("is-local-preview");
    }
  };

  window.addEventListener("beforeunload", clearPreview);

  input.addEventListener("change", () => {
    const file = input.files && input.files[0] ? input.files[0] : null;
    if (!file) return;

    if (file.size > maxBytes) {
      input.value = "";
      clearPreview();
      setHint("tooLarge");
      return;
    }

    if (!file.type.startsWith("image/")) {
      input.value = "";
      clearPreview();
      setHint("notImage");
      return;
    }

    if (lastUrl) URL.revokeObjectURL(lastUrl);
    lastUrl = URL.createObjectURL(file);
    img.src = lastUrl;
    if (preview instanceof HTMLElement) {
      preview.classList.add("is-local-preview");
    }
    setHint("updated");
  });
})();

(() => {
  const btn = document.getElementById("back-to-top");
  if (!btn) return;

  const threshold = 80;

  const sync = () => {
    if (window.scrollY > threshold) btn.removeAttribute("hidden");
    else btn.setAttribute("hidden", "");
  };

  sync();
  window.addEventListener("scroll", sync, { passive: true });

  btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
})();
