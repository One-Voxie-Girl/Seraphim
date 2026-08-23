<?php
/**
 * Holdings List ACF Component
 */


/*TODO
 * get javascript for dropdown menus working
 * get company logos overlaid on image in card view
 * fix visuals on web link for both views
 * add filtering for c-shares and vc
 *
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

    ?>



    <div class="holdings-list-container container" id="holdings-container">
        <div class="portfolioFilterBar">
            <div class="portfolioFilters">
                <div class="portfolioFilterDropdown" id="dropdown-sector">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Sector <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-value=""><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_sectors as $term ) : ?>
                            <button type="button" data-value="<?php echo esc_attr( $term->slug ); ?>"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="portfolioFilterDropdown" id="dropdown-category">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Category <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-value=""><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_categories as $term ) : ?>
                            <button type="button" data-value="<?php echo esc_attr( $term->slug ); ?>"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="portfolioFilterDropdown" id="dropdown-country">
                    <button class="portfolioFilterToggle" type="button" aria-expanded="false">
                        Location <i class="ci-Caret_Down_SM"></i>
                    </button>
                    <div class="portfolioFilterMenu">
                        <button type="button" data-value=""><i class="ci-Chevron_Right"></i>All</button>
                        <?php foreach ( $all_countries as $term ) : ?>
                            <button type="button" data-value="<?php echo esc_attr( $term->slug ); ?>"><i class="ci-Chevron_Right"></i><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="portfolioViewToggle" aria-label="View options">
                <button type="button" class="view-btn active" data-portfolio-view="grid" aria-label="Grid view" aria-pressed="true">
                    <i class="ci-More_Grid_Big"></i>
                </button>
                <button type="button" class="view-btn" data-portfolio-view="list" aria-label="List view" aria-pressed="false">
                    <i class="ci-List_Unordered"></i>
                </button>
            </div>
        </div>

        <div class="portfolioGrid">
            <div class="portfolioListHeader" aria-hidden="true">
                <span>Company</span>
                <span>Sector</span>
                <span>Category</span>
                <span>Location</span>
                <span>Assets %</span>
            </div>

            <?php while ( $query->have_posts() ) : $query->the_post();
                $tagline = get_field( 'preview_text' );
                $logo    = get_field( 'logo' );
                $assets_pct = get_field( 'assets_percentage' ) ?: '0';
                $is_ipo = get_field( 'ipo_status' ); // true false radio button or 'yes'
                $is_top_holding = get_field( 'top_holding' ); // true false radio button
                $company_percentage = get_field( 'company_percentage' ) ?: '0';


                $sectors    = get_the_terms( get_the_ID(), 'company-sector' );
                $categories = get_the_terms( get_the_ID(), 'company-category' );
                $countries  = get_the_terms( get_the_ID(), 'country' );

                $sector_name   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->name : '';
                $sector_slug   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->slug : '';
                $sector_parent_slug = '';
                if ( ! is_wp_error( $sectors ) && ! empty( $sectors ) && $sectors[0]->parent ) {
                    $parent_s = get_term( $sectors[0]->parent, 'company-sector' );
                    if ( ! is_wp_error( $parent_s ) && ! empty( $parent_s ) ) $sector_parent_slug = $parent_s->slug;
                }

                $category_name = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->name : '';
                $category_slug = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->slug : '';
                $category_parent_slug = '';
                if ( ! is_wp_error( $categories ) && ! empty( $categories ) && $categories[0]->parent ) {
                    $parent_c = get_term( $categories[0]->parent, 'company-category' );
                    if ( ! is_wp_error( $parent_c ) && ! empty( $parent_c ) ) $category_parent_slug = $parent_c->slug;
                }
                
                $country_name = '';
                $country_slug = '';
                $country_parent_slug = ''; // Add parent slug for filtering
                if ( ! is_wp_error( $countries ) && ! empty( $countries ) ) {
                    $country_term = $countries[0];
                    $country_name = $country_term->name;
                    $country_slug = $country_term->slug;

                    if ( $country_term->parent ) {
                        $parent_term = get_term( $country_term->parent, 'country' );
                        if ( ! is_wp_error( $parent_term ) && ! empty( $parent_term ) ) {
                            $country_name = $parent_term->name . ' - ' . $country_name;
                            $country_parent_slug = $parent_term->slug;
                        }
                    }
                }

                $background_image = get_field( 'background_image' );
                $card_img_url     = ( ! empty( $background_image ) && is_array( $background_image ) ) ? $background_image['url'] : get_the_post_thumbnail_url( get_the_ID(), 'large' );
                ?>
                <a href="<?php the_permalink(); ?>" class="notch-card portfolioHoldingCard holding-item<?php echo ( $is_top_holding == 'true' ) ? ' is-top-holding' : ''; ?>"
                     data-sector="<?php echo esc_attr( $sector_slug ); ?>" 
                     data-sector-parent="<?php echo esc_attr( $sector_parent_slug ); ?>"
                     data-category="<?php echo esc_attr( $category_slug ); ?>" 
                     data-category-parent="<?php echo esc_attr( $category_parent_slug ); ?>"
                     data-country="<?php echo esc_attr( $country_slug ); ?>"
                     data-country-parent="<?php echo esc_attr( $country_parent_slug ); ?>"
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
                            <div class="tagline white65 small"><?php echo esc_html($tagline ); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="detailsCon">
                        <div class="captionCon">
                            <?php if ( $sector_name ) : ?>
                                <span class="caption"><?php echo esc_html( $sector_name ); ?></span>
                            <?php endif; ?>
                            <?php if ( $category_name ) : ?>
                                <span class="caption"><?php echo esc_html( $category_name ); ?> </span>
                            <?php endif; ?>
                            <?php if ( $assets_pct ) : ?>
                                <span class="caption"><?php echo esc_html( $assets_pct ); ?>%</span>
                            <?php endif; ?>
                        </div>
                        <div class="buttonCon">
                            <span class="tertiary small">Learn more</span>
                        </div>
                    </div>

                    <div class="portfolioListValues" aria-hidden="true">
                        <span><?php echo esc_html( $sector_name ); ?></span>
                        <span><?php echo esc_html( $category_name ); ?></span>
                        <span><?php echo esc_html( $country_name ); ?></span>
                        <span><?php echo esc_html( $assets_pct ); ?>%</span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>

    <script>
        (function() {
            // PORTFOLIO FILTERS
            function initPortfolioFilters() {
                var filterBar = document.querySelector('.portfolioFilters');
                if (!filterBar) return;

                var dropdowns = Array.prototype.slice.call(filterBar.querySelectorAll('.portfolioFilterDropdown'));

                var closeDropdowns = function (currentDropdown) {
                    dropdowns.forEach(function (dropdown) {
                        if (dropdown === currentDropdown) return;
                        dropdown.classList.remove('is-open');
                        var toggle = dropdown.querySelector('.portfolioFilterToggle');
                        if (toggle) toggle.setAttribute('aria-expanded', 'false');
                    });
                };

                dropdowns.forEach(function (dropdown) {
                    var toggle = dropdown.querySelector('.portfolioFilterToggle');
                    var menuOptions = dropdown.querySelectorAll('.portfolioFilterMenu button');
                    if (!toggle) return;

                    toggle.addEventListener('click', function (e) {
                        e.stopPropagation();
                        var shouldOpen = !dropdown.classList.contains('is-open');
                        closeDropdowns(dropdown);
                        dropdown.classList.toggle('is-open', shouldOpen);
                        toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');
                    });

                    menuOptions.forEach(function (option) {
                        option.addEventListener('click', function () {
                            var icon = toggle.querySelector('i');
                            var selectedText = option.textContent.trim();
                            var filterValue = option.getAttribute('data-value');

                            toggle.setAttribute('data-active-value', filterValue);
                            toggle.textContent = selectedText + ' ';
                            if (icon) toggle.appendChild(icon);
                            dropdown.classList.remove('is-open');
                            toggle.setAttribute('aria-expanded', 'false');

                            filterItems();
                        });
                    });
                });

                document.addEventListener('click', function (event) {
                    if (!filterBar.contains(event.target)) closeDropdowns();
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') closeDropdowns();
                });

                function filterItems() {
                    var activeSector = document.querySelector('#dropdown-sector .portfolioFilterToggle').getAttribute('data-active-value') || '';
                    var activeCategory = document.querySelector('#dropdown-category .portfolioFilterToggle').getAttribute('data-active-value') || '';
                    var activeCountry = document.querySelector('#dropdown-country .portfolioFilterToggle').getAttribute('data-active-value') || '';

                    var cards = document.querySelectorAll('.notch-card');
                    cards.forEach(function(card) {
                        var sector = card.getAttribute('data-sector') || '';
                        var sectorParent = card.getAttribute('data-sector-parent') || '';
                        var category = card.getAttribute('data-category') || '';
                        var categoryParent = card.getAttribute('data-category-parent') || '';
                        var country = card.getAttribute('data-country') || '';
                        var countryParent = card.getAttribute('data-country-parent') || '';

                        var sectorMatch = !activeSector || sector === activeSector || sectorParent === activeSector;
                        var categoryMatch = !activeCategory || category === activeCategory || categoryParent === activeCategory;
                        var countryMatch = !activeCountry || country === activeCountry || countryParent === activeCountry;

                        if (sectorMatch && categoryMatch && countryMatch) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                }
            }

            // PORTFOLIO VIEW TOGGLE
            function initPortfolioViewToggle() {
                var viewToggle = document.querySelector('.portfolioViewToggle');
                var portfolioGrid = document.querySelector('.portfolioGrid');
                if (!viewToggle || !portfolioGrid) return;

                var buttons = Array.prototype.slice.call(viewToggle.querySelectorAll('[data-portfolio-view]'));

                buttons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var selectedView = button.getAttribute('data-portfolio-view');
                        var isListView = selectedView === 'list';

                        portfolioGrid.classList.add('is-view-switching');
                        portfolioGrid.classList.toggle('is-list', isListView);

                        buttons.forEach(function (toggleButton) {
                            var isActive = toggleButton === button;
                            toggleButton.classList.toggle('active', isActive);
                            toggleButton.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                        });

                        window.requestAnimationFrame(function () {
                            window.requestAnimationFrame(function () {
                                portfolioGrid.classList.remove('is-view-switching');
                            });
                        });
                    });
                });
            }

            // Initialize
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initPortfolioFilters();
                    initPortfolioViewToggle();
                });
            } else {
                initPortfolioFilters();
                initPortfolioViewToggle();
            }
        })();
    </script>
<?php else : ?>
    <div class="container py-5">
        <p>No companies found.</p>
    </div>
<?php endif; ?>
