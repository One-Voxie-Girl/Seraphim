<?php
/**
 * Holdings List ACF Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$args = array(
    'post_type'      => 'portfolio',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
);

$query = new WP_Query( $args );

$all_sectors    = get_terms( array( 'taxonomy' => 'company-sector', 'hide_empty' => true ) );
$all_categories = get_terms( array( 'taxonomy' => 'company-category', 'hide_empty' => true ) );
$all_countries  = get_terms( array( 'taxonomy' => 'country', 'hide_empty' => true ) );

if ( $query->have_posts() ) :
    $total_companies = $query->found_posts;
    $countries_count = count( $all_countries );
    // Note: portfolio_value and ipos_count would ideally come from ACF fields on a settings page or be calculated.
    // For now, using placeholders or simple counts if possible.
    $portfolio_value = get_field('portfolio_value', 'option') ?: '332M';
    $ipos_count = get_field('ipos_count', 'option') ?: '3';
    ?>
    <div class="holdings-list-container container" id="holdings-container">
        
        <div class="portfolioFilterBar">
            <div class="portfolioFilters">
                <div class="portfolioFilterDropdown">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Sector <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-filter="" data-taxonomy="sector"><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_sectors as $term ) : ?>
                            <button type="button" data-filter="<?php echo esc_attr( $term->slug ); ?>" data-taxonomy="sector"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="portfolioFilterDropdown">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Category <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-filter="" data-taxonomy="category"><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_categories as $term ) : ?>
                            <button type="button" data-filter="<?php echo esc_attr( $term->slug ); ?>" data-taxonomy="category"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="portfolioFilterDropdown">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Location <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-filter="" data-taxonomy="country"><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_countries as $term ) : ?>
                            <button type="button" data-filter="<?php echo esc_attr( $term->slug ); ?>" data-taxonomy="country"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="portfolioViewToggle" aria-label="View options">
                <button type="button" class="active" aria-label="Grid view" aria-pressed="true" data-portfolio-view="grid"><i class="ci-More_Grid_Big"></i></button>
                <button type="button" aria-label="List view" aria-pressed="false" data-portfolio-view="list"><i class="ci-List_Unordered"></i></button>
            </div>
        </div>

        <div class="portfolioGrid">
            <div class="portfolioListHeader" aria-hidden="true">
                <span>Company</span>
                <span>Sector</span>
                <span>Category</span>
                <span>Location</span>
                <span>% of assets</span>
            </div>

            <?php while ( $query->have_posts() ) : $query->the_post();
                $tagline = get_field( 'tagline' );
                $logo    = get_field( 'logo' );
                $assets_pct = get_field( 'assets_percentage' ) ?: '0';
                $is_ipo = get_field( 'ipo_status' ); // true false radio button or 'yes'
                $is_top_holding = get_field( 'top_holding' ); // true false radio button


                $sectors    = get_the_terms( get_the_ID(), 'company-sector' );
                $categories = get_the_terms( get_the_ID(), 'company-category' );
                $countries  = get_the_terms( get_the_ID(), 'country' );

                $sector_name   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->name : '';
                $sector_slug   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->slug : '';
                $category_name = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->name : '';
                $category_slug = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->slug : '';
                
                $country_name = '';
                $country_slug = '';
                if ( ! is_wp_error( $countries ) && ! empty( $countries ) ) {
                    $country_term = $countries[0];
                    $country_name = $country_term->name;
                    $country_slug = $country_term->slug;

                    if ( $country_term->parent ) {
                        $parent_term = get_term( $country_term->parent, 'country' );
                        if ( ! is_wp_error( $parent_term ) && ! empty( $parent_term ) ) {
                            $country_name = $parent_term->name . ' - ' . $country_name;
                            // Add parent slug for filtering if needed, but let's see if we should just use names or slugs consistently
                        }
                    }
                }

                $background_image = get_field( 'background_image' );
                $card_img_url     = ( ! empty( $background_image ) && is_array( $background_image ) ) ? $background_image['url'] : get_the_post_thumbnail_url( get_the_ID(), 'large' );
                ?>
                <div class="notch-card portfolioHoldingCard holding-item" 
                     data-sector="<?php echo esc_attr( $sector_slug ); ?>" 
                     data-category="<?php echo esc_attr( $category_slug ); ?>" 
                     data-country="<?php echo esc_attr( $country_slug ); ?>"
                     data-assets="<?php echo esc_attr( $assets_pct ); ?>%">
                    
                    <span class="notch-card__tab" aria-hidden="true"></span>
                    
                    <?php if ( $is_ipo === true || $is_ipo === 'yes' || $is_ipo === 'true' ) : ?>
                        <div class="badge">
                            <span class="text">IPO</span>
                        </div>
                    <?php endif; ?>

                    <?php if ( $is_top_holding == 'true' ) : ?>
                        <div class="notch-card-label">Top holding</div>
                    <?php endif; ?>

                    <div class="imageCon" style="background-image: url('<?php echo esc_url( $card_img_url ); ?>');">
                        <?php if ( $logo ) : ?>
                            <div class="logoCon">
                                <img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>" style="width: 100%; height: auto;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="contentCon">
                        <h4 class="title small"><?php the_title(); ?></h4>
                        <?php if ( $tagline ) : ?>
                            <span class="white65"><?php echo esc_html( wp_trim_words( $tagline, 20 ) ); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="detailsCon">
                        <div class="captionCon">
                            <?php if ( $sector_name ) : ?>
                                <span class="caption"><?php echo esc_html( $sector_name ); ?></span>
                            <?php endif; ?>
                            <?php if ( $category_name ) : ?>
                                <span class="caption"><?php echo esc_html( $category_name ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttonCon">
                            <a href="<?php the_permalink(); ?>" class="tertiary small">Learn more</a>
                        </div>
                    </div>

                    <div class="portfolioListValues" aria-hidden="true">
                        <span><?php echo esc_html( $sector_name ); ?></span>
                        <span><?php echo esc_html( $category_name ); ?></span>
                        <span><?php echo esc_html( $country_name ); ?></span>
                        <span><?php echo esc_html( $assets_pct ); ?>%</span>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const holdingsContainer = document.getElementById('holdings-container');
        if (holdingsContainer) {
            const portfolioGrid = holdingsContainer.querySelector('.portfolioGrid');
            const viewToggleBtns = holdingsContainer.querySelectorAll('.portfolioViewToggle button');
            const filterToggles = holdingsContainer.querySelectorAll('.portfolioFilterToggle');
            const filterMenus = holdingsContainer.querySelectorAll('.portfolioFilterMenu');
            const items = holdingsContainer.querySelectorAll('.holding-item');

            let activeFilters = {
                sector: '',
                category: '',
                country: ''
            };

            // Dropdown Toggles
            filterToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    const dropdown = this.closest('.portfolioFilterDropdown');
                    const isOpen = dropdown.classList.contains('is-open');
                    
                    // Close all other dropdowns
                    holdingsContainer.querySelectorAll('.portfolioFilterDropdown').forEach(d => {
                        if (d !== dropdown) {
                            d.classList.remove('is-open');
                            const otherToggle = d.querySelector('.portfolioFilterToggle');
                            if (otherToggle) otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    });

                    dropdown.classList.toggle('is-open', !isOpen);
                    this.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
                    e.stopPropagation();
                });
            });

            // Close dropdowns on outside click
            document.addEventListener('click', function(event) {
                const filterBar = holdingsContainer.querySelector('.portfolioFilters');
                if (filterBar && !filterBar.contains(event.target)) {
                    holdingsContainer.querySelectorAll('.portfolioFilterDropdown').forEach(dropdown => {
                        dropdown.classList.remove('is-open');
                        const toggle = dropdown.querySelector('.portfolioFilterToggle');
                        if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    });
                }
            });

            // Filter Selection
            holdingsContainer.querySelectorAll('.portfolioFilterMenu button').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const taxonomy = this.getAttribute('data-taxonomy');
                    const value = this.getAttribute('data-filter');
                    const label = this.textContent.trim();

                    activeFilters[taxonomy] = value;

                    // Update toggle button text
                    const dropdown = this.closest('.portfolioFilterDropdown');
                    const toggle = dropdown.querySelector('.portfolioFilterToggle');
                    const icon = toggle.querySelector('i');
                    
                    const defaultText = taxonomy.charAt(0).toUpperCase() + taxonomy.slice(1);
                    const displayText = value ? label : (taxonomy === 'country' ? 'Location' : defaultText);
                    
                    toggle.textContent = displayText + ' ';
                    if (icon) toggle.appendChild(icon);

                    applyFilters();
                    
                    // Close menu
                    dropdown.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    e.stopPropagation();
                });
            });

            // View Switcher
            viewToggleBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.getAttribute('data-portfolio-view');
                    
                    viewToggleBtns.forEach(b => {
                        b.classList.remove('active');
                        b.setAttribute('aria-pressed', 'false');
                    });
                    
                    this.classList.add('active');
                    this.setAttribute('aria-pressed', 'true');
                    
                    portfolioGrid.classList.add('is-view-switching');
                    
                    setTimeout(() => {
                        if (view === 'list') {
                            portfolioGrid.classList.add('is-list');
                        } else {
                            portfolioGrid.classList.remove('is-list');
                        }
                        
                        setTimeout(() => {
                            portfolioGrid.classList.remove('is-view-switching');
                        }, 50);
                    }, 250);
                });
            });

            function applyFilters() {
                items.forEach(item => {
                    const itemSector = item.getAttribute('data-sector');
                    const itemCategory = item.getAttribute('data-category');
                    const itemCountry = item.getAttribute('data-country');

                    const sectorMatch = !activeFilters.sector || itemSector === activeFilters.sector;
                    const categoryMatch = !activeFilters.category || itemCategory === activeFilters.category;
                    const countryMatch = !activeFilters.country || itemCountry === activeFilters.country;

                    if (sectorMatch && categoryMatch && countryMatch) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        }
    });
    </script>
<?php else : ?>
    <div class="container py-5">
        <p>No companies found.</p>
    </div>
<?php endif; ?>
