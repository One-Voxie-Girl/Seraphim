if (window.jQuery) {
$(document).ready(function () {

function openDrawer() {
    $('.drawer').stop(true, true).css('right', '-55%');

    $('.drawerCon').stop(true, true).fadeIn(300, function () {

        $('.drawer').stop(true, true).animate({
            right: 0
        }, 400);

    });
}

function closeDrawer() {
    $('.drawer').stop(true, true).animate({
        right: '-55%'
    }, 400, function () {

        $('.drawerCon').stop(true, true).fadeOut(300);

    });
}

$('.openDrawer').on('click', function (event) {
    event.preventDefault();
    openDrawer();
});

$('.closeDrawer').on('click', function () {
    closeDrawer();
});

$('.drawerCon').on('click', function (event) {
    if (event.target === this) {
        closeDrawer();
    }
});
});
}


// HEADER

function initHeader() {
  var mainNavbar = document.getElementById('mainNavbar');
  var navbarToggler = document.querySelector('[data-bs-target="#mainNavbar"]');

  if (mainNavbar) {
    var closeTimer;

    var lockPageScroll = function () {
      window.clearTimeout(closeTimer);
      mainNavbar.classList.remove('mobile-menu-closing');
      if (navbarToggler) {
        navbarToggler.setAttribute('data-bs-toggle', 'collapse');
      }
      document.documentElement.classList.add('mobile-menu-open');
      document.body.classList.add('mobile-menu-open');
    };

    var startClose = function () {
      mainNavbar.classList.add('mobile-menu-closing');
    };

    var finishClose = function () {
      window.clearTimeout(closeTimer);
      mainNavbar.classList.remove('mobile-menu-closing');
      if (navbarToggler) {
        navbarToggler.setAttribute('data-bs-toggle', 'collapse');
      }
      document.documentElement.classList.remove('mobile-menu-open');
      document.body.classList.remove('mobile-menu-open');
    };

    var closeWithAnimation = function (event, forceClose) {
      if ((!forceClose && !mainNavbar.classList.contains('show')) || mainNavbar.classList.contains('mobile-menu-closing')) {
        return;
      }

      event.preventDefault();

      if (event.stopPropagation) {
        event.stopPropagation();
      }

      if (event.stopImmediatePropagation) {
        event.stopImmediatePropagation();
      }

      startClose();
      mainNavbar.classList.add('show');
      document.documentElement.classList.add('mobile-menu-open');
      document.body.classList.add('mobile-menu-open');
      navbarToggler.removeAttribute('data-bs-toggle');
      navbarToggler.classList.add('collapsed');
      navbarToggler.setAttribute('aria-expanded', 'false');

      closeTimer = window.setTimeout(function () {
        mainNavbar.classList.remove('show');
        mainNavbar.classList.remove('collapsing');
        mainNavbar.style.height = '';
        finishClose();
      }, 220);
    };

    var handleBootstrapClose = function (event) {
      if (mainNavbar.classList.contains('mobile-menu-closing')) {
        return;
      }

      closeWithAnimation(event, true);
    };

    mainNavbar.addEventListener('show.bs.collapse', lockPageScroll);
    mainNavbar.addEventListener('shown.bs.collapse', lockPageScroll);
    mainNavbar.addEventListener('hide.bs.collapse', handleBootstrapClose);
    mainNavbar.addEventListener('hidden.bs.collapse', finishClose);

    if (navbarToggler) {
      navbarToggler.addEventListener('pointerdown', closeWithAnimation, true);
      navbarToggler.addEventListener('mousedown', closeWithAnimation, true);
      navbarToggler.addEventListener('touchstart', closeWithAnimation, true);
      navbarToggler.addEventListener('click', closeWithAnimation, true);
      navbarToggler.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
          closeWithAnimation(event);
        }
      }, true);
    }
  }

  // Enable click-to-open submenus and prevent the parent dropdown from closing
  document.querySelectorAll('.dropdown-submenu > a').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
 
      var submenu = this.nextElementSibling;
      var isOpen = submenu.classList.contains('show');
 
      // Close sibling submenus at the same level
      var parentMenu = this.closest('.dropdown-menu');
      parentMenu.querySelectorAll(':scope > .dropdown-submenu > .dropdown-menu.show').forEach(function (openMenu) {
        if (openMenu !== submenu) openMenu.classList.remove('show');
      });
 
      submenu.classList.toggle('show', !isOpen);
    });
  });
 
  // Reset submenu state when the parent dropdown closes
  document.querySelectorAll('.dropdown').forEach(function (dropdown) {
    dropdown.addEventListener('hidden.bs.dropdown', function () {
      this.querySelectorAll('.dropdown-submenu .dropdown-menu.show').forEach(function (menu) {
        menu.classList.remove('show');
      });
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHeader);
} else {
  initHeader();
}


// VIDEO PLAYER

function initVideoPlayers() {
  document.querySelectorAll('.videoCon').forEach(function (player) {
    var video = player.querySelector('video');
    var playButton = player.querySelector('.videoPlayButton');
    var durationLabel = player.querySelector('.videoDuration');

    if (!video || !playButton) {
      return;
    }

    var formatTime = function (seconds) {
      if (!Number.isFinite(seconds) || seconds <= 0) {
        return video.getAttribute('data-duration') || '0:00';
      }

      var totalSeconds = Math.round(seconds);
      var minutes = Math.floor(totalSeconds / 60);
      var remainingSeconds = totalSeconds % 60;

      return minutes + ':' + String(remainingSeconds).padStart(2, '0');
    };

    var updateDuration = function () {
      if (durationLabel) {
        durationLabel.textContent = formatTime(video.duration);
      }
    };

    var playVideo = function () {
      var playPromise = video.play();

      if (playPromise && typeof playPromise.catch === 'function') {
        playPromise.catch(function () {
          player.classList.remove('is-playing');
        });
      }
    };

    updateDuration();

    video.addEventListener('loadedmetadata', updateDuration);
    video.addEventListener('durationchange', updateDuration);

    video.addEventListener('play', function () {
      player.classList.add('is-playing');
      playButton.setAttribute('aria-label', 'Pause video');
      playButton.setAttribute('tabindex', '-1');
    });

    video.addEventListener('pause', function () {
      player.classList.remove('is-playing');
      playButton.setAttribute('aria-label', 'Play video');
      playButton.removeAttribute('tabindex');
    });

    video.addEventListener('ended', function () {
      player.classList.remove('is-playing');
      playButton.setAttribute('aria-label', 'Play video');
      playButton.removeAttribute('tabindex');
    });

    playButton.addEventListener('click', function () {
      if (video.paused || video.ended) {
        playVideo();
      } else {
        video.pause();
      }
    });

    video.addEventListener('click', function () {
      if (video.paused || video.ended) {
        playVideo();
      } else {
        video.pause();
      }
    });
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initVideoPlayers);
} else {
  initVideoPlayers();
}
