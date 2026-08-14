<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$word        = get_sub_field('moving_text'); // e.g. "Our Services"

// Make sure there's a word
if ( $word ) {
    // Repeat the word with a separator until it fills the curve
    // Adjust the multiplier (e.g. 30) to control how long the line is
    $repeated = str_repeat($word . '. ', 30);
    $text_colour = get_sub_field('text_colour');

}
?>


<div class="curvedText top">
  <svg width="120%" viewBox="0 -15 650.1 61.7">
    <path id="curve2" fill="transparent"
          d="M0,0.1c0,0,62.7,34.9,163.7,32.3S308-3.9,405.6,0.4c101.8,4.5,126.3,54.6,244.6,38"></path>
    <text width="100%">
      <textPath alignment-baseline="top" xlink:href="#curve2" startOffset="-2.3140495867767186"
                class="text-path">
        <?= esc_html($repeated); ?>
      </textPath>
    </text>
  </svg>
</div>

<? /*
<div class="curvedText bottom">
  <svg width="100%" viewBox="0 -15 650.1 61.7">
    <path id="curve2" fill="transparent"
          d="M0,0.1c0,0,62.7,34.9,163.7,32.3S308-3.9,405.6,0.4c101.8,4.5,126.3,54.6,244.6,38"></path>
    <text width="100%">
      <textPath alignment-baseline="top" xlink:href="#curve2" startOffset="-0.3140495867767186"
                class="text-path">
        <?= esc_html($repeated); ?>
      </textPath>
    </text>
  </svg>
</div>
*/ ?>


<script>
jQuery(document).ready(function($) {
  $(document).on("scroll", function() {
    const h = document.documentElement,
          b = document.body,
          st = 'scrollTop',
          sh = 'scrollHeight';

    let percent = (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 80;
    $(".text-path").attr("startOffset", (1000 - (percent * 40)));
  });
});
</script>

