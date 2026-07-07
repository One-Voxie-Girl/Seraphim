<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	

$h1_title = get_sub_field('h1_title');
$decorative_title = get_sub_field('decorative_title');
$header_body_copy = get_sub_field('header_body_copy');

?>





</div></div></div>

<div class="pageHeader homePageHeader">

  <div class="container headerSectionCon">
  <div class="row">
      <div class="col-12">

          <h1 class="h2"><?= $h1_title ?></h1>
          
          <h2 class="h1">
            <?= $decorative_title; ?>
          </h2>

          <div class="row">
            <div class="col-12 col-md-6 offset-md-6">
              
              <p class="large"><?= $header_body_copy; ?></p>

              <?php
                get_template_part('template-parts/acf/call_to_action', 'none');
              ?>
          </div>
          </div>

        </div>
  </div>
</div>

<div class="headerHeartsCon">
  <div class="hearts-pattern" style="background: #FFF0E6;">
    <div class="flashlight-overlay"></div>

  <div class="hearts-grid" id="heartsGrid">
    <template id="heartTemplate">
      <div class="heart">
        <svg class="heart__shape" viewBox="0 0 348 318">

          <path d="M346.99469,101.62817c0,26.01525-9.92947,52.018-29.776,71.86459l-143.72904,143.72911L29.76047,173.49276c-39.68062-39.69312-39.68062-104.03607,0-143.72919C49.60699,9.91705,75.62216,0,101.62491,0s52.01807,9.91705,71.86475,29.76357C193.33618,9.91705,219.35135,0,245.3541,0s52.01807,9.91705,71.86459,29.76357c19.84652,19.8466,29.776,45.84935,29.776,71.86459Z"/>
        </svg>

        <div class="eye eye--left" style="background: #FFF0E6;"><div class="pupil"></div></div>
        <div class="eye eye--right" style="background: #FFF0E6;"><div class="pupil"></div></div>
      </div>
    </template>
  </div>

  </div>
</div>
<script src="/wp-content/themes/make-us-care-3-0/assets/js/header-hearts.js"></script>




</div>

<div class="container"><div class="row"><div class="col-12 col-md-12">