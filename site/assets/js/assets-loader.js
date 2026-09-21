(() => {
  if (!document.body.classList.contains("assets-heavy-page")) return;

  const overlay = document.getElementById("assets-loading-overlay");
  if (!(overlay instanceof HTMLElement)) return;

  const reduce =
    window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const startDots = () => {
    if (reduce) {
      overlay.classList.add("is-reduced-motion");
      return;
    }
    overlay.classList.add("is-animating");
  };

  const stopDots = () => {
    overlay.classList.remove("is-animating");
  };

  const decodeImage = (src) =>
    new Promise((resolve) => {
      if (!src) {
        resolve();
        return;
      }
      const img = new Image();
      img.decoding = "async";
      const done = () => resolve();
      img.onload = () => {
        if (img.decode) {
          img.decode().then(done).catch(done);
        } else {
          done();
        }
      };
      img.onerror = done;
      img.src = src;
    });

  const collectPriorityImages = () => {
    const main = document.querySelector(".site-main");
    if (!main) return [];
    const imgs = Array.from(main.querySelectorAll("img[src]"));
    const priority = imgs.filter((img) => {
      if (img.closest(".assets-loading-overlay")) return false;
      if (img.hasAttribute("data-loader-skip")) return false;
      if (img.loading === "lazy") return false;
      if (img.closest("[data-cheat-slider]")) return true;
      const card = img.closest(".ws-game-card, .hero-quad-card, .gallery-card, .chronicle-card");
      if (card) {
        const rect = card.getBoundingClientRect();
        return rect.top < window.innerHeight * 1.15;
      }
      return img.getBoundingClientRect().top < window.innerHeight;
    });
    const seen = new Set();
    return priority
      .map((img) => img.currentSrc || img.src)
      .filter((src) => {
        if (!src || seen.has(src)) return false;
        seen.add(src);
        return true;
      });
  };

  const showOverlay = () => {
    overlay.removeAttribute("hidden");
    overlay.classList.remove("is-done");
    document.documentElement.classList.add("is-scroll-locked");
    document.body.classList.add("is-assets-loading");
    startDots();
  };

  const hideOverlay = () => {
    stopDots();
    overlay.classList.add("is-done");
    window.setTimeout(() => {
      overlay.setAttribute("hidden", "");
      overlay.classList.remove("is-done");
      document.documentElement.classList.remove("is-scroll-locked");
      document.body.classList.remove("is-assets-loading");
    }, 280);
  };

  const run = async () => {
    showOverlay();
    const started = Date.now();
    const urls = collectPriorityImages();
    const timeout = new Promise((resolve) => window.setTimeout(resolve, 12000));
    const loads = Promise.all(urls.map((u) => decodeImage(u)));
    await Promise.race([Promise.all([loads, timeout]), timeout]);
    const elapsed = Date.now() - started;
    if (elapsed < 400) {
      await new Promise((r) => window.setTimeout(r, 400 - elapsed));
    }
    hideOverlay();
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => run(), { once: true });
  } else {
    run();
  }
})();
