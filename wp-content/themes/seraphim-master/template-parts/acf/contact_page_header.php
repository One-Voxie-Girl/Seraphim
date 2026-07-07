<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	

$h1_title = get_sub_field('h1_title');
$decorative_title = get_sub_field('decorative_title');
$header_body_copy = get_sub_field('header_body_copy');
$background_colour = get_sub_field('background_colour');

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

<div class="pageHeader contactPageHeader contanct">

  <div class="container headerSectionCon">
      <div class="row">
            <div class="col-12 col-lg-6">

                <h1 class="h2"><?= $h1_title ?></h1>
                
                <h2 class="h1 contactPage">
                  <?= $decorative_title; ?>
                </h2>
                <div class="headerBody">
                  <?= $header_body_copy; ?>
                </div>
                <div class="spacer" style="height:60px;"></div>
            </div>

            <div class="col-12 col-lg-6">
              <div class="contactPageForm">
                  <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
                  <script>
                  hbspt.forms.create({
                    region: "na1",
                    formId: "2d098c49-042f-40f2-9f17-9e1bc246836c",
                    portalId: "9285362"
                  });
                  </script>
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

<div class="container"><div class="row"><div class="col-12 col-md-12">