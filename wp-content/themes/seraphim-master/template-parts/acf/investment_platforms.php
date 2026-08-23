<?php
/**
 * ACF Template Part: Investment Platforms
 */


/*TODO
 * FIx logo size/position
 * title should be to right of logo
 * link should be separate below
 *  */
$selected_platforms = get_sub_field('platforms_checklist');
$all_platforms = get_field('investment_platform', 'option');

if ($selected_platforms && $all_platforms): ?>
    <section class="investment-platforms py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <?php foreach ($all_platforms as $platform):
                    $name = is_array($platform) ? ($platform['platform_name'] ?? $platform['name'] ?? '') : $platform;
                    
                    // Only display if this platform is selected in the checklist
                    if (!in_array($name, $selected_platforms)) {
                        continue;
                    }

                    $logo = is_array($platform) ? ($platform['logo'] ?? $platform['platform_logo'] ?? '') : '';
                    $link = is_array($platform) ? ($platform['link'] ?? $platform['website_link'] ?? '') : '';
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="platform-card h-100 p-3 text-center border rounded shadow-sm d-flex flex-column align-items-center justify-content-center">
                            <?php if ($logo): ?>
                                <div class="platform-logo mb-3">

                                    <img src="<?php echo esc_url(is_array($logo) ? $logo['url'] : $logo); ?>"
                                         alt="<?php echo esc_attr($name); ?>"
                                         class="img-fluid"
                                         style="max-height: 60px;">

                                </div>
                            <?php endif; ?>
                            
                            <h4 class="platform-name h6 mb-0">
                                <?php if ($link): ?><a href="<?php echo esc_url($link); ?>" target="_blank" class="text-decoration-none text-dark"><?php endif; ?>
                                    <?php echo esc_html($name); ?>
                                <?php if ($link): ?></a><?php endif; ?>
                            </h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
