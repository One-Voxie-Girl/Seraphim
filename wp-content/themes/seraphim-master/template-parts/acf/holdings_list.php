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

if ( $query->have_posts() ) : ?>
    <div class="holdings-list-container container" id="holdings-container">
        <div class="holdings-filter-bar d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="filters d-flex flex-wrap gap-2">
                <select id="filter-sector" class="form-select w-auto">
                    <option value="">All Sectors</option>
                    <?php foreach ( $all_sectors as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="filter-category" class="form-select w-auto">
                    <option value="">All Categories</option>
                    <?php foreach ( $all_categories as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="filter-country" class="form-select w-auto">
                    <option value="">All Locations</option>
                    <?php foreach ( $all_countries as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="holdings-view-switcher d-flex">
                <button class="view-btn active" data-view="list" aria-label="List View">
                    <i class="ci-List_Unordered"></i>
                </button>
                <button class="view-btn" data-view="grid" aria-label="Grid View">
                    <i class="ci-More_Grid_Big"></i>
                </button>
            </div>
        </div>

        <div class="holdings-list-view">
            <div class="holdings-list-header d-none d-md-flex row py-3 border-bottom">
                <div class="col-md-4"><strong>Company</strong></div>
                <div class="col-md-3"><strong>Sector</strong></div>
                <div class="col-md-3"><strong>Category</strong></div>
                <div class="col-md-2"><strong>Country</strong></div>
            </div>
            <div class="holdings-list-body">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $tagline = get_field( 'tagline' );

                    // Get taxonomy terms for Sector, Category and Country
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
                            }
                        }
                    }
                    ?>
                    <div class="holdings-list-row row py-4 border-bottom align-items-center holding-item d-flex" data-sector="<?php echo esc_attr( $sector_slug ); ?>" data-category="<?php echo esc_attr( $category_slug ); ?>" data-country="<?php echo esc_attr( $country_slug ); ?>">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="company-name h5 mb-1"><?php the_title(); ?></div>
                            <?php if ( $tagline ) : ?>
                                <div class="company-tagline text-muted small"><?php echo esc_html( $tagline ); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                            <span class="d-md-none text-muted small d-block">Sector:</span>
                            <div class="company-sector"><?php echo esc_html( $sector_name ); ?></div>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-0">
                            <span class="d-md-none text-muted small d-block">Category:</span>
                            <div class="company-category"><?php echo esc_html( $category_name ); ?></div>
                        </div>
                        <div class="col-12 col-md-2">
                            <span class="d-md-none text-muted small d-block">Country:</span>
                            <div class="company-country"><?php echo esc_html( $country_name ); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="holdings-grid-view d-none">
            <div class="row">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $tagline    = get_field( 'tagline' );
                    $sectors    = get_the_terms( get_the_ID(), 'company-sector' );
                    $categories = get_the_terms( get_the_ID(), 'company-category' );
                    $countries  = get_the_terms( get_the_ID(), 'country' );

                    $sector_name   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->name : '';
                    $sector_slug   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->slug : '';
                    $category_name = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->name : '';
                    $category_slug = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->slug : '';
                    $country_slug  = ( ! is_wp_error( $countries ) && ! empty( $countries ) ) ? $countries[0]->slug : '';

                    $featured_img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    $logo         = get_field( 'logo' ); // Assuming there is a logo field
                    ?>
                    <div class="col-12 col-sm-6 col-lg-4 mb-4 holding-item" data-sector="<?php echo esc_attr( $sector_slug ); ?>" data-category="<?php echo esc_attr( $category_slug ); ?>" data-country="<?php echo esc_attr( $country_slug ); ?>">
                        <div class="notch-card">
                            <span class="notch-card__tab" aria-hidden="true"></span>
                            <div class="badge">
                                <span class="text"><?php echo esc_html( $sector_name ); ?></span>
                            </div>
                            <div class="imageCon" style="background-image: url('<?php echo esc_url( $featured_img ); ?>');">
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
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const holdingsContainer = document.getElementById('holdings-container');
        if (holdingsContainer) {
            const switcherBtns = holdingsContainer.querySelectorAll('.view-btn');
            const listView = holdingsContainer.querySelector('.holdings-list-view');
            const gridView = holdingsContainer.querySelector('.holdings-grid-view');
            const items = holdingsContainer.querySelectorAll('.holding-item');

            const sectorFilter = document.getElementById('filter-sector');
            const categoryFilter = document.getElementById('filter-category');
            const countryFilter = document.getElementById('filter-country');

            // View Switcher Logic
            switcherBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const view = this.getAttribute('data-view');

                    // Update buttons
                    switcherBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Update views
                    if (view === 'grid') {
                        listView.classList.add('d-none');
                        gridView.classList.remove('d-none');
                    } else {
                        gridView.classList.add('d-none');
                        listView.classList.remove('d-none');
                    }
                });
            });

            // Filtering Logic
            function applyFilters() {
                const sector = sectorFilter.value;
                const category = categoryFilter.value;
                const country = countryFilter.value;

                items.forEach(item => {
                    const itemSector = item.getAttribute('data-sector');
                    const itemCategory = item.getAttribute('data-category');
                    const itemCountry = item.getAttribute('data-country');

                    const sectorMatch = !sector || itemSector === sector;
                    const categoryMatch = !category || itemCategory === category;
                    const countryMatch = !country || itemCountry === country;

                    if (sectorMatch && categoryMatch && countryMatch) {
                        item.classList.remove('d-none');
                        if (item.classList.contains('holdings-list-row')) {
                            item.classList.add('d-flex');
                        }
                    } else {
                        item.classList.add('d-none');
                        item.classList.remove('d-flex');
                    }
                });
            }

            sectorFilter.addEventListener('change', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);
            countryFilter.addEventListener('change', applyFilters);
        }
    });
    </script>
<?php else : ?>
    <div class="container py-5">
        <p>No companies found.</p>
    </div>
<?php endif; ?>
