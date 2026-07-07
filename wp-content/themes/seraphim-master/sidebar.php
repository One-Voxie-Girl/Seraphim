<?php if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
  <aside id="primary-sidebar" class="widget-area">
    <?php dynamic_sidebar( 'primary-sidebar' ); ?>
  </aside>
<?php else: ?>
  <div class="p-3 bg-white rounded-3">
    <h3 class="h5">Sidebar</h3>
    <p>Add widgets in WP Admin → Appearance → Widgets.</p>
  </div>
<?php endif; ?>
