(() => {
  const DEVTOOLS_THRESHOLD = 160;
  const isRu = (document.documentElement.lang || "ru").toLowerCase().startsWith("ru");
  const overlayTitle = isRu ? "Инструменты разработчика отключены" : "Developer tools are disabled";
  const overlayLead = isRu
    ? "Закройте панель разработчика, чтобы продолжить работу с сайтом."
    : "Close the developer tools panel to continue using the site.";

  let devtoolsOpen = false;
  let debuggerTimer = null;
  let overlay = null;

  const blockContextMenu = (e) => {
    e.preventDefault();
    e.stopPropagation();
    return false;
  };

  const isBlockedKey = (e) => {
    const key = e.key;
    if (key === "F12") return true;
    if (e.ctrlKey && e.shiftKey && /^[IJC]$/i.test(key)) return true;
    if (e.ctrlKey && (key === "u" || key === "U")) return true;
    if (e.metaKey && e.altKey && /^[IJC]$/i.test(key)) return true;
    if (e.metaKey && e.altKey && (key === "u" || key === "U")) return true;
    return false;
  };

  const onKeyDown = (e) => {
    if (!isBlockedKey(e)) return;
    e.preventDefault();
    e.stopPropagation();
    return false;
  };

  const guardDocument = (doc) => {
    if (!doc || doc.__basaltDevtoolsGuard) return;
    doc.__basaltDevtoolsGuard = true;
    doc.addEventListener("contextmenu", blockContextMenu, true);
    doc.addEventListener("keydown", onKeyDown, true);
    const win = doc.defaultView;
    if (win && win !== window) {
      win.addEventListener("contextmenu", blockContextMenu, true);
      win.addEventListener("keydown", onKeyDown, true);
    }
  };

  const ensureOverlayStyles = () => {
    if (document.getElementById("devtools-guard-styles")) return;
    const style = document.createElement("style");
    style.id = "devtools-guard-styles";
    style.textContent = `
      #devtools-blocked-overlay {
        position: fixed;
        inset: 0;
        z-index: 2147483646;
        display: grid;
        place-items: center;
        padding: 24px;
        background: rgba(4, 4, 4, 0.88);
        backdrop-filter: blur(8px);
      }
      .devtools-blocked-panel {
        max-width: 420px;
        padding: 1.5rem 1.25rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: rgba(16, 16, 16, 0.95);
        color: #f2f2f2;
        text-align: center;
        font: 15px/1.5 system-ui, sans-serif;
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.55);
      }
      .devtools-blocked-panel h2 {
        margin: 0 0 0.75rem;
        font-size: 1.15rem;
      }
      .devtools-blocked-panel p {
        margin: 0;
        color: #a8a8a8;
      }
    `;
    document.head.appendChild(style);
  };

  const showOverlay = () => {
    ensureOverlayStyles();
    if (overlay) return;
    overlay = document.createElement("div");
    overlay.id = "devtools-blocked-overlay";
    overlay.setAttribute("role", "alertdialog");
    overlay.setAttribute("aria-modal", "true");
    overlay.innerHTML = `<div class="devtools-blocked-panel"><h2>${overlayTitle}</h2><p>${overlayLead}</p></div>`;
    document.documentElement.appendChild(overlay);
  };

  const hideOverlay = () => {
    if (!overlay) return;
    overlay.remove();
    overlay = null;
  };

  const startDebuggerLoop = () => {
    if (debuggerTimer !== null) return;
    debuggerTimer = window.setInterval(() => {
      // eslint-disable-next-line no-debugger
      debugger;
    }, 120);
  };

  const stopDebuggerLoop = () => {
    if (debuggerTimer === null) return;
    window.clearInterval(debuggerTimer);
    debuggerTimer = null;
  };

  const checkDevtools = () => {
    const widthGap = window.outerWidth - window.innerWidth;
    const heightGap = window.outerHeight - window.innerHeight;
    const open = widthGap > DEVTOOLS_THRESHOLD || heightGap > DEVTOOLS_THRESHOLD;
    if (open === devtoolsOpen) return;
    devtoolsOpen = open;
    if (devtoolsOpen) {
      showOverlay();
      startDebuggerLoop();
    } else {
      hideOverlay();
      stopDebuggerLoop();
    }
  };

  guardDocument(document);
  window.addEventListener("contextmenu", blockContextMenu, true);
  window.addEventListener("keydown", onKeyDown, true);

  const hookIframe = (frame) => {
    if (!(frame instanceof HTMLIFrameElement) || frame.dataset.basaltGuardHooked === "1") return;
    frame.dataset.basaltGuardHooked = "1";
    const attach = () => {
      try {
        const doc = frame.contentDocument;
        if (doc) guardDocument(doc);
      } catch (_) {
        /* cross-origin */
      }
    };
    frame.addEventListener("load", attach);
    attach();
  };

  const hookIframes = () => {
    document.querySelectorAll("iframe").forEach(hookIframe);
  };

  hookIframes();
  new MutationObserver(hookIframes).observe(document.documentElement, {
    childList: true,
    subtree: true,
  });

  window.setInterval(checkDevtools, 500);
  checkDevtools();
})();
