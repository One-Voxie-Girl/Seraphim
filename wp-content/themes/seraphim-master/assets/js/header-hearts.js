const hearts = Array.from(document.querySelectorAll('.heart'));
const pattern = document.querySelector('.hearts-pattern');

(function () {

  const HEARTS_PER_ROW = 10;
  const ROW_COUNT = 10;

  const grid = document.getElementById("heartsGrid");
  const template = document.getElementById("heartTemplate");

  for (let rowIndex = 0; rowIndex < ROW_COUNT; rowIndex++) {
    const row = document.createElement("div");
    row.classList.add("hearts-row");

    if (rowIndex % 2 === 1) row.classList.add("is-odd");

    for (let i = 0; i < HEARTS_PER_ROW; i++) {
      const clone = template.content.cloneNode(true);
      row.appendChild(clone);
    }

    grid.appendChild(row);
  }

  const hearts = Array.from(document.querySelectorAll('.heart'));
  if (!hearts.length) return;

  // --------------------------------------------------
  // COLOURS
  // --------------------------------------------------
  const COLORS = [
    "#78D9BF",
    "#EA4243",
    "#FFE88A",
    "#89CDFF",
    "#9C84FF",
    "#FF89CD"
  ];

  hearts.forEach((heart) => {
    const c = COLORS[Math.floor(Math.random() * COLORS.length)];
    heart.style.setProperty("--accent", c);
    heart.style.setProperty("--hover", "0");
    heart.style.setProperty("--heart-scale", "1");
  });

  const maxOffsetFactor = 0.5;

  // --------------------------------------------------
  // IDLE STATE (NEW)
  // --------------------------------------------------
  let isActive = false;

  if (pattern) pattern.classList.add("is-idle");

  function setInitialGaze() {
    const cx = window.innerWidth / 2;
    const cy = window.innerHeight / 2;

    hearts.forEach((heart) => {
      const eyes = heart.querySelectorAll('.eye');

      eyes.forEach((eye) => {
        const pupil = eye.querySelector('.pupil');
        if (!pupil) return;

        const rect = eye.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const dx = cx - centerX;
        const dy = cy - centerY;
        const angle = Math.atan2(dy, dx);

        const maxOffset = (rect.width / 2) * maxOffsetFactor;

        const row = eye.closest('.hearts-row');
        const isOdd = row && row.classList.contains('is-odd');

        const directionX = isOdd ? -1 : 1;
        const directionY = isOdd ? -1 : 1;

        const offsetX = Math.cos(angle) * maxOffset * directionX;
        const offsetY = Math.sin(angle) * maxOffset * directionY;

        pupil.style.transform =
          `translate(calc(-50% + ${offsetX}px), calc(-50% + ${offsetY}px))`;
      });
    });
  }

  function activate() {
    if (isActive) return;
    isActive = true;
    if (pattern) pattern.classList.remove("is-idle");
  }

  // --------------------------------------------------
  // MOUSE MOVE (EXISTING, EXTENDED)
  // --------------------------------------------------
  function handleMouseMove(e) {
    activate();

    const mouseX = e.clientX;
    const mouseY = e.clientY;

    // ---- FLASHLIGHT MASK FOR HEARTS ---->
    if (grid) {
      const rect = grid.getBoundingClientRect();
      const x = mouseX - rect.left;
      const y = mouseY - rect.top;

      const inside =
        x >= 0 && x <= rect.width &&
        y >= 0 && y <= rect.height;

      if (inside) {
        grid.style.setProperty('--mx', `${x}px`);
        grid.style.setProperty('--my', `${y}px`);
      } else {
        grid.style.setProperty('--mx', `-999px`);
        grid.style.setProperty('--my', `-999px`);
      }
    }
    // ---- END FLASHLIGHT MASK ---->

    hearts.forEach((heart) => {

      const heartRect = heart.getBoundingClientRect();
      const heartCenterX = heartRect.left + heartRect.width / 2;
      const heartCenterY = heartRect.top + heartRect.height / 2;

      const dxHeart = mouseX - heartCenterX;
      const dyHeart = mouseY - heartCenterY;
      const distHeart = Math.hypot(dxHeart, dyHeart);

      const maxDist = 300;
      const clamped = Math.min(distHeart / maxDist, 1);
      const hover = Math.pow(1 - clamped, 4);

      heart.style.setProperty("--hover", hover.toFixed(3));
      heart.style.setProperty("--heart-scale", "1");

      const eyes = heart.querySelectorAll('.eye');

      eyes.forEach((eye) => {
        const pupil = eye.querySelector('.pupil');
        if (!pupil) return;

        const rect = eye.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const dx = mouseX - centerX;
        const dy = mouseY - centerY;

        const angle = Math.atan2(dy, dx);
        const maxOffset = (rect.width / 2) * maxOffsetFactor;

        const row = eye.closest('.hearts-row');
        const isOdd = row && row.classList.contains('is-odd');

        const directionX = isOdd ? -1 : 1;
        const directionY = isOdd ? -1 : 1;

        const offsetX = Math.cos(angle) * maxOffset * directionX;
        const offsetY = Math.sin(angle) * maxOffset * directionY;

        pupil.style.transform =
          `translate(calc(-50% + ${offsetX}px), calc(-50% + ${offsetY}px))`;
      });
    });
  }

 window.addEventListener('mousemove', handleMouseMove);

window.addEventListener('touchstart', (e) => {
  if (e.touches && e.touches.length) {
    const t = e.touches[0];
    handleMouseMove({
      clientX: t.clientX,
      clientY: t.clientY
    });
  }
}, { passive: true });

// ---- FORCE INITIAL PAINT (MOBILE SAFARI FIX) ----
window.addEventListener("load", () => {
  if (grid) {
    grid.style.transform = "translateZ(0)";
    requestAnimationFrame(() => {
      grid.style.transform = "";
    });
  }
});

})();




// --------------------------------------------------
// SCROLL (UNCHANGED)
// --------------------------------------------------
const headerHearts = document.querySelector('.headerHeartsCon');

function updateOpacity() {
  const scrollY = window.scrollY;
  const maxScroll = window.innerHeight;

  const progress = Math.min(scrollY / maxScroll, 1);
  const opacity = 1 - progress;

  headerHearts.style.opacity = opacity;

  if (opacity <= 0) {
    headerHearts.style.visibility = 'hidden';
    headerHearts.style.pointerEvents = 'none';
  } else {
    headerHearts.style.visibility = 'visible';
    headerHearts.style.pointerEvents = '';
  }
}

window.addEventListener('scroll', updateOpacity);
