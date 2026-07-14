jQuery(function($){ /* Ready */ });

document.addEventListener("DOMContentLoaded", () => {
  const carousel = document.querySelector(".logo-carousel");
  if (carousel) {
    const clone = carousel.cloneNode(true);
    carousel.parentNode.appendChild(clone);
  }
});


document.addEventListener('DOMContentLoaded', function () {
  const toggler = document.querySelector('.navbar-toggler');
  const overlay = document.querySelector('.site-header');

  if (!toggler || !overlay) return;

  toggler.addEventListener('click', function () {
    overlay.classList.toggle('is-open');
    document.body.classList.toggle('no-scroll');
  });

});