// kota.js - replace the old file with this exact content
document.addEventListener("DOMContentLoaded", () => {
  // header & cards animations (kept same behavior)
  const cityHeading = document.querySelector(".city-header h1");
  const cityDesc = document.querySelector(".city-description");
  setTimeout(() => cityHeading?.classList.add("visible"), 200);
  setTimeout(() => cityDesc?.classList.add("visible"), 600);
  const cards = document.querySelectorAll(".card");
  cards.forEach((card, i) => setTimeout(() => card.classList.add("visible"), i * 300 + 900));

  // carousel logic
  const carousel = document.querySelector(".carousel");
  const leftBtn = document.querySelector(".left-btn");
  const rightBtn = document.querySelector(".right-btn");
  if (!carousel) {
    console.warn("carousel element not found — skipping carousel init");
    return;
  }

  // Wait for all images inside carousel to finish loading (or error)
  const imgs = Array.from(carousel.querySelectorAll("img"));
  const waitImages = imgs.map(img => {
    if (img.complete) return Promise.resolve();
    return new Promise(resolve => {
      img.addEventListener("load", resolve, { once: true });
      img.addEventListener("error", resolve, { once: true });
    });
  });

  Promise.all(waitImages).then(() => {
    // if content fits, no need to autoscroll
    if (carousel.scrollWidth <= carousel.clientWidth + 5) {
      console.log("carousel: content fits inside view — autoscroll disabled");
      return;
    }

    // autoscroll using RAF
    let rafId = null;
    let lastTs = null;
    let isPaused = false;
    const pxPerSecond = 45; // tweak speed here

    function step(ts) {
      if (!lastTs) lastTs = ts;
      const dt = ts - lastTs;
      lastTs = ts;

      if (!isPaused) {
        const move = (pxPerSecond * dt) / 1000;
        carousel.scrollLeft += move;

        // loop to start when reach end
        if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 1) {
          // snap back to start (simple loop)
          carousel.scrollLeft = 0;
        }
      }

      rafId = requestAnimationFrame(step);
    }
    rafId = requestAnimationFrame(step);

    // pause/resume handlers
    const pause = () => { isPaused = true; };
    const resume = () => { isPaused = false; };

    carousel.addEventListener("mouseenter", pause);
    carousel.addEventListener("mouseleave", resume);
    carousel.addEventListener("pointerdown", pause);
    window.addEventListener("pointerup", resume);
    carousel.addEventListener("touchstart", pause, { passive: true });
    carousel.addEventListener("touchend", resume);

    // manual button scrolling: scroll by ~60% of visible width
    function scrollByAmount(amount) {
      isPaused = true;
      carousel.scrollBy({ left: amount, behavior: "smooth" });
      setTimeout(() => { isPaused = false; }, 700); // resume after smooth scroll
    }

    leftBtn?.addEventListener("click", () => scrollByAmount(-Math.round(carousel.clientWidth * 0.6)));
    rightBtn?.addEventListener("click", () => scrollByAmount(Math.round(carousel.clientWidth * 0.6)));

    // keyboard accessibility for buttons
    [leftBtn, rightBtn].forEach(btn => {
      if (!btn) return;
      btn.addEventListener("keydown", e => {
        if (e.key === "Enter" || e.key === " ") { e.preventDefault(); btn.click(); }
      });
    });

    // handle resize: stop autoscroll if content fits after resize
    let resizeTimeout = null;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        if (carousel.scrollWidth <= carousel.clientWidth + 5) {
          console.log("carousel: content fits after resize — stopping autoscroll");
          if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
        } else if (!rafId) {
          lastTs = null;
          rafId = requestAnimationFrame(step);
        }
      }, 150);
    });

    // cleanup
    window.addEventListener("beforeunload", () => { if (rafId) cancelAnimationFrame(rafId); });
  }).catch(err => {
    console.error("Error initializing carousel images:", err);
  });
});
