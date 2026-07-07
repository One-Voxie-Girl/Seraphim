<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
$title = get_sub_field('title');
$header_body_copy = get_sub_field('header_body_copy');
$background_colour = get_sub_field('background_colour');
$show_project_overview = get_sub_field('show_project_overview');



if ($background_colour == "multi") {
  $background_colour == "#FFF0E6";
 
} else { ?>

 <style>
  .heart {--accent: #000 !important; }
  .mucSectorsSection .text-col .sector-list li.active, .quoteCon .marks {color: <?php echo $background_colour; ?>; }
  </style>

<?php 
}

?>



</div></div></div>

<div class="pageHeader">

  <div class="container headerSectionCon <?php if ($background_colour != "multi") { echo "whiteCTA"; }?>">
  <div class="row">
      <div class="col-12">

        <div class="row">
          <div class="col-12 col-lg-8">
            <h1 class="h2"><?php the_title(); ?></h1>
            <h2 class="h1"><?= $title; ?></h2>
         </div>
        </div>
       
        <div class="row">
          <div class="col-12 col-md-5 offset-md-6">
            <?= $header_body_copy; ?>
             <?php
              include 'call_to_action.php';
            ?>

            <?php if ($show_project_overview == "show") { ?>
            
            <div class="call_to_action LeftAlign"" id="viewOverview">
                <div class="cta-line">
                  <span>Project overview</span>
                </div>
            </div>

            <?php } ?>


        </div>
        </div>

        

      </div>

  </div>
</div>

<div class="headerHeartsCon">
  <div class="hearts-pattern" style="background: <?php echo $background_colour; ?>;">
    <div class="flashlight-overlay"></div>

  <div class="hearts-grid" id="heartsGrid">
    <template id="heartTemplate">
      <div class="heart">
        <svg class="heart__shape" viewBox="0 0 348 318">

          <path d="M346.99469,101.62817c0,26.01525-9.92947,52.018-29.776,71.86459l-143.72904,143.72911L29.76047,173.49276c-39.68062-39.69312-39.68062-104.03607,0-143.72919C49.60699,9.91705,75.62216,0,101.62491,0s52.01807,9.91705,71.86475,29.76357C193.33618,9.91705,219.35135,0,245.3541,0s52.01807,9.91705,71.86459,29.76357c19.84652,19.8466,29.776,45.84935,29.776,71.86459Z"/>
        </svg>

        <div class="eye eye--left" style="background: <?php echo $background_colour; ?>;"><div class="pupil"></div></div>
        <div class="eye eye--right" style="background: <?php echo $background_colour; ?>;"><div class="pupil"></div></div>
      </div>
    </template>
  </div>

  </div>
</div>
<script src="/wp-content/themes/make-us-care-3-0/assets/js/header-hearts.js"></script>





</div>

<?php if ($show_project_overview == "show") { 
  $project_overview = get_sub_field('project_overview');
  ?>

<div class="caseStudyDetailOverlay" id="caseStudyDetailOverlay" aria-hidden="true">
  <div class="container detailCon">
    <div class="row">
      <div class="col-12">
        
        <div class="titleAndClose">
          <h2>Project overview</h2>
          <button class="closeOverlayCon close" type="button" aria-label="Close overlay">
            <div class="closeOverlay"></div>
            <div class="closeOverlay second"></div>
        </button>
        </div>

        <div class="overviewBody" id="overviewBody">
          <?php echo $project_overview; ?>
        </div>
        
      </div>
    </div>
  </div>
</div>



<script>
  const openBtn = document.getElementById('viewOverview');
  const overlay = document.getElementById('caseStudyDetailOverlay');
  const closeBtn = overlay.querySelector('.close');
  const paragraphs = overlay.querySelectorAll('.detailCon p');

  const OVERLAY_FADE_DURATION = 450; // must match CSS
  const TEXT_IN_DELAY = 140;
  const TEXT_OUT_DELAY = 60;

  function lockScroll() {
    const scrollbarWidth =
      window.innerWidth - document.documentElement.clientWidth;
    document.body.style.overflow = 'hidden';
    document.body.style.paddingRight = `${scrollbarWidth}px`;
  }

  function unlockScroll() {
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
  }

  function animateParagraphsIn() {
    paragraphs.forEach((p, i) => {
      p.classList.remove('is-in');
      p.style.transitionDelay = `${i * TEXT_IN_DELAY}ms`;
      requestAnimationFrame(() => p.classList.add('is-in'));
    });
  }

  function animateParagraphsOut() {
    paragraphs.forEach((p, i) => {
      p.style.transitionDelay = `${
        (paragraphs.length - i) * TEXT_OUT_DELAY
      }ms`;
      p.classList.remove('is-in');
    });
  }

  function openOverlay() {
    overlay.classList.add('is-open');
    lockScroll();

    // let background fade begin, then animate text
    setTimeout(animateParagraphsIn, 120);
  }

  function closeOverlay() {
    // fade text out first
    animateParagraphsOut();

    // then fade overlay out
    setTimeout(() => {
      overlay.classList.remove('is-open');
      unlockScroll();
    }, 120);

    // cleanup delays after fade completes
    setTimeout(() => {
      paragraphs.forEach(p => (p.style.transitionDelay = '0ms'));
    }, OVERLAY_FADE_DURATION);
  }

  if (openBtn) {
    openBtn.addEventListener('click', e => {
      e.preventDefault();
      openOverlay();
    });
  }

  closeBtn.addEventListener('click', closeOverlay);

  overlay.addEventListener('click', e => {
    if (e.target === overlay) closeOverlay();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
      closeOverlay();
    }
  });
</script>



<?php } ?>

<div class="container"><div class="row"><div class="col-12 col-md-12">