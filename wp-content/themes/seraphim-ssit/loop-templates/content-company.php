<?php
/**
 * Template part for displaying cars
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

$company_name      = get_field( 'company_name' );
$company_value     = get_field( 'valuation' );
$company_status    = get_field( 'status' );
$company_country   = get_field( 'country' );
$company_website   = get_field( 'website_link' );


$terms = get_the_terms( $post->ID, 'make' );
?>

<article id="company-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="company-header">
		<?php the_title( '<h1 class="company-title">', '</h1>' ); ?>
		
	</header><!-- .company-header -->

	<figure class="company-thumbnail">
		<?php the_post_thumbnail( 'full' ); ?>
	</figure><!-- .company-thumbnail -->

	<div class="company-content">
		<ul class="company-meta">
			<li class="company-meta__title"><strong>Title:</strong> <?php echo esc_html( $company_name ); ?></li>
			<li class="company-meta__value"><strong>Value:</strong> <?php echo esc_html( $company_value ); ?></li> 
			<li class="company-meta__status"><strong>Status:</strong> <?php echo esc_html( $company_status ); ?></li>
			<li class="company-meta__country"><strong>Country:</strong> <?php echo esc_html( $company_country ); ?></li>
			<li class="company-meta__website"><strong>Website:</strong> <?php echo esc_html( $company_website ); ?></li>
		</ul><!-- .company-meta -->

		
	</div><!-- .company-content -->

</article><!-- #company-<?php the_ID(); ?> -->