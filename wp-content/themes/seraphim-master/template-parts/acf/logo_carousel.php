<?php
if ( ! defined('ABSPATH') ) exit;

/**
 * Robust logo carousel:
 * - Works when 'logos' is a sub field (inside a layout) or a top-level field.
 * - Handles ACF Link field (array) or plain URL string.
 * - Duplicates items to achieve a continuous marquee loop.
 */

$items = [];

/* 1) Preferred: iterate the repeater as a SUB FIELD */
if ( function_exists('have_rows') && have_rows('logos') ) {

  while ( have_rows('logos') ) : the_row();
    $logo = get_sub_field('logo_image');  // Image field
    $link = get_sub_field('logo_link');   // Link field (array) OR URL

    if ( empty($logo) ) continue;

    $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : (is_numeric($logo) ? (int)$logo : null);
    if ( ! $logo_id ) continue;

    // Normalise link into $url/$target
    $url = ''; $target = '_self';
    if ( is_array($link) ) {
      $url    = $link['url']    ?? '';
      $target = $link['target'] ?? '_self';
    } elseif ( is_string($link) ) {
      $url = $link;
    }

    ob_start(); ?>
      <div class="logo-item mx-4 flex-shrink-0">
        <?php if ($url): ?>
          <a href="<?= esc_url($url); ?>" target="<?= esc_attr($target); ?>">
            <?= wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'img-fluid']); ?>
          </a>
        <?php else: ?>
          <?= wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'img-fluid']); ?>
        <?php endif; ?>
      </div>
    <?php
    $items[] = ob_get_clean();
  endwhile;

/* 2) Fallback: 'logos' provided as an ARRAY (top-level or via get_sub_field) */
} else {
  $logos = get_sub_field('logos');              // try sub field first
  if ( empty($logos) ) $logos = get_field('logos'); // then try top-level

  if ( is_array($logos) ) {
    foreach ($logos as $row) {
      $logo = $row['logo_image'] ?? null;
      $link = $row['logo_link'] ?? null;
      if ( empty($logo) ) continue;

      $logo_id = is_array($logo) ? ($logo['ID'] ?? null) : (is_numeric($logo) ? (int)$logo : null);
      if ( ! $logo_id ) continue;

      $url = ''; $target = '_self';
      if ( is_array($link) ) {
        $url    = $link['url']    ?? '';
        $target = $link['target'] ?? '_self';
      } elseif ( is_string($link) ) {
        $url = $link;
      }

      ob_start(); ?>
        <div class="logo-item mx-4 flex-shrink-0">
          <?php if ($url): ?>
            <a href="<?= esc_url($url); ?>" target="<?= esc_attr($target); ?>">
              <?= wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'img-fluid']); ?>
            </a>
          <?php else: ?>
            <?= wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'img-fluid']); ?>
          <?php endif; ?>
        </div>
      <?php
      $items[] = ob_get_clean();
    }
  }
}

/* 3) Output the track twice for a seamless loop */
if ( ! empty($items) ) : ?>
  <div class="logo-carousel-wrapper overflow-hidden py-5">
    <div class="logo-carousel d-inline-flex">
      <?= implode("\n", $items); ?>
      <?= implode("\n", $items); ?>
    </div>
  </div>
<?php endif;
